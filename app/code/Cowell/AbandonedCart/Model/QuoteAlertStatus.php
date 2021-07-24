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
class QuoteAlertStatus extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'CWA_QUOTE_ALERT_STATUS';

    /**
     * construct
     */
    protected function _construct()
    {
        $this->_init('Cowell\AbandonedCart\Model\ResourceModel\QuoteAlertStatus');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
