<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Model\Amazon\Connector\Account\Delete;

/**
 * Class \Ess\M2ePro\Model\Amazon\Connector\Account\Delete\EntityResponser
 */
class EntityResponser extends \Ess\M2ePro\Model\Connector\Command\Pending\Responser
{
    //########################################

    protected function validateResponse()
    {
        return true;
    }

    protected function processResponseData()
    {
        return null;
    }

    //########################################
}
