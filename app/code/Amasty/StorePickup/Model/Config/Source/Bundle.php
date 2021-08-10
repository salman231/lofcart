<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_StorePickup
 */


namespace Amasty\StorePickup\Model\Config\Source;

class Bundle implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('As in "Ship Bundle Items" setting')],
            ['value' => '1', 'label' => __('From bundle product')],
            ['value' => '2', 'label' => __('From items in bundle')],
        ];
    }
}
