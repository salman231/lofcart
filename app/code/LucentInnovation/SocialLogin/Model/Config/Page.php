<?php
namespace LucentInnovation\SocialLogin\Model\Config;

class Page implements \Magento\Framework\Option\ArrayInterface
{ 
    public function toOptionArray()
    {
        $data =  array();
        return [
            ['value' => 'login', 'label' => __('Login Page')],
            ['value' => 'create', 'label' => __('Registration Page')],
            ['value' => 'forgotpassword', 'label' => __('Forgot Password Page')]
        ];
    }
}