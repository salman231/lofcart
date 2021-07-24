<?php

namespace MageArray\AjaxCompare\Block;

class Compare extends \Magento\Framework\View\Element\Template
{
    protected $_config;

    /* For get the configuration value of default extension settings*/
    public function getConfig()
    {
        return $this->_scopeConfig->getValue('ajaxaddtocart/setting', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function manageHeaderContent()
    {

        $this->pageConfig->addPageAsset('MageArray_AjaxCompare::css/font-awesome.css');
        $this->pageConfig->addPageAsset('MageArray_AjaxCompare::css/font-awesome.min.css');
        /* $this->pageConfig->addPageAsset('MageArray_AjaxCompare::js/ajaxCompare.js'); */
    }

    /* For add js for custom editor in configuration settings in backend */

    protected function _toHtml()
    {
        return parent::_toHtml();
    }
}
