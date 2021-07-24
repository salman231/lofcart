<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Status
 * @package Cowell\AbandonedCart\Ui\Component\Listing\Column
 */
class Status extends Column
{
    const MAIL_LOGS_SENT = '<span class="grid-severity-notice" style="width: 107px">Sent</span>';

    const MAIL_LOGS_ERROR = '<span class="grid-severity-critical" style="width: 107px">Error</span>';

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$items) {
                $items['status'] = $items['status'] ? self::MAIL_LOGS_SENT : self::MAIL_LOGS_ERROR;
            }
        }
        return $dataSource;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return
            [
                [
                    'value' => 1,
                    'label' => __('Sent')
                ],
                [
                    'value' => 0,
                    'label' => __('Error')
                ]
            ];
    }
}
