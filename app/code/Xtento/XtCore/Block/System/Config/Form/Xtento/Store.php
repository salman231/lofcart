<?php

/**
 * Product:       Xtento_XtCore
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2020-05-29T13:06:01+00:00
 * File:          app/code/Xtento/XtCore/Block/System/Config/Form/Xtento/Store.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\XtCore\Block\System\Config\Form\Xtento;

class Store extends \Magento\Config\Block\System\Config\Form\Field
{
    /*
     * XTENTO Store
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $extStoreUrl = 'https://www.xtento.com/magento-2-extensions.html?extensionstore=true';
        $html = <<<EOT
<script>
requirejs(['prototype'], function() {
    if (typeof $$(".save")[0] !== 'undefined') {
        $$(".save")[0].down('span').innerHTML = 'Open the XTENTO Extension Store in a new window';
        $$(".save")[0].setAttribute('onclick', "window.open('{$extStoreUrl}', '_blank'); return false;");
    }
});
</script>
<iframe src="{$extStoreUrl}" scrolling="auto"
style="width: 100%; height: 900px !important; display: block; border: 1px solid #ccc;"></iframe>
EOT;
        return $html;
    }
}
