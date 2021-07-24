<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Component\MassAction\Filter;
use Cowell\AbandonedCart\Model\ResourceModel\MailLog\CollectionFactory;
use Cowell\AbandonedCart\Model\MailLog;

/**
 * Class ClearLogs
 * @package Cowell\AbandonedCart\Controller\Adminhtml\Logs
 */
class ClearLogs extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Cowell_AbandonedCart::delete';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var MailLog
     */
    private $model;

    /**
     * ClearLogs constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param MailLog $model
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory, MailLog $model)
    {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->model             = $model;
        parent::__construct($context);
    }

    /**
     * Execute Action
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $resource = $this->model->getResource();
        try {
            $resource->getConnection()->truncateTable($resource->getMainTable());
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Logs have been cannot delete.'));
        }

        $this->messageManager->addSuccess(__('Logs have been deleted.'));

        return $resultRedirect->setPath('*/*/');
    }
}
