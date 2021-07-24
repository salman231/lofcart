<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 *
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Controller\Adminhtml\Logs;

use Cowell\AbandonedCart\Model\Email;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Cowell\AbandonedCart\Logger\Logger;

/**
 * Class Resend
 * @package Cowell\AbandonedCart\Controller\Adminhtml\Logs
 */
class Resend extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Cowell\AbandonedCart\Logger\Logger
     */
    protected $logger;
    /**
     * @var ResourceModel\MailLog\CollectionFactory
     */
    protected $mailLogFactory;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;
    /**
     * \TimezoneInterface $date,
     */
    protected $date;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Cowell\AbandonedCart\Logger\Logger $logger
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $jsonHelper,
        Logger $logger,
        \Cowell\AbandonedCart\Model\MailLogFactory $mailLogFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    )
    {

        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper        = $jsonHelper;
        $this->logger            = $logger;
        $this->mailLogFactory    = $mailLogFactory;
        $this->transportBuilder  = $transportBuilder;
    }

    /**
     * Send again and redirect page log index
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $id  = $this->getRequest()->getParam('entity_id');
        $log = $this->mailLogFactory->create()->load($id);
        if (!empty($log->getData())) {
            try {
                $this->sendAgain($log);
                $this->messageManager->addSuccessMessage(__('Email sent successfully'));
            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
                $this->messageManager->addErrorMessage(__('An error occured when sending this mail.'));
            }
        }
        $this->_redirect('emaillogs/logs/index');
    }

    /**
     * Send mail again
     *
     * @param $log
     * @throws \Magento\Framework\Exception\MailException
     */
    protected function sendAgain($log)
    {
        $this->transportBuilder->setTemplateIdentifier('quote_alert_send_again')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $log->getStoreId()])
            ->setTemplateVars([
                'body'    => htmlspecialchars_decode($log->getEmailContent()),
                'subject' => $log->getEmailSubject()
            ])
            ->setFrom($log->getSender())
            ->addTo($log->getEmailTo())
            ->getTransport()
            ->sendMessage();
        $log->setStatus(Email::STATUS_SUCCESS)->save();
    }
}
