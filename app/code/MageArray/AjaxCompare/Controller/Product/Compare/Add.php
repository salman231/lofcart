<?php

namespace MageArray\AjaxCompare\Controller\Product\Compare;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\View\Result\PageFactory;

class Add extends \Magento\Catalog\Controller\Product\Compare\Add
{
    /**
     * Add item to compare list
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
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

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setRefererUrl();
        }
        $productId = (int)$this->getRequest()->getParam('product');
        $specifyOptions = $this->getRequest()->getParam('options');

        if ($productId && ($this->_customerVisitor->getId() || $this->_customerSession->isLoggedIn())) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                $product = null;
            }

            if ($product) {
                $this->_catalogProductCompareList->addProduct($product);
                $productName = $this->_objectManager
                    ->get(\Magento\Framework\Escaper::Class)->escapeHtml($product->getName());
                $this->messageManager->addSuccess(__(
                    'You added product %1 to the comparison list.',
                    $productName
                ));
                if ($this->_compareHelper->isActive()) {
                    $comparePopup = $this->_compareHelper->getCompareContent();
                    $result = $this->resultJsonFactory->create()->setData([
                        'status' => 1,
                        'message' => "product is added",
                        'compare_popup' => $comparePopup
                    ]);
                    $this->_eventManager->dispatch('catalog_product_compare_add_product', ['product' => $product]);
                }
            }
            $this->_objectManager->get(\Magento\Catalog\Helper\Product\Compare::Class)->calculate();
        }
        if ($this->_compareHelper->isActive()) {
            return $result;
        } else {
            return $resultRedirect->setRefererOrBaseUrl();
        }
    }
}
