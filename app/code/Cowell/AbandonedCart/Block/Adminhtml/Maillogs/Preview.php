<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 *
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Block\Adminhtml\Maillogs;

use Cowell\AbandonedCart\Model\MailLog;

/**
 * Class Preview
 * @package Cowell\AbandonedCart\Block\Adminhtml\Maillogs
 */
class Preview extends \Magento\Backend\Block\Widget
{
    /**
     * @var MailLog
     */
    protected $emailFactory;

    /**
     * Preview constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param MailLog $mailLog
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        MailLog $mailLog,
        array $data = []
    )
    {
        $this->emailFactory = $mailLog;
        parent::__construct($context, $data);
    }

    /**
     * Return string template
     *
     * @return string
     */
    protected function _toHtml()
    {
        $id = $this->getRequest()->getParam('entity_id');
        try {
            $log               = $this->emailFactory->load($id);
            $templateProcessed = $log->getEmailContent();
        } catch (\Exception $e) {
            $templateProcessed = $e->getMessage();
        }
        return $templateProcessed;
    }
}
