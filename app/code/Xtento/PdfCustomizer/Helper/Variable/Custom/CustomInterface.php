<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2019-02-19T17:03:40+00:00
 * File:          app/code/Xtento/PdfCustomizer/Helper/Variable/Custom/CustomInterface.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */


namespace Xtento\PdfCustomizer\Helper\Variable\Custom;

interface CustomInterface
{
    /**
     * @return object
     */
    public function processAndReadVariables();

    /**
     * @param $source
     * @return object
     */
    public function entity($source);
}
