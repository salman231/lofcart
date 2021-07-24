<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Model\ResourceModel;

/**
 * Class Affix
 * @package Aeonibs\Catalog\Model\ResourceModel
 */
class QuoteAlertStatus extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * construct
     */
    protected function _construct()
    {
        $this->_init('quote_alert_status', 'entity_id');
    }
}
