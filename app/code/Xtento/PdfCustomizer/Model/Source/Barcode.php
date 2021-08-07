<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2019-02-19T17:03:40+00:00
 * File:          app/code/Xtento/PdfCustomizer/Model/Source/Barcode.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Model\Source;

use Xtento\PdfCustomizer\Helper\AbstractPdf;

class Barcode extends AbstractSource
{
    /**
     * @return array, options for the code bar system
     */
    public function getAvailable()
    {
        foreach (AbstractPdf::CODE_BAR as $code) {
            $options[$code] = $code;
        }

        return $options;
    }
}
