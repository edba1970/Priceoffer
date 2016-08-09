<?php


class Chronostore_Priceoffer_Block_Adminhtml_Priceoffer extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_priceoffer";
	$this->_blockGroup = "priceoffer";
	$this->_headerText = Mage::helper("priceoffer")->__("Priceoffer Manager");
	$this->_addButtonLabel = Mage::helper("priceoffer")->__("Add New Item");
	parent::__construct();
	
	}

}