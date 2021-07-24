<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_MultiStoreViewPricing
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
declare(strict_types=1);

namespace Bss\MultiStoreViewPricing\Block\Adminhtml\Product\Edit\Action\Attribute\Tab;

class Attributes extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Action\Attribute\Tab\Attributes
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /** @var array */
    private $excludeFields;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Helper\Product\Edit\Action\Attribute $attributeAction
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Helper\Product\Edit\Action\Attribute $attributeAction,
        \Magento\Framework\App\Request\Http $request,
        array $data = [],
        array $excludeFields = null
    ) {
        parent::__construct($context, $registry, $formFactory, $productFactory, $attributeAction, $data, $excludeFields);
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareForm(): void
    {
        parent::_prepareForm();

        $this->getForm()->getDataObject()->setStoreId(0);

        $storeId = $this->request->getParam('store');
        if ($storeId) {
            $this->getForm()->getDataObject()->setStoreId($storeId);
        }
    }

    /**
     * Returns excluded fields
     *
     * @return array
     */
    private function getExcludedFields(): array
    {
        return $this->excludeFields;
    }
}
