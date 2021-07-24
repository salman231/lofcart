<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Controller\Adminhtml\Logs;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Cowell\AbandonedCart\Model\ResourceModel\MailLog\CollectionFactory;

/**
 * Class MassDelete
 * @package Cowell\AbandonedCart\Controller\Adminhtml\Logs
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
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
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute Mass Delete Action
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $logCollection  = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $logCollection->getSize();

            if ($collectionSize > 0) {
                foreach ($logCollection as $item) {
                    $item->delete();
                }
            }
            $this->messageManager->addSuccess(__('A total of %1 item(s) were deleted.', $collectionSize));
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
