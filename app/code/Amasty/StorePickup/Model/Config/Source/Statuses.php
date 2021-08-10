<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_StorePickup
 */


namespace Amasty\StorePickup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Statuses implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '0' => __('Inactive'),
            '1' => __('Active'),
        ];
    }
}
