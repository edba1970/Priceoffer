<?php
class Chronostore_Priceoffer_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Price Offer"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("price_offer", array(
                "label" => $this->__("Price Offer"),
                "title" => $this->__("Price Offer")
		   ));

      $this->renderLayout(); 
	  
    }
}