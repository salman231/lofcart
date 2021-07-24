<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Model\Config\Source;
/**
 * Class Status
 * @package Cowell\AbandonedCart\Model\Config\Source
 */
class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return
            [
                [
                    'value' => 1,
                    'label' => __('Sent')
                ],
                [
                    'value' => 0,
                    'label' => __('Error')
                ]
            ];
    }
}
