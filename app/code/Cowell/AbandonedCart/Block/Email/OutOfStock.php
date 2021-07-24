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
 * Class OutOfStock
 * @package Cowell\AbandonedCart\Block\Email
 */
class OutOfStock extends AbstractEmail
{
    /**
     * Template Quote out of stock
     *
     * @var string
     */
    protected $_template = 'Cowell_AbandonedCart::email/quote_out_of_stock.phtml';
}
