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
 * Class Email
 * @package Cowell\AbandonedCart\Model
 */
class Email
{
    const TYPE_COME_BACK_EMAIL = 1;
    const TYPE_LOW_STOCK_EMAIL = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_ERROR = 0;
    /**
     * Stock block
     *
     * @var
     */
    protected $comeBackBlock;

    /**
     * Stock block
     *
     * @var
     */
    protected $outStockBlock;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * Type
     *
     * @var string
     */
    protected $type = self::TYPE_COME_BACK_EMAIL;

    /**
     * Data Items
     *
     * @var array
     */
    protected $dataItems;

    /**
     * @var \Cowell\AbandonedCart\Logger\Logger
     */
    protected $logger;

    /**
     * @var ResourceModel\MailLog\CollectionFactory
     */
    protected $mailLogFactory;
    /**
     * \TimezoneInterface $date,
     */
    protected $date;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directory;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    protected $eventManager;

    /**
     * @var \Cowell\AbandonedCart\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var ResourceModel\QuoteAlertStatus\CollectionFactory
     */
    protected $quoteAlertStatusFactory;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    protected $appEmulation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\ProductAlert\Helper\Data
     */
    protected $productAlertData;

    /**
     * Email constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\ProductAlert\Helper\Data $productAlertData
     * @param \Magento\Framework\Event\Manager $eventManager
     * @param \Cowell\AbandonedCart\Helper\Data $dataHelper
     * @param \Cowell\AbandonedCart\Logger\Logger $logger
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param ResourceModel\QuoteAlertStatus\CollectionFactory $quoteAlertStatusFactory
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\ProductAlert\Helper\Data $productAlertData,
        \Magento\Framework\Event\Manager $eventManager,
        \Cowell\AbandonedCart\Helper\Data $dataHelper,
        \Cowell\AbandonedCart\Logger\Logger $logger,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Cowell\AbandonedCart\Model\ResourceModel\QuoteAlertStatus\CollectionFactory $quoteAlertStatusFactory,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    )
    {

        $this->scopeConfig             = $scopeConfig;
        $this->appState                = $appState;
        $this->storeManager            = $storeManager;
        $this->transportBuilder        = $transportBuilder;
        $this->productAlertData        = $productAlertData;
        $this->eventManager            = $eventManager;
        $this->dataHelper              = $dataHelper;
        $this->timezone                = $timezone;
        $this->quoteAlertStatusFactory = $quoteAlertStatusFactory;
        $this->logger                  = $logger;
        $this->appEmulation            = $appEmulation;
        $this->date                    = $date;
        $this->directory               = $directoryList;

    }

    /**
     * Set model type
     *
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Retrieve model type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Data Items
     * @param array $dataItems
     */
    public function setDataItems($dataItems = [])
    {
        $this->dataItems = $dataItems;
    }

    /**
     * Retrieve Data Items
     *
     * @return string
     */
    public function getDataItems()
    {
        return $this->dataItems;
    }

    /**
     * Retrieve price block
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getComeBackBlock()
    {
        if ($this->comeBackBlock === NULL) {
            $this->comeBackBlock = $this->dataHelper->createBlock(\Cowell\AbandonedCart\Block\Email\ComeBack::class);
        }
        return $this->comeBackBlock;
    }

    /**
     * Retrieve stock block
     * @return \Magento\Framework\View\Element\AbstractBlock
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getOutOfStockBlock()
    {
        if (is_null($this->outStockBlock)) {
            $this->outStockBlock = $this->dataHelper->createBlock(\Cowell\AbandonedCart\Block\Email\OutOfStock::class);
        }
        return $this->outStockBlock;
    }

    /**
     * Get prepare data for email sending
     * @param $data
     * @param $type
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getEmailData($data, $type) {
        switch ($type) {
            case self::TYPE_LOW_STOCK_EMAIL :
                $this->_getOutOfStockBlock()->setProduct($data['product_ids']);
                return [
                    'block' => $this->_getOutOfStockBlock(),
                    'tplId' => $this->dataHelper->getOutStockTemplateMail($data['store_id']),
                    'from'  =>  $this->dataHelper->getComeBackIdentity($data['store_id'])
                ];
                break;
            default :
                $this->_getComeBackBlock()->setProduct($data['product_ids']);
                return [
                    'block' => $this->_getComeBackBlock(),
                    'tplId' => $this->dataHelper->getComeBackTemplateMail($data['store_id']),
                    'from'  =>  $this->dataHelper->getOutStockIdentity($data['store_id'])
                ];
        }
    }

    /**
     * Send mail by type comeback and time exist
     * @return bool
     */
    public function send()
    {
        if (!empty($this->dataItems)) {
            foreach ($this->dataItems as $item) {
                try {
                    $this->appEmulation->startEnvironmentEmulation(
                        $item['store_id'],
                        \Magento\Framework\App\Area::AREA_FRONTEND,
                        true
                    );
                    $emailData = $this->getEmailData($item, $this->type);
                    $alertGrid = $this->appState->emulateAreaCode(
                        \Magento\Framework\App\Area::AREA_FRONTEND,
                        [$emailData['block'], 'toHtml']
                    );
                    $this->appEmulation->stopEnvironmentEmulation();

                    $transport = $this->transportBuilder->setTemplateIdentifier(
                        $emailData['tplId']
                    )->setTemplateOptions(
                        [
                            'area'  => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => $item['store_id'],
                        ]
                    )->setTemplateVars(
                        [
                            'quote_time'         => $this->date->formatDateTime($this->date->date($item['updated_at']), \IntlDateFormatter::LONG),
                            'checkout_cart_url'  => $this->storeManager->getStore($item['store_id'])
                                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB)
                                . 'checkout/cart?___store=' . $item['store_id'],
                            'formattedCart'      => $alertGrid,
                            'customer_firstname' => $item['customer_firstname'] ?? '',
                            'customer_lastname'  => $item['customer_lastname'] ?? ''
                        ]
                    )->setFrom(
                        $emailData['from']
                    )->addTo(
                        $item['customer_email']
                    )->getTransport();
                    $transport->sendMessage();
                    $data['quote_id'] = $item['entity_id'];
                    $data['status']   = self::STATUS_SUCCESS;
                } catch (\Exception $e) {
                    $data['status'] = self::STATUS_ERROR;
                    $this->logger->info($item['entity_id'] . ':' . $e->getMessage());
                } finally {
                    $data['email_content'] = $transport->getMessage()->getBody()->generateMessage();
                    $data['quote_id']      = $item['entity_id'];
                    $data['email_to']      = $item['customer_email'];
                    $data['type']          = $this->type;
                    $data['email_subject'] = $transport->getMessage()->getSubject();
                    $data['sender']        = $emailData['from'];
                    $data['store_id']      = $item['store_id'];
                    $this->eventManager->dispatch('save_quote_alert_email_logs', ['emailData' => $data]);
                }
            }
        }
        return true;
    }
}
