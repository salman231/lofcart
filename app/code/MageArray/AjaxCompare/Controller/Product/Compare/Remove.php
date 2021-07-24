<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MageArray\AjaxCompare\Controller\Product\Compare;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\View\Result\PageFactory;

class Remove extends \Magento\Catalog\Controller\Product\Compare\Remove
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository,
        \MageArray\AjaxCompare\Helper\Product\Compare $compareHelper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->_compareHelper = $compareHelper;
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct(
            $context,
            $compareItemFactory,
            $itemCollectionFactory,
            $customerSession,
            $customerVisitor,
            $catalogProductCompareList,
            $catalogSession,
            $storeManager,
            $formKeyValidator,
            $resultPageFactory,
            $productRepository
        );
    }

    /**
     * Remove item from compare list
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                $product = null;
            }

            if ($product) {
                /** @var $item \Magento\Catalog\Model\Product\Compare\Item */
                $item = $this->_compareItemFactory->create();
                if ($this->_customerSession->isLoggedIn()) {
                    $item->setCustomerId($this->_customerSession->getCustomerId());
                } elseif ($this->_customerId) {
                    $item->setCustomerId($this->_customerId);
                } else {
                    $item->addVisitorId($this->_customerVisitor->getId());
                }

                $item->loadByProduct($product);
                /** @var $helper \Magento\Catalog\Helper\Product\Compare */
                $helper = $this->_objectManager->get(\Magento\Catalog\Helper\Product\Compare::Class);
                if ($item->getId()) {
                    $item->delete();
                    $productName = $this->_objectManager->get(\Magento\Framework\Escaper::Class)
                        ->escapeHtml($product->getName());
                    if ($this->_compareHelper->isActive()) {
                        $count = count($this->_compareHelper->listcompare->getItems());
                        $this->messageManager->addSuccess(
                            __('You removed product %1 from the comparison list.', $productName)
                        );
                        if ($count < 1) {
                            $this->messageManager->addNotice(
                                __('You have no items to compare.')
                            );
                        }
                        $comparePopup = $this->_compareHelper->getCompareContent();
                        $result = $this->resultJsonFactory->create()->setData([
                            'status' => 1,
                            'message' => "product is removed",
                            'compare_popup' => $comparePopup,
                            "product" => $productId,
                            "product_remain" => $count
                        ]);
                    } else {
                        $this->messageManager->addSuccess(
                            __('You removed product %1 from the comparison list.', $productName)
                        );
                    }
                    $this->_eventManager->dispatch(
                        'catalog_product_compare_remove_product',
                        ['product' => $item]
                    );
                    $helper->calculate();
                }
            }
        }
        if ($this->_compareHelper->isActive()) {
            return $result;
        }
        if (!$this->getRequest()->getParam('isAjax', false)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setRefererOrBaseUrl();
        }
    }
}
