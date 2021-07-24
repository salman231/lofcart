<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 *
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Controller\Adminhtml\Logs;

/**
 * Class Preview
 * @package Cowell\AbandonedCart\Controller\Adminhtml\Logs
 */
class Preview extends \Magento\Email\Controller\Adminhtml\Email\Template
{
    /**
     * Render layout preview
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        try {
            $this->_view->loadLayout();
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Email Preview'));
            $this->_view->renderLayout();
            $this->getResponse()->setHeader('Content-Security-Policy', "script-src 'none'");
        } catch (\Exception $e) {
            $this->messageManager->addError(__('An error occurred. The email template can not be opened for preview.'));
            $this->_redirect('adminhtml/*/');
        }
    }
}
