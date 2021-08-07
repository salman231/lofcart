<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Model/Source/TemplateDefault.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Model\Source;

class TemplateDefault extends AbstractSource
{
    /**
     * Statuses
     */
    const STATUS_YES = 1;
    const STATUS_NO = 0;

    /**
     * @return array
     */
    public function getAvailable()
    {
        return [self::STATUS_YES => __('Yes'), self::STATUS_NO => __('No')];
    }
}
