<?php

namespace MageArray\AjaxCompare\Helper\Product;

/**
 * Catalog Product Compare Helper
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Compare extends \Magento\Catalog\Helper\Product\Compare
{
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;
    
    const AJAXCOMPARE_IS_ENABLED = 'ajaxcompare/setting/enable';

    const AJAXCOMPARE_SHOW_BOX = 'ajaxcompare/setting/showbox';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Wishlist\Helper\Data $wishlistHelper,
        \Magento\Framework\Data\Helper\PostHelper $postHelper,
        \Magento\Catalog\Block\Product\Compare\ListCompare $listcompare,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        $this->storemanager = $storeManager;
        $this->listcompare = $listcompare;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->assetRepo = $assetRepo;
        $this->_escaper = $escaper;
        $this->_layout = $layout;
        parent::__construct(
            $context,
            $storeManager,
            $itemCollectionFactory,
            $catalogProductVisibility,
            $customerVisitor,
            $customerSession,
            $catalogSession,
            $formKey,
            $wishlistHelper,
            $postHelper
        );
    }

    public function getProductImage($product)
    {
        if ($product->getImage() != 'no_selection' && $product->getImage() != "") {
            return $this->storemanager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                . 'catalog/product' . $product->getImage();
        } else {

            return $this->getDefaultPlaceholder();
        }
    }

    public function getCompareContent()
    {

        $items = $this->listcompare->getItems();
        $count = count($items);
        if ($count) {
            $html = '<div class="comparison_results comparisonBar slideInUp animated" >
			<a href="#" class="comparelist">
			<div class="compare_header">
				<span class="h2 title"> 
					<span class="numberOfItems">' . $count . '</span>' . __(" Compare Products") . ' 
				<i class="fa fa-long-arrow-up" aria-hidden="true"></i>					
				</span>
			</div>';

            $html .= '</a>
			<div class="inner" style="display:none">
				<div class="comparison_selectedproducts">
				<table width="100%"><tr>';

            foreach ($items as $_item) {
                $remove = $this->getPostDataRemove($_item);
                $image = $this->getProductImage($_item);
                $html .= '<td><div data-productid="' . $_item->getId() . '" class="comparisonProductBox">
						<a href="#" data-post="' . $this->_escaper->escapeHtml($remove) . '" class="action delete" title="' . __('Remove Product') . '"><span>X</span></a>
						<a href="' . $this->listcompare->getProductUrl($_item) . '">  
							<img width="86" height="86" src="' . $image . '" class="product_image"> 
							<div class="product_name"><span>' . $_item->getName() . '</span></div> 
						</a></div></td>';
            }
            $html .= '<tr></table>';
            $compareurl = $this->getListUrl();
            $html .= '</div>
					<div class="info fadeInUp animated">
						<div class="primary">
							<a href="' . $compareurl . '" class="action compare primary"><span>' . __('Compare') . '</span></a>
						</div>
					</div> 
				</div>
				</div>';
            $html .= '<script>
					require([	
						"jquery","MageArray_AjaxCompare/js/ajaxCompare"
						], function ($,addCompare) {
							"use strict";
						   
						$(document).ready(function() {
							$(".comparelist").on("click",function(e){
								e.preventDefault();
								if($(".inner").css("display")=="none"){
									$(".inner").slideToggle();
									$(".comparisonBar").addClass("open");
								}else{
									$(".inner").slideToggle();
									$(".comparisonBar").removeClass("open");
								}
							});
						});
						
					});
					</script>';
        }
        if (!empty($html)) {
            return $html;
        }
    }

    public function getDefaultPlaceholder()
    {
        $image = $this->_scopeConfig->getValue('catalog/placeholder/small_image_placeholder');
        if (!$image) {
            return $this->assetRepo
                ->getUrl('Magento_Catalog::images/product/placeholder/small_image.jpg');
        }
        return $image;
    }

    public function isActive()
    {
        return $this->_scopeConfig->getValue(
            self::AJAXCOMPARE_IS_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getCurrentStoreId()
        );
    }

    public function showBox()
    {
        return $this->_scopeConfig->getValue(
            self::AJAXCOMPARE_SHOW_BOX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getCurrentStoreId()
        );
    }

    public function getCurrentStoreId()
    {
        return $this->storemanager->getStore()->getStoreId();
    }
}
