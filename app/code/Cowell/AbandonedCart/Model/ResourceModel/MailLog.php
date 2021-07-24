<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Model\ResourceModel;

use Cowell\AbandonedCart\Model\Email;

/**
 * Class Affix
 * @package Aeonibs\Catalog\Model\ResourceModel
 */
class MailLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const IS_IN_OF_STOCK = 1;
    const IS_ACTIVE = 1;
    const TYPE_BUNDLE = 'bundle';
    const TYPE_CONFIGURABLE = 'configurable';
    const STATUS_TRUE = 1;
    const STATUS_FALSE = 0;
    const STATUS_CHECK = '2';
    /**
     * @var \Cowell\AbandonedCart\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Store\Model\StoreRepository
     */
    protected $_storeRepository;

    /**
     * \TimezoneInterface $date,
     */
    protected $date;

    /**
     * @var array
     */
    protected $quoteFields = [
            'entity_id', 'customer_email', 'store_id', 'updated_at',
            'items_qty', 'subtotal', 'customer_firstname', 'customer_lastname'
        ];
    private $eavStatusId = NULL;

    /**
     * MailLog constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Store\Model\StoreRepository $storeRepository
     * @param \Cowell\AbandonedCart\Helper\Data $dataHelper
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreRepository $storeRepository,
        \Cowell\AbandonedCart\Helper\Data $dataHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
    )
    {
        $this->_storeRepository = $storeRepository;
        $this->dataHelper       = $dataHelper;
        $this->date             = $date;
        parent::__construct($context);
    }

    /**
     * construct
     */
    protected function _construct()
    {
        $this->_init('quote_alert_email_logs', 'entity_id');
    }

    /**
     * Get quote out stock current
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuoteByOutStock()
    {
        $stores    = $this->_storeRepository->getList();
        $storeList = [];
        foreach ($stores as $store) {
            $stockNotify                   = $this->dataHelper->getStockNotify($store["store_id"]);
            $cartTotalQuantity             = $this->dataHelper->getCartTotalQuantity($store["store_id"]);
            $cartTotalAmount               = $this->dataHelper->getCartTotalAmount($store["store_id"]);
            $checkMailEnable               = $this->dataHelper->getEmailOutStockEnable($store["store_id"]);
            $storeList[$store["store_id"]] = [
                'stock_notify'              => $stockNotify,
                'cart_quantity'             => $cartTotalQuantity,
                'cart_amount'               => $cartTotalAmount,
                'check_send_mail_out_stock' => $checkMailEnable
            ];
        }

        $arrData  = $this->getQuoteOutStockByQuery();
        $arrItems = [];

        if (count($arrData) > 0) {
            // check qty and unset item not validate
            foreach ($arrData as $key => $item) {
                if (isset($storeList[$item['store_id']])) {
                    $checkMinQty    = $item['qty'] > ($item['quote_qty'] + $storeList[$item['store_id']]['stock_notify']);
                    $checkItemCount = $item['items_qty'] < $storeList[$item['store_id']]['cart_quantity'];
                    $checkTotal     = $item['subtotal'] < $storeList[$item['store_id']]['cart_amount'];
                    $checkSendmail  = $storeList[$item['store_id']]['check_send_mail_out_stock'];
                    $checkStatus    = ($item['value'] == self::STATUS_CHECK) ? self::STATUS_TRUE : self::STATUS_FALSE;
                    if ($checkMinQty || $checkItemCount || $checkTotal || !$checkSendmail || $checkStatus) {
                        unset($arrData[$key]);
                    }
                }
            }
            $arrItems = $this->arrangementArray($arrData);
            $arrItems = $this->removeItemInArray($arrItems);
        }

        return $arrItems;
    }

    /**
     * Get quote time exist current
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuoteByTimeExist()
    {
        $stores    = $this->_storeRepository->getList();
        $storeList = [];
        foreach ($stores as $store) {
            $timeExist                     = $this->dataHelper->getTimeComeBack($store["store_id"]) * 3600;
            $cartTotalQuantity             = $this->dataHelper->getCartTotalQuantity($store["store_id"]);
            $cartTotalAmount               = $this->dataHelper->getCartTotalAmount($store["store_id"]);
            $checkMailEnable               = $this->dataHelper->getEmailComeBackEnable($store["store_id"]);
            $storeList[$store["store_id"]] = [
                'time_exist'                => $timeExist,
                'cart_quantity'             => $cartTotalQuantity,
                'cart_amount'               => $cartTotalAmount,
                'check_send_mail_out_stock' => $checkMailEnable
            ];
        }
        $currentTimeFormat = $this->date->date()->format('Y-m-d H:i:s');
        $arrData  = $this->getQuoteByTimeExistByQuery();
        $arrItems = [];
        if (count($arrData) > 0) {
            foreach ($arrData as $key => $item) {
                if (isset($storeList[$item['store_id']])) {
                    $timeUpdate     = $this->date->date($item['updated_at'])->format('Y-m-d H:i:s');
                    $checkTime      = (strtotime($currentTimeFormat) - strtotime($timeUpdate) < $storeList[$item['store_id']]['time_exist']);
                    $checkItemCount = $item['items_qty'] < $storeList[$item['store_id']]['cart_quantity'];
                    $checkTotal     = $item['subtotal'] < $storeList[$item['store_id']]['cart_amount'];
                    $checkSendmail  = $storeList[$item['store_id']]['check_send_mail_out_stock'];
                    $checkStatus    = (int)($item['value'] == '2');
                    if ($checkTime || $checkItemCount || $checkTotal || !$checkSendmail || $checkStatus) {
                        unset($arrData[$key]);
                    }
                }
            }
            $arrItems = $this->arrangementArray($arrData);
            $arrItems = $this->removeItemInArray($arrItems);
        }

        return $arrItems;
    }

    /**
     * get Quote By Out Stock Query
     * @return array
     */
    protected function getQuoteOutStockByQuery() {
        $attribute_id = $this->getEavStatusId();
        $connection   = $this->getConnection();

        $select = $connection->select()
            ->from(['main_table' => $connection->getTableName('quote')], $this->quoteFields)
            ->joinLeft(
                [
                    'quote_alert_status' =>
                        $connection->getTableName('quote_alert_status')
                ],
                'main_table.entity_id = quote_alert_status.quote_id and quote_alert_status.type = ' . Email::TYPE_LOW_STOCK_EMAIL,
                ['type']
            )
            ->joinLeft(
                ['quote_item' => $connection->getTableName('quote_item')],
                'main_table.entity_id = quote_item.quote_id',
                ['product_id', 'qty as quote_qty', 'item_id', 'parent_item_id', 'product_type']
            )->joinLeft(
                [
                    'cataloginventory_stock_item' =>
                        $connection->getTableName('cataloginventory_stock_item')
                ],
                'quote_item.product_id = cataloginventory_stock_item.product_id',
                ['qty']
            )->joinLeft(
                [
                    'catalog_product_entity_int' =>
                        $connection->getTableName('catalog_product_entity_int')
                ],
                'quote_item.product_id = catalog_product_entity_int.entity_id  
                AND catalog_product_entity_int.attribute_id = "' . $attribute_id . '"',
                ['value']
            )->joinLeft(
                ['quote_item_option' => $connection->getTableName('quote_item_option')],
                'quote_item.item_id = quote_item_option.item_id  AND quote_item_option.value = "grouped"',
                ['product_id as product_id_parent']
            )
            ->where('is_active = ?', self::IS_ACTIVE)
            ->where('quote_alert_status.type IS NULL')
            ->where('customer_email IS NOT NULL')
            ->where('cataloginventory_stock_item.is_in_stock = ?', self::IS_IN_OF_STOCK);

        return $connection->fetchAll($select);
    }

    /**
     * Get all Quote by Time Exist Query
     * @return array
     */
    protected function getQuoteByTimeExistByQuery() {
        $attribute_id      = $this->getEavStatusId();
        $connection        = $this->getConnection();
        $select            = $connection->select()
            ->from(['main_table' => $connection->getTableName('quote')], $this->quoteFields)
            ->joinLeft(
                [
                    'quote_alert_status' =>
                        $connection->getTableName('quote_alert_status')
                ],
                'main_table.entity_id = quote_alert_status.quote_id and quote_alert_status.type = ' . Email::TYPE_COME_BACK_EMAIL,
                ['type']
            )
            ->joinLeft(
                ['quote_item' => $connection->getTableName('quote_item')],
                'main_table.entity_id = quote_item.quote_id',
                ['product_id', 'qty as quote_qty', 'item_id', 'parent_item_id', 'product_type']
            )->joinLeft(
                [
                    'catalog_product_entity_int' =>
                        $connection->getTableName('catalog_product_entity_int')
                ],
                'quote_item.product_id = catalog_product_entity_int.entity_id  
                AND catalog_product_entity_int.attribute_id = "' . $attribute_id . '"',
                ['value']
            )->joinLeft(
                ['quote_item_option' => $connection->getTableName('quote_item_option')],
                'quote_item.item_id = quote_item_option.item_id  AND quote_item_option.value = "grouped"',
                ['product_id as product_id_parent']
            )
            ->where('is_active = ?', self::IS_ACTIVE)
            ->where('quote_alert_status.type IS NULL')
            ->where("customer_email is not NULL");

        return $connection->fetchAll($select);
    }

    /**
     * Get attribute id of status
     * @return string
     */
    protected function getEavStatusId()
    {
        if (is_null($this->eavStatusId) || $this->eavStatusId == '') {
            $connection = $this->getConnection();

            // get attribute id by status
            $selectAttribute   = $connection->select()
                ->from(['eav_attribute' => $connection->getTableName('eav_attribute')], ['attribute_id'])
                ->where('attribute_code = ?', "status");
            $attribute_id      = $connection->fetchAll($selectAttribute);
            $this->eavStatusId = isset($attribute_id[0]['attribute_id']) ? $attribute_id[0]['attribute_id'] : '';
        }
        return $this->eavStatusId;
    }


    /**
     * Array arrangement by parent item and children item
     *
     * @param $arrData
     * @return mixed
     */
    protected function arrangementArray($arrData)
    {
        $arrItems = [];

        // consolidate products with quote
        $arrayChildrenItem = [];
        foreach ($arrData as $key => $value) {
            if ($value['parent_item_id']) {
                $arrayChildrenItem[$value['parent_item_id']][] = $value['item_id'];
            }
            if (!isset($arrItems[$value['entity_id']])) {
                $arrItems[$value['entity_id']] = $value;
            }
            $arrItems[$value['entity_id']]['product_ids'][$value['item_id']] = [
                'product_id'         => $value['product_id'],
                'qty'                => $value['quote_qty'],
                'store_id'           => $value['store_id'],
                'parent_item_id'     => $value['parent_item_id'],
                'product_type'       => $value['product_type'],
                'item_id'            => $value['item_id'],
                'product_id_parent'  => $value['product_id_parent'],
                'customer_firstname' => $value['customer_firstname'],
                'customer_lastname'  => $value['customer_lastname']
            ];
            unset($arrItems[$value['entity_id']]['product_id']);
        }

        //format arrayItems to children_item
        if (!empty($arrayChildrenItem)) {
            foreach ($arrItems as $keyOut => $valueOut) {
                $parentQty = NULL;
                if (count($valueOut['product_ids']) > 0) {
                    foreach ($valueOut['product_ids'] as $keyIn => $valueIn) {
                        if (isset($arrayChildrenItem[$valueIn['item_id']]) &&
                            ($valueIn['product_type'] == self::TYPE_BUNDLE || $valueIn['product_type'] == self::TYPE_CONFIGURABLE)) {
                            $arrItems[$keyOut]['product_ids'][$keyIn]['children_item'] = $arrayChildrenItem[$valueIn['item_id']];
                        }
                        if ($valueIn['product_type'] == 'configurable') {
                            $parentQty = $valueIn['qty'];
                        }
                        if (isset($arrItems[$keyOut]['product_ids'][$keyIn]['children_item'])) {
                            foreach ($arrItems[$keyOut]['product_ids'][$keyIn]['children_item'] as $index => $item) {
                                if (!is_null($parentQty)) {
                                    $valueOut['product_ids'][$item]['qty'] = $parentQty;
                                }
                                $arrItems[$keyOut]['product_ids'][$keyIn]['children_item'][$item] = $valueOut['product_ids'][$item];
                                unset($arrItems[$keyOut]['product_ids'][$keyIn]['children_item'][$index]);
                                unset($arrItems[$keyOut]['product_ids'][$item]);
                            }
                        }
                    }
                }
            }
        }

        return $arrItems;
    }

    /**
     * remove Item In Array: parent not children and item not product_id
     *
     * @param $arrItems
     * @return mixed
     */
    protected function removeItemInArray($arrItems)
    {
        if(count($arrItems) > 0){
            // remove parent item config not include children item
            foreach ($arrItems as $keyOut => $valueOut) {
                if (count($valueOut['product_ids']) > 0) {
                    foreach ($valueOut['product_ids'] as $keyIn => $valueIn) {
                        if ($valueIn['product_type'] == 'configurable' && !isset($valueIn['children_item'])){
                            if(count($valueOut['product_ids']) == 1){
                                unset($arrItems[$keyOut]);
                            } else{
                                unset($arrItems[$keyOut]['product_ids'][$keyIn]);
                            }

                        }
                    }
                }
            }

            // remove item not product_ids
            foreach ($arrItems as $keyOut => $valueOut) {
                if (count($valueOut['product_ids']) == 0) {
                    unset($arrItems[$keyOut]);
                }
            }
        }

        return $arrItems;
    }
}
