<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Model;

/**
 * Class Affix
 * @package Aeonibs\Catalog\Model
 */
class MailLog extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'CWA_QUOTE_MAIL_LOG';
    const MALI_LOGS_LOW_STOCK = 0;
    const MALI_LOGS_ABANDONED_CART = 1;

    /**
     * Contruct
     */
    protected function _construct()
    {
        $this->_init('Cowell\AbandonedCart\Model\ResourceModel\MailLog');
    }

    /**
     * Get Available Type
     *
     * @return array
     */
    public function getAvailableType()
    {
        return [self::MALI_LOGS_LOW_STOCK => __('Low Stock'), self::MALI_LOGS_ABANDONED_CART => __('Abandoned Cart')];
    }

    /**
     * Get Identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
