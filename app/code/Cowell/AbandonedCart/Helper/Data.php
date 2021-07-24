<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 *
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Cowell\AbandonedCart\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CART_TOTAL_AMOUNT = 'quote_alert_section/equote/cart_total';
    const CART_TOTAL_QUANTITY = 'quote_alert_section/equote/cart_stock';

    const EMAIL_COME_BACK_ENABLE = 'quote_alert_section/quote_email_notify_come_back/e_quote_come_back';
    const TIME_COME_BACK = 'quote_alert_section/quote_email_notify_come_back/time_come_back';
    const EMAIL_COME_BACK_TEMPLATE = 'quote_alert_section/quote_email_notify_come_back/email_come_back_template';
    const EMAIL_COME_BACK_IDENTITY = 'quote_alert_section/quote_email_notify_come_back/email_identity';

    const EMAIL_OUT_STOCK_ENABLE = 'quote_alert_section/quote_email_out_stock/e_quote_out_stock';
    const STOCK_NOTIFY = 'quote_alert_section/quote_email_out_stock/stock_notify';
    const EMAIL_OUT_STOCK_TEMPLATE = 'quote_alert_section/quote_email_out_stock/email_stock_template';
    const EMAIL_OUT_STOCK_IDENTITY = 'quote_alert_section/quote_email_out_stock/email_identity';

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\LayoutInterface $layout
    )
    {
        $this->layout = $layout;
        parent::__construct($context);
    }

    /**
     * General config
     *
     * @param $storeId
     * @return mixed
     */
    public function getCartTotalAmount($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::CART_TOTAL_AMOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Get Cart Total Quantity
     *
     * @param $storeId
     * @return mixed
     */
    public function getCartTotalQuantity($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::CART_TOTAL_QUANTITY,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Email come back config
     *
     * @param $storeId
     * @return mixed
     */
    public function getEmailComeBackEnable($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_COME_BACK_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Time ComeBack
     *
     * @param $storeId
     * @return mixed
     */
    public function getTimeComeBack($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::TIME_COME_BACK,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Come Back Template Mail
     *
     * @param $storeId
     * @return mixed
     */
    public function getComeBackTemplateMail($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_COME_BACK_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Come Back Identity
     *
     * @param $storeId
     * @return mixed
     */
    public function getComeBackIdentity($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_COME_BACK_IDENTITY,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Get Email out of stock status from config
     * @param null $storeId
     * @return mixed
     */
    public function getEmailOutStockEnable($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_OUT_STOCK_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Stock Notify Config
     * @param null $storeId
     * @return mixed
     */
    public function getStockNotify($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::STOCK_NOTIFY,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Out Stock template Mail
     * @param $storeId
     * @return mixed
     */
    public function getOutStockTemplateMail($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_OUT_STOCK_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Out Stock Identity
     * @param null $storeId
     * @return mixed
     */
    public function getOutStockIdentity($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_OUT_STOCK_IDENTITY,
            ScopeInterface::SCOPE_STORE,
            $storeId);
    }

    /**
     * Create block instance
     *
     * @param string|\Magento\Framework\View\Element\AbstractBlock $block
     * @return \Magento\Framework\View\Element\AbstractBlock
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createBlock($block)
    {
        if (is_string($block)) {
            if (class_exists($block)) {
                $block = $this->layout->createBlock($block);
            }
        }
        if (!$block instanceof \Magento\Framework\View\Element\AbstractBlock) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid block type: %1', $block));
        }
        return $block;
    }
}
