<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 *
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;
use Cowell\AbandonedCart\Model\MailLog;

/**
 * Class Delete
 * @package Cowell\AbandonedCart\Controller\Adminhtml\Logs
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Cowell_AbandonedCart::delete';

    /**
     * @var MailLog
     */
    protected $emailFactory;

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Cowell_AbandonedCart::delete');
    }

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param MailLog $mailLog
     */
    public function __construct(Action\Context $context, MailLog $mailLog)
    {
        $this->emailFactory = $mailLog;
        parent::__construct($context);
    }

    /**
     * Delete log by entity id and set page Redirect
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id             = (int)$this->getRequest()->getParam('entity_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $model  = NULL;
            $status = true;
            try {
                $model = $this->emailFactory->load($id);
                if ($model->getId()) {
                    $model->delete();
                    $this->messageManager->addSuccess(__('The log has been deleted.'));
                } else {
                    $this->messageManager->addError(__('Can\'t find a record to delete.'));
                }

            } catch (\Exception $e) {
                $status = false;
                $this->messageManager->addError($e->getMessage());
            } finally {
                $this->_eventManager->dispatch(
                    'mail_logs_on_delete',
                    ['logData' => $model, 'status' => $status]
                );
                return $status ? $resultRedirect->setPath('*/*/') : $resultRedirect->setPath(
                    '*/*/edit',
                    ['entity_id' => $id]
                );
            }
        }

        // display error message
        $this->messageManager->addError(__('Can\'t find a record to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
