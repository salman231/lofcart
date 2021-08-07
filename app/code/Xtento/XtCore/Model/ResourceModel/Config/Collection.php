<?php

/**
 * Product:       Xtento_XtCore
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2017-08-16T08:52:13+00:00
 * File:          app/code/Xtento/XtCore/Model/ResourceModel/Config/Collection.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\XtCore\Model\ResourceModel\Config;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Xtento\XtCore\Model\Config', 'Xtento\XtCore\Model\ResourceModel\Config');
    }
}
