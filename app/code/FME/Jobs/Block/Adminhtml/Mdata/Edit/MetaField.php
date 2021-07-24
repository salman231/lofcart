<?php
/**
 * FME Extensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the fmeextensions.com license that is
 * available through the world-wide-web at this URL:
 * https://www.fmeextensions.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  FME
 * @package   FME_Jobs
 * @copyright Copyright (c) 2019 FME (http://fmeextensions.com/)
 * @license   https://fmeextensions.com/LICENSE.txt
 */

namespace FME\Jobs\Block\Adminhtml\Mdata\Edit;

class MetaField extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'FME_Jobs::/mdata/meta_field.phtml';

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;
    protected $_productFactory;
    protected $_eventFactory;
    public $_storeAdminHelper;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        //\FME\Events\Helper\Data $helper,
        \Magento\Backend\Block\Template\Context $context,
        \FME\Jobs\Model\Job $eventFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        
        $this->_eventFactory = $eventFactory;
        //$this->_coreRegistry = $coreRegistry;
        //$this->_storeAdminHelper = $helper;
        parent::__construct($context);
    }
    
    public function getMetaId()
    {
        $id = $this->getRequest()->getParam('set');
        // $mediaobj = $this->_eventFactory->getStores($id);
        return $id;
    }

    public function getDatCodeEditId()
    {
        $id = $this->getRequest()->getParam('data_code');
         if($id){
                $mediaobj = $this->_eventFactory->getTypesCode($id);
                $mediaobj = $mediaobj['0']['type_code'];         
            return $mediaobj;
          }
    }

    public function getMetaName()
    {
        $id = $this->getRequest()->getParam('type');
        // $mediaobj = $this->_eventFactory->getStores($id);
        return $id;
    }

    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    public function getMetaCollection()
    {
        return  $this->_eventFactory->getTypes();
    }
}
