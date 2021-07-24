<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class MaillogAction
 * @package Cowell\AbandonedCart\Ui\Component\Listing\Column
 */
class MaillogAction extends Column
{

    const MAIL_LOGS_URL_PATH_VIEW = 'emaillogs/logs/preview';

    const MAIL_LOGS_URL_PATH_DELETE = 'emaillogs/logs/delete';

    const MAIL_LOGS_URL_PATH_SENDTO = 'emaillogs/logs/resend';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * MaillogAction constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {

        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Data Source
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    if ($item['status'] == Status::MAIL_LOGS_SENT) {
                        $item[$fieldName]['preview'] = [
                            'target' => '_blank',
                            'href'   => $this->urlBuilder->getUrl(
                                self::MAIL_LOGS_URL_PATH_VIEW,
                                ['entity_id' => $item['entity_id']]
                            ),
                            'label'  => __('Preview Email')
                        ];
                    } else if ($item['status'] == Status::MAIL_LOGS_ERROR) {
                        $item[$fieldName]['resend'] = [
                            'href'  => $this->urlBuilder->getUrl(
                                self::MAIL_LOGS_URL_PATH_SENDTO,
                                ['entity_id' => $item['entity_id']]
                            ),
                            'label' => __('Resend')
                        ];
                    }
                    // Add Delete link
                    $item[$fieldName]['delete'] = [
                        'href'    => $this->urlBuilder->getUrl(
                            self::MAIL_LOGS_URL_PATH_DELETE,
                            ['entity_id' => $item['entity_id']]
                        ),
                        'label'   => __('Delete'),
                        'confirm' => [
                            'title'   => __('Delete item'),
                            'message' => __('Are you sure you want to delete this record?')
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
