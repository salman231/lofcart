<?php
namespace LucentInnovation\SocialLogin\Model\Config;

class PagePosition implements \Magento\Framework\Option\ArrayInterface
{ 
    public function toOptionArray()
    {
        $data =  array();
        return [
            ['value' => 0, 'label' => __('Above Customer Login Form')],
            ['value' => 1, 'label' => __('Below Customer Login Form')],
            ['value' => 2, 'label' => __('Above Customer Registration Form')],
            ['value' => 3, 'label' => __('Below Customer Registration Form')],
            ['value' => 4, 'label' => __('Above Customer Forgot Password Form')],
            ['value' => 5, 'label' => __('Below Customer Forgot Password Form')],
        ];
    }
}