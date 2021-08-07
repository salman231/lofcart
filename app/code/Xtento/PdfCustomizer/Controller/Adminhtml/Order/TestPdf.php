<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            62/NQWL5Lum38rXboT76lWjXOaztcOud5OvQih0FVjI=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Controller/Adminhtml/Order/TestPdf.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Controller\Adminhtml\Order;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Xtento\PdfCustomizer\Api\TemplatesRepositoryInterface;
use Xtento\PdfCustomizer\Controller\Adminhtml\Templates;
use Xtento\PdfCustomizer\Helper\GeneratePdf;
use Xtento\PdfCustomizer\Model\Source\TemplateType;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;

/**
 * Class TestPdf
 * @package Xtento\PdfCustomizer\Controller\Adminhtml\Order
 */
class TestPdf extends AbstractPdf
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Xtento_PdfCustomizer::templates';

    /**
     * @var SearchCriteriaBuilder
     */
    public $criteriaBuilder;

    /**
     * @var FilterBuilder
     */
    public $filterBuilder;

    /**
     * @var TemplatesRepositoryInterface
     */
    protected $templatesRepositoryInterface;

    /**
     * TestPdf constructor.
     *
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param GeneratePdf $generatePdfHelper
     * @param TemplatesRepositoryInterface $templatesRepositoryInterface
     * @param SearchCriteriaBuilder $_criteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        GeneratePdf $generatePdfHelper,
        TemplatesRepositoryInterface $templatesRepositoryInterface,
        SearchCriteriaBuilder $_criteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->templatesRepositoryInterface = $templatesRepositoryInterface;
        $this->criteriaBuilder = $_criteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        parent::__construct($context, $fileFactory, $generatePdfHelper);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $templateId = $this->getRequest()->getParam('template_id');
        $entity = $this->getRequest()->getParam('template_type');
        $templateHtml = $this->getRequest()->getParam('template_html');
        $templateCss = $this->getRequest()->getParam('template_css');

        $this->templateId = $templateId;
        $this->templateModel();
        $this->templateModel->setTemplateHtml($templateHtml);
        $this->templateModel->setTemplateCss($templateCss);
        $this->templateModel->setData(
            array_merge(
                $this->templateModel()->getData(),
                [
                    'template_paper_ori' => $this->getRequest()->getParam('template_paper_ori'),
                    'template_custom_t' => $this->getRequest()->getParam('template_custom_t'),
                    'template_custom_b' => $this->getRequest()->getParam('template_custom_b'),
                    'template_custom_l' => $this->getRequest()->getParam('template_custom_l'),
                    'template_custom_r' => $this->getRequest()->getParam('template_custom_r')
                ]
            )
        );
        $this->templateModel->setIsTestGeneration(true);

        $entityModel = false;
        switch ($entity) {
            case TemplateType::TYPE_ORDER:
                $entityModel = OrderRepositoryInterface::class;
                break;
            case TemplateType::TYPE_INVOICE:
                $entityModel = InvoiceRepositoryInterface::class;
                break;
            case TemplateType::TYPE_SHIPMENT:
                $entityModel = ShipmentRepositoryInterface::class;
                break;
            case TemplateType::TYPE_CREDIT_MEMO:
                $entityModel = CreditmemoRepositoryInterface::class;
                break;
        }
        if ($templateId <= 0 || $entityModel === false) {
            /** @var \Magento\Framework\Controller\Result\Raw $resultPage */
            $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
            $resultPage->setContents(__("Please save the template once before trying to use the preview functionality for the first time."));
            return $resultPage;
        }

        $item = $this->collection($entityModel);
        if ($item === false) {
            /** @var \Magento\Framework\Controller\Result\Raw $resultPage */
            $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
            $resultPage->setContents(__("%1 ID does not exist. Please set a test ID in 'PDF Template' tab.", ucwords(TemplateType::TYPES[$entity])));
            return $resultPage;
        }

        return $this->returnFileInline($entityModel, $item->getId(), $this->templateModel);
    }

    /**
     * @param $templateTypeName
     *
     * @return mixed
     */
    public function collection($entityModel)
    {
        $this->criteriaBuilder->addFilters(
            [
                $this->filterBuilder
                    ->setField('increment_id')
                    ->setValue($this->getRequest()->getParam('entity_id'))
                    ->setConditionType('eq')
                    ->create()
            ]
        );
        $searchCriteria = $this->criteriaBuilder->create();
        //@codingStandardsIgnoreLine
        $collection = $this->_objectManager->create($entityModel)->getList($searchCriteria);

        if (!$collection->count()) {
            return false;
        }

        return $collection->getLastItem();
    }

    /**
     * @return mixed|\Xtento\PdfCustomizer\Model\PdfTemplate
     */
    public function templateModel()
    {
        $templateId = $this->templateId;

        if (!$this->templateId) {
            //$this->noRoute();
        }

        $templateModel = $this->templatesRepositoryInterface->getById($templateId);

        $this->templateModel = $templateModel;
        return $this->templateModel;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    //@codingStandardsIgnoreLine
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(Templates::ADMIN_RESOURCE_VIEW);
    }
}
