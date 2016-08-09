<?php
class Chronostore_Priceoffer_Adminhtml_PriceofferbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Price Offer"));
	   $this->renderLayout();
    }
}