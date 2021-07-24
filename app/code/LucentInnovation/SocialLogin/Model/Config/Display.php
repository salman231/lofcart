<?php
namespace LucentInnovation\SocialLogin\Model\Config;

class Display implements \Magento\Framework\Option\ArrayInterface
{ 
    public function toOptionArray()
    {
        $data =  array();
        return [
            ['value' => '0', 'label' => __('Popup')],
            ['value' => '1', 'label' => __('Floating')],
            ['value' => '2', 'label' => __('Page')]
        ];
    }
}