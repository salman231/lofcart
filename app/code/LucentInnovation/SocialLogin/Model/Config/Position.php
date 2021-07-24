<?php
namespace LucentInnovation\SocialLogin\Model\Config;

class Position implements \Magento\Framework\Option\ArrayInterface
{ 
    public function toOptionArray()
    {
        $data =  array();
        return [
            ['value' => 0, 'label' => __('Left')],
            ['value' => 1, 'label' => __('Right')]
        ];
    }
}