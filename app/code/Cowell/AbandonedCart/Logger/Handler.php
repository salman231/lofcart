<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 *
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Logger;
use Monolog\Logger;

/**
 * Class Handler
 * @package Cowell\AbandonedCart\Logger
 */
class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/co-well/abandoned_cart_email.log';
}
