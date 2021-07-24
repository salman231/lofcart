<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Model;

/**
 * Class Observer
 * @package Cowell\AbandonedCart\Model
 */
class Observer
{
    /**
     * @var ResourceModel\MailLog
     */
    protected $mailLog;

    /**
     * @var \Magento\ProductAlert\Model\EmailFactory
     */
    protected $emailFactory;

    /**
     * Observer constructor.
     * @param ResourceModel\MailLog $mailLog
     * @param EmailFactory $emailFactory
     */
    public function __construct(
        \Cowell\AbandonedCart\Model\ResourceModel\MailLog $mailLog,
        \Cowell\AbandonedCart\Model\EmailFactory $emailFactory
    )
    {
        $this->mailLog      = $mailLog;
        $this->emailFactory = $emailFactory;
    }

    /**
     * Set date time and type ComeBack
     *
     * @param Email $email
     * @return $this
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function _processComeBack(Email $email)
    {
        $dataItems = $this->mailLog->getQuoteByTimeExist();
        $email->setDataItems($dataItems);
        $email->setType(Email::TYPE_COME_BACK_EMAIL);
        $email->send();

        return $this;
    }

    /**
     * Set date time and type OutOfStock
     *
     * @param Email $email
     * @return $this
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _processOutOfStock(Email $email)
    {
        $dataItems = $this->mailLog->getQuoteByOutStock();
        $email->setDataItems($dataItems);
        $email->setType(Email::TYPE_LOW_STOCK_EMAIL);
        $email->send();

        return $this;
    }

    /**
     * Set Process
     * @return $this
     * @throws \Exception
     */
    public function process()
    {
        $email = $this->emailFactory->create();
        $this->_processOutOfStock($email);
        $this->_processComeBack($email);

        return $this;
    }
}
