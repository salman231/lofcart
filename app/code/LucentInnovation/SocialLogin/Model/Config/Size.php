<?php
namespace LucentInnovation\SocialLogin\Model\Config;

class Size implements \Magento\Framework\Option\ArrayInterface
{ 
    public function toOptionArray()
    {
        $data =  array();
        return [
            ['value' => 0, 'label' => __('Select Size')],
            ['value' => 24, 'label' => __('Extra Small')],
            ['value' => 48, 'label' => __('Small')],
            ['value' => 64, 'label' => __('Medium')],
            ['value' => 128, 'label' => __('Large')],
        ];
    }
}