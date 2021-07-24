<?php
namespace LucentInnovation\SocialLogin\Model\Config;

class FontFamily implements \Magento\Framework\Option\ArrayInterface
{ 
    public function toOptionArray()
    {
        $data =  array();
        return [
            ['value' => 0, 'label' => __('Select Font Family')],
            ['value' => 'Roboto', 'label' => __('Roboto')],
            ['value' => 'Sofia', 'label' => __('Sofia')],
            ['value' => 'Merriweather', 'label' => __('Merriweather')],
            ['value' => 'Wallpoet', 'label' => __('Wallpoet')],
            ['value' => 'Work+Sans', 'label' => __('Work Sans')],
            ['value' => 'Open+Sans', 'label' => __('Open Sans')],
            ['value' => 'Oswald', 'label' => __('Oswald')],
            ['value' => 'Ubuntu', 'label' => __('Ubuntu')],
            ['value' => 'PT+Sans', 'label' => __('PT Sans')],
            ['value' => 'Cinzel', 'label' => __('Cinzel')],
            ['value' => 'Monda', 'label' => __('Monda')],
            ['value' => 'Kaushan+Script', 'label' => __('Kaushan Script')],
            ['value' => 'Archivo+Black', 'label' => __('Archivo Black')],
            ['value' => 'Istok+Web', 'label' => __('Istok Web')],
            ['value' => 'Cantarell', 'label' => __('Cantarell')],
            ['value' => 'Adamina', 'label' => __('Adamina')],
            ['value' => 'Viga', 'label' => __('Viga')],
        ];
    }
}