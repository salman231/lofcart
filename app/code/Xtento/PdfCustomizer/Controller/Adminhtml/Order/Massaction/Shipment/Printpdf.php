<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Controller/Adminhtml/Order/Massaction/Shipment/Printpdf.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction\Shipment;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction\AbstractMassAction;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\App\ResponseInterface;
use Xtento\PdfCustomizer\Helper\GeneratePdf;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;

/**
 * Class Printpdf
 *
 * @package Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction\Shipment
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Printpdf extends AbstractMassAction
{
    /**
     * Printpdf constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param FileFactory $fileFactory
     * @param ForwardFactory $resultForwardFactory
     * @param GeneratePdf $generatePdfHelper
     * @param ShipmentCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        FileFactory $fileFactory,
        ForwardFactory $resultForwardFactory,
        GeneratePdf $generatePdfHelper,
        ShipmentCollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter, $fileFactory, $resultForwardFactory, $generatePdfHelper);
    }

    /**
     * @param AbstractCollection $collection
     * @return ResponseInterface
     */
    //@codingStandardsIgnoreLine
    public function massAction(AbstractCollection $collection)
    {
        $this->abstractCollection = $collection;
        return $this->generateFile();
    }
}
