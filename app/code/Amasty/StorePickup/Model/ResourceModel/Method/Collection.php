<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_StorePickup
 */


namespace Amasty\StorePickup\Model\ResourceModel\Method;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Amasty\StorePickup\Model\Method::class,
            \Amasty\StorePickup\Model\ResourceModel\Method::class
        );
    }

    public function addStoreFilter($storeId)
    {
        $storeId = (int)$storeId;
        $this->getSelect()->where('stores="" OR stores LIKE "%,' . $storeId . ',%"');

        return $this;
    }

    public function addCustomerGroupFilter($groupId)
    {
        $groupId = (int)$groupId;
        $this->getSelect()->where('cust_groups="" OR cust_groups LIKE "%,' . $groupId . ',%"');

        return $this;
    }

    public function hashMinRate()
    {
        return $this->_toOptionHash('id', 'min_rate');
    }

    public function hashMaxRate()
    {
        return $this->_toOptionHash('id', 'max_rate');
    }

    public function joinLabels($modelId)
    {
        $this->getSelect()->joinLeft(
            ['label' => $this->getTable('amasty_storepick_method_label')],
            'main_table.id = label.method_id'
        )->where(
            'main_table.id=?',
            $modelId
        );

        return $this;
    }
}
