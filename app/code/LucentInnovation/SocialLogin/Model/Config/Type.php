<?php
namespace LucentInnovation\SocialLogin\Model\Config;

class Type implements \Magento\Framework\Option\ArrayInterface
{ 
    public function toOptionArray()
    {
        $data =  array();
        return [
            ['value' => 0, 'label' => __('Select Type')],
            ['value' => 1, 'label' => __('Logo')],
            ['value' => 2, 'label' => __('Button')]
        ];
    }
}