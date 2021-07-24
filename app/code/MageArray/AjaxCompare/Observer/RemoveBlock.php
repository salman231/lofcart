<?php

namespace MageArray\AjaxCompare\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RemoveBlock implements ObserverInterface
{
    protected $_scopeConfig;

    public function __construct(
        \MageArray\AjaxCompare\Helper\Product\Compare $helper
    ) {
        $this->_helper = $helper;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $observer->getLayout();
        $block = $layout->getBlock('catalog.compare.sidebar');
        if ($block) {
            $remove = $this->_helper->isActive();
            $showBox = $this->_helper->showBox();

            if ($remove && $showBox) {
                $layout->unsetElement('catalog.compare.sidebar');
            }
        }
    }
}
