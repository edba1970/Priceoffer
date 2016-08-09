<?php

class Chronostore_Priceoffer_Block_Adminhtml_Priceoffer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("priceofferGrid");
				$this->setDefaultSort("offer_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("priceoffer/priceoffer")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("offer_id", array(
				"header" => Mage::helper("priceoffer")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "offer_id",
				));
                
				$this->addColumn("product_id", array(
				"header" => Mage::helper("priceoffer")->__("Product Id"),
				"index" => "product_id",
				));
				
				$this->addColumn("product_name", array(
				"header" => Mage::helper("priceoffer")->__("Product Name"),
				"index" => "product_name",
				));
				
				$this->addColumn("email", array(
				"header" => Mage::helper("priceoffer")->__("Email Address"),
				"index" => "email",
				));
				$this->addColumn("offer_price", array(
				"header" => Mage::helper("priceoffer")->__("Offer Price"),
				"index" => "offer_price",
				));
				$this->addColumn("our_offer_price", array(
				"header" => Mage::helper("priceoffer")->__("Counter Offer"),
				"index" => "our_offer_price",
				));	
				$this->addColumn("qty", array(
				"header" => Mage::helper("priceoffer")->__("Quantity"),
				"index" => "qty",
				));
				$this->addColumn("offer_submitted", array(
				"header" => Mage::helper("priceoffer")->__("Offer Emailed"),
				"index" => "offer_submitted",
				"type" => "options",
				"options"   => array(
					  0 => 'No',
					  1 => 'Yes',
				  ),
				));
				
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('offer_id');
			$this->getMassactionBlock()->setFormFieldName('offer_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_priceoffer', array(
					 'label'=> Mage::helper('priceoffer')->__('Remove Priceoffer'),
					 'url'  => $this->getUrl('*/adminhtml_priceoffer/massRemove'),
					 'confirm' => Mage::helper('priceoffer')->__('Are you sure?')
				));
			return $this;
		}
			

}