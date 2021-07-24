<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Block\Email;
/**
 * Class AbstractEmail
 * @package Cowell\AbandonedCart\Block\Email
 */
abstract class AbstractEmail extends \Magento\Framework\View\Element\Template
{
    /**
     * Product collection array
     *
     * @var array
     */
    protected $products = [];

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $imageBuilder;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;
    protected $priceRender = NULL;

    /**
     * AbstractEmail constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = []
    )
    {
        $this->imageBuilder      = $imageBuilder;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * Reset product collection
     *
     * @return void
     */
    public function reset()
    {
        $this->products = [];
    }

    /**
     * Set product to collection
     *
     * @param $productIdList
     */
    public function setProduct($productIdList)
    {
        $this->products = $productIdList;
    }

    /**
     * Retrieve product collection array
     *
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    /**
     * Return HTML block with tier price
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    )
    {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getPriceRender();
        $price       = '';

        if ($priceRender) {
            $price = $priceRender->render(
                $priceType,
                $product,
                $arguments
            );
        }
        return $price;
    }

    /**
     * Get price and render block price product
     * @return \Magento\Framework\View\Element\BlockInterface|null
     */
    protected function getPriceRender()
    {
        if (is_null($this->priceRender)) {
            $this->priceRender = $this->_layout->createBlock(
                \Magento\Framework\Pricing\Render::class,
                '',
                ['data' => ['price_render_handle' => 'catalog_product_prices']]
            );
        }

        return $this->priceRender;
    }

    /**
     * Format quantity by number format
     *
     * @param $qty
     * @return mixed
     */
    public function formatQty($qty)
    {
        $qty = number_format((int)$qty);
        return $qty;
    }

    /**
     * Get Product Repository  by id and store view
     *
     * @param $productId
     * @param $storeId
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductObject($productId, $storeId)
    {
        $product = $this->productRepository->getById(
            $productId,
            false,
            (int)$storeId
        );
        return $product;
    }
}
