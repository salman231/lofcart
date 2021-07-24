<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Model\Walmart\Connector\Account\Add;

/**
 * Class \Ess\M2ePro\Model\Walmart\Connector\Account\Add\EntityResponser
 */
class EntityResponser extends \Ess\M2ePro\Model\Connector\Command\Pending\Responser
{
    //########################################

    protected function validateResponse()
    {
        $responseData = $this->getResponse()->getResponseData();
        if (empty($responseData['hash']) || !isset($responseData['info'])) {
            return false;
        }

        return true;
    }

    protected function processResponseData()
    {
        $responseData = $this->getPreparedResponseData();

        /** @var $walmartAccount \Ess\M2ePro\Model\Walmart\Account */
        $walmartAccount = $this->walmartFactory
            ->getObjectLoaded('Account', $this->params['account_id'])
            ->getChildObject();

        $dataForUpdate = [
            'server_hash' => $responseData['hash'],
            'info'        => $this->getHelper('Data')->jsonEncode($responseData['info'])
        ];

        $walmartAccount->addData($dataForUpdate)->save();
    }

    //########################################
}
