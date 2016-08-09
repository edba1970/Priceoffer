<?php
class Chronostore_Priceoffer_Block_Adminhtml_Priceoffer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("priceoffer_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("priceoffer")->__("Offer Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("priceoffer")->__("Offer Information"),
				"title" => Mage::helper("priceoffer")->__("Offer Information"),
				"content" => $this->getLayout()->createBlock("priceoffer/adminhtml_priceoffer_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
