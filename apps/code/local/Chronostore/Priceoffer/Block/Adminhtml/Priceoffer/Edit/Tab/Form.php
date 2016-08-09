<?php
class Chronostore_Priceoffer_Block_Adminhtml_Priceoffer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("priceoffer_form", array("legend"=>Mage::helper("priceoffer")->__("Item information")));

				
				$fieldset->addField("offer_id", "text", array(
				"label" => Mage::helper("priceoffer")->__("Offer Id"),
				"name" => "offer_id",
				));
			
				$data = array();
				if ( Mage::getSingleton('adminhtml/session')->getPriceofferData() ){
					$data = Mage::getSingleton('adminhtml/session')->getPriceofferData();
				} elseif ( Mage::registry('priceoffer_data') ) {
					$data = Mage::registry('priceoffer_data')->getData();
				}
				
				  
				$out='<a href="'.$this->getUrl("adminhtml/catalog_product/edit", array("id" => $data['product_id'])).'">'.$data['product_name'].'</a>';
				$fieldset->addField("product_id", "label", array(
				"label" => Mage::helper("priceoffer")->__("Product Id"),					
				"required" => true,
				"name" => "product_id",
				"note" => $out,
				));
				
				$fieldset->addField("email", "text", array(
				"label" => Mage::helper("priceoffer")->__("Email Address"),					
				"class" => "required-entry",
				"required" => true,
				"name" => "email",
				));
			
				$product=Mage::getModel("catalog/product")->load($data['product_id']);
				$_store=$product->getStore();
				$discounted_price = Mage::getResourceModel('catalogrule/rule')->getRulePrice( 
				Mage::app()->getLocale()->storeTimeStamp($_store->getStoreId()), 
				1, 
				0, $product->getId());

				$googlePrice=$product->getGooglePriceMatch();
				$price=$product->getPrice();
				
				if ($discounted_price) {
					if ($googlePrice && $googlePrice<$discounted_price) {
						$price = $googlePrice;
					} elseif ($discounted_price<$price) {
						$price = $discounted_price;
					} 
				}
				
				$price = $_store->roundPrice($_store->convertPrice($price));
				
				$fieldset->addType('original_price_cost', 'Chronostore_Priceoffer_Block_Adminhtml_Priceoffer_Edit_Tab_Field_Custom');
				$fieldset->addField('original_price_cost', 'original_price_cost', array(
					 'title' => "<b>Current Price:</b> $".number_format($price,2). " <b>Cost: </b>$".number_format($product->getPriceOurCost(),2),
					 'id' => 'original_price_cost',
					 'class' => 'original_price_cost',
					 'type' => 'label',
					 'value' => "Current Price: $price Cost: ".$product->getPriceOurCost(),
				));
				
				$fieldset->addField("offer_price", "text", array(
				"label" => Mage::helper("priceoffer")->__("Offer Price"),					
				"class" => "required-entry",
				"required" => true,
				"type" => "number",
				"name" => "offer_price",
				));

				$fieldset->addField("our_offer_price", "text", array(
				"label" => Mage::helper("priceoffer")->__("Counter Offer"),					
				"class" => "required-entry",
				"required" => true,
				"type" => "number",
				"name" => "our_offer_price",
				));
				
				$fieldset->addField("qty", "text", array(
				"label" => Mage::helper("priceoffer")->__("Quantity"),					
				"class" => "required-entry",
				"required" => true,
				"name" => "qty",
				));
						
				$fieldset->addField("promo", "text", array(
				"label" => Mage::helper("priceoffer")->__("Promo Code"),					
				"name" => "promo",
				"disabled" => true,
				));
				
				$fieldset->addField("phone", "text", array(
				"label" => Mage::helper("priceoffer")->__("Phone Number"),
				"name" => "phone",
				));
			
				$fieldset->addField("zip_code", "text", array(
				"label" => Mage::helper("priceoffer")->__("Zip Code"),
				"name" => "zip_code",
				));
			
				$fieldset->addField("comments", "textarea", array(
				"label" => Mage::helper("priceoffer")->__("Comments"),
				"name" => "comments",
				));
			
				$fieldset->addField("offer_submitted", "checkbox", array(
				"label" => Mage::helper("priceoffer")->__("Status"),					
				"name" => "offer_submitted",
				"disabled" => true,
				'note' => $data["offer_submitted"] ? "<span style='color: green; font-weight: bold'>This offer has been previously emailed to the customer</span>" : "<span style='color: red; font-weight: bold'>This offer has not been emailed to the customer yet.</span>"
				));
				
				$form->getElement('offer_submitted')->setIsChecked(!empty($data['offer_submitted']));
				
				$fieldset->addType('add_button', 'Chronostore_Priceoffer_Block_Adminhtml_Priceoffer_Edit_Tab_Field_CreateSale');
				$fieldset->addField('create_sale', 'add_button', array(
					 'title' => Mage::helper('priceoffer')->__('Create Sale & Send Offer'),
					 'id' => 'create_sale',
					 'class' => 'buttonadder_class',
					 'onclick' => 'return isEmpty(\''.$this->getUrl('*/*/createSale', array("id" => $this->getRequest()->getParam("id"))).'\'); ',
					 'type' => 'button',       
				));
				
				if (Mage::getSingleton("adminhtml/session")->getPriceofferData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getPriceofferData());
					Mage::getSingleton("adminhtml/session")->setPriceofferData(null);
				} 
				elseif(Mage::registry("priceoffer_data")) {
				    $form->setValues(Mage::registry("priceoffer_data")->getData());
				}
				return parent::_prepareForm();
		}
}
