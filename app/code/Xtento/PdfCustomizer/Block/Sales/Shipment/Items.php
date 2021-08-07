<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2019-08-30T13:51:37+00:00
 * File:          app/code/Xtento/PdfCustomizer/Block/Sales/Shipment/Items.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Block\Sales\Shipment;

use Xtento\PdfCustomizer\Helper\Data;
use Xtento\PdfCustomizer\Model\Source\TemplateType;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

class Items extends \Magento\Shipping\Block\Items
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    private $pdfTemplate;

    /**
     * Items constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $data);
        $this->helper = $helper;
    }

    /**
     * @param $source
     * @return bool
     */
    public function addPDFLink($source)
    {
        $helper = $this->helper;

        if ($helper->isEnabled(false, $this->_storeManager->getStore()->getId())
            && $helper->isEnabled('xtento_pdfcustomizer/shipment/frontend_enabled', $this->_storeManager->getStore()->getId())
        ) {
            $defaultTemplate = $helper->getDefaultTemplate(
                $source,
                TemplateType::TYPE_SHIPMENT
            );

            if ($defaultTemplate->getId()) {
                $this->pdfTemplate = $defaultTemplate;
                return true;
            }
        }

        return false;
    }

    /**
     * @param $source
     * @return string
     */
    public function getPrintPDFUrl($source)
    {
        return $this->getUrl('xtento_pdf/pdfPrint/sales', [
            'template_id' => $this->pdfTemplate->getId(),
            'order_id' => $source->getOrder()->getId(),
            'entity_id' => $source->getId()
        ]);
    }

    /**
     * Check whether built-in print action of Magento should be hidden
     */
    public function hideBuiltInPrintActions()
    {
        return $this->helper->isEnabled('xtento_pdfcustomizer/advanced/disable_default_print_actions');
    }
}
