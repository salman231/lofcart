<?php

namespace MageArray\AjaxCompare\Controller\Product;

class Comparebox extends \Magento\Framework\App\Action\Action
{
    /**
     * Add item to compare list
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageArray\AjaxCompare\Helper\Product\Compare $compareHelper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_compareHelper = $compareHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    public function execute()
    {

        if ($this->_compareHelper->isActive()) {
            $comparePopup = $this->_compareHelper->getCompareContent();
            $result = $this->resultJsonFactory->create()->setData([
                'status' => 1,
                'message' => "get compare products",
                'compare_popup' => $comparePopup
            ]);
        }
        return $result;
    }
}
