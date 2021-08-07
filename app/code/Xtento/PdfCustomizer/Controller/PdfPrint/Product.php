<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Controller/PdfPrint/Product.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Controller\PdfPrint;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ResponseInterface;

/**
 * Class Product
 * @package Xtento\PdfCustomizer\Controller\PdfPrint
 */
class Product extends AbstractPdf
{
    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        $pdf = $this->returnFile(ProductRepositoryInterface::class, 'product_id');
        return $pdf;
    }
}