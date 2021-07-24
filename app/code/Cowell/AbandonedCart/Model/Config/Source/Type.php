<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Type
 * @package Cowell\AbandonedCart\Model\Config\Source
 */
class Type implements OptionSourceInterface
{
    /**
     * @var \Cowell\AbandonedCart\Model\MailLog
     */
    protected $mailLogs;

    /**
     * Type constructor.
     * @param \Cowell\AbandonedCart\Model\MailLog $mailLogs
     */
    public function __construct(\Cowell\AbandonedCart\Model\MailLog $mailLogs)
    {
        $this->mailLogs = $mailLogs;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->mailLogs->getAvailableType();
        $options          = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
