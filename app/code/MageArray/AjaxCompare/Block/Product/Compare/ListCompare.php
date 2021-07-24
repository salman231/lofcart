<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MageArray\AjaxCompare\Block\Product\Compare;

/**
 * Catalog products compare block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ListCompare extends \Magento\Catalog\Block\Product\Compare\ListCompare
{
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\App\Action\Context $actionContext,
        array $data = []
    ) {
        $this->actionContext = $actionContext;
        parent::__construct(
            $context,
            $urlEncoder,
            $itemCollectionFactory,
            $catalogProductVisibility,
            $customerVisitor,
            $httpContext,
            $currentCustomer,
            $data
        );
    }

    protected function _prepareLayout()
    {
        $currentPageXML = $this->actionContext->getRequest()->getFullActionName();
        if ($currentPageXML == "catalog_product_compare_index") {
            $this->pageConfig->getTitle()->set(
                __('Products Comparison List') . ' - ' . $this->pageConfig->getTitle()->getDefault()
            );
            return parent::_prepareLayout();
        } else {
            return '';
        }
    }

    public function getCompareboxUrl()
    {
        return $this->getUrl() . 'ajaxcompare/product/comparebox';
    }
}
