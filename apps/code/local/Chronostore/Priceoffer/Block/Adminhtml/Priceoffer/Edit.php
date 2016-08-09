<?php
	
class Chronostore_Priceoffer_Block_Adminhtml_Priceoffer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "offer_id";
				$this->_blockGroup = "priceoffer";
				$this->_controller = "adminhtml_priceoffer";
				$this->_updateButton("save", "label", Mage::helper("priceoffer")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("priceoffer")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("priceoffer")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("priceoffer_data") && Mage::registry("priceoffer_data")->getId() ){

				    return Mage::helper("priceoffer")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("priceoffer_data")->getProductName()));

				} 
				else{

				     return Mage::helper("priceoffer")->__("Add Item");

				}
		}
}