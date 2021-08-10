<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_StorePickup
 */


namespace Amasty\StorePickup\Block\Onepage;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->moduleManager = $moduleManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function process($jsLayout)
    {
        if ($this->isCompatibleCheckout()) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['shippingAddress']['template'] = 'Amasty_StorePickup/shipping';
        }

        return $jsLayout;
    }

    /**
     * Check checkout is compatible with Table Rates
     *
     * @return bool
     */
    private function isCompatibleCheckout()
    {
        return !($this->moduleManager->isEnabled('Magestore_OneStepCheckout')
            || ($this->moduleManager->isEnabled('IWD_Opc')
                && $this->scopeConfig->getValue('iwd_opc/general/enable')));
    }
}
