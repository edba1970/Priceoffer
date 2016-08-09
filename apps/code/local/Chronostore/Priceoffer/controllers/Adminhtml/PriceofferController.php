<?php

class Chronostore_Priceoffer_Adminhtml_PriceofferController extends Mage_Adminhtml_Controller_Action
{	
		static protected $_save=false;
		
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("priceoffer/priceoffer")->_addBreadcrumb(Mage::helper("adminhtml")->__("Priceoffer  Manager"),Mage::helper("adminhtml")->__("Priceoffer Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Priceoffer"));
			    $this->_title($this->__("Manager Priceoffer"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Priceoffer"));
				$this->_title($this->__("Priceoffer"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("priceoffer/priceoffer")->load($id);
				if ($model->getId()) {
					Mage::register("priceoffer_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("priceoffer/priceoffer");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Priceoffer Manager"), Mage::helper("adminhtml")->__("Priceoffer Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Priceoffer Description"), Mage::helper("adminhtml")->__("Priceoffer Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("priceoffer/adminhtml_priceoffer_edit"))->_addLeft($this->getLayout()->createBlock("priceoffer/adminhtml_priceoffer_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("priceoffer")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Priceoffer"));
		$this->_title($this->__("Priceoffer"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("priceoffer/priceoffer")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("priceoffer_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("priceoffer/priceoffer");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Priceoffer Manager"), Mage::helper("adminhtml")->__("Priceoffer Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Priceoffer Description"), Mage::helper("adminhtml")->__("Priceoffer Description"));


		$this->_addContent($this->getLayout()->createBlock("priceoffer/adminhtml_priceoffer_edit"))->_addLeft($this->getLayout()->createBlock("priceoffer/adminhtml_priceoffer_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();
			
			//die($post_data['offer_price'].' '.$model->getOfferPrice());
				if ($post_data) {

					try {

						
						$model = Mage::getModel("priceoffer/priceoffer")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Priceoffer was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setPriceofferData(false);
						
						$this->createsaleAction($oldPrice);					

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setPriceofferData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("priceoffer/priceoffer");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		private function GeneratePromoCode($couponName) {
			$generator = Mage::getModel('salesrule/coupon_massgenerator');
			$data = array(
				'max_probability'   => .25,
				'max_attempts'      => 10,
				'uses_per_customer' => 1,
				'uses_per_coupon'   => 1,
				'qty'               => 1, //number of coupons to generate
				'length'            => 14, //length of coupon string
				'to_date'           => date('Y-m-d'), //ending date of generated promo
				/**
				 * Possible values include:
				 * Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC
				 * Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHABETICAL
				 * Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_NUMERIC
				 */
				'format'          => Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC,
				'rule_id'         => $couponName //the id of the rule you will use as a template
			);
			
			$generator->validateData($data);
			$generator->setData($data);
			$generator->generatePool();
			
			$salesRule = Mage::getModel('salesrule/rule')->load($couponName);
			$collection = Mage::getResourceModel('salesrule/coupon_collection')
				->addRuleToFilter($couponName)
				->addGeneratedCouponsFilter();
			
		}

		function generateCouponCode($length = 8) {
		  $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		  $ret = '';
		  for($i = 0; $i < $length; ++$i) {
			$random = str_shuffle($chars);
			$ret .= $random[0];
		  }
		  return $ret;
		}
		
		public function createsaleAction($oldPrice) {
			$date = new DateTime(now());
			$date->modify('+1 day');
			
			$id=$this->getRequest()->getParam('id');
						
			$model = Mage::getModel("priceoffer/priceoffer");
			$offer = $model->load($id);

			$product = Mage::getModel('catalog/product')->load($offer->getProductId());
			$query=Mage::helper('checkout/cart')->getAddUrl($product);
			
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

			$couponName=$this->generateCouponCode();//$offer->getPromo();
			$offer->setPromo($couponName);
			$offer->save();
			//$this->GeneratePromoCode ($couponName);
			$oCoupon = Mage::getModel('salesrule/coupon')->load($couponName, 'code');
			if ($couponName) {
			//$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
			//var_dump($oRule->getData());
				if (!$oCoupon->getRuleId()) {
					$coupon = Mage::getModel('salesrule/rule');
					$coupon->setName($couponName)
						->setDescription($offer->getProductName())
						->setFromDate(date('Y-m-d'))
						->setToDate($date->format('Y-m-d'))
						->setCouponType(2) //1 for autogenerate 2 is to set custom
						->setCouponCode($couponName)
						/*->setConditionsSerialized('a:6:{s:4:"type";s:32:"salesrule/rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}')
						->setActionsSerialized('a:6:{s:4:"type";s:40:"salesrule/rule_condition_product_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}')*/
						->setUsesPerCoupon(0)
						->setUsesPerCustomer(1)
						->setCustomerGroupIds(array(0,1)) //an array of customer groupids
						->setIsActive(1)
						->setStopRulesProcessing(0)
						->setIsAdvanced(1)
						->setProductIds()
						->setSortOrder(0)
						->setSimpleAction('by_fixed')
						->setDiscountAmount($price-$offer->getOurOfferPrice())
						->setDiscountQty(1)
						->setDiscountStep('0')
						->setSimpleFreeShipping('0')
						->setApplyToShipping('0')
						->setIsRss(0)
						->setWebsiteIds(array(1));      

						$item_found = Mage::getModel('salesrule/rule_condition_product_found')
						  ->setType('salesrule/rule_condition_product_found')
						  ->setValue(1) // 1 == FOUND
						  ->setAggregator('all'); // match ALL conditions
						$coupon->getConditions()->addCondition($item_found);
						$conditions = Mage::getModel('salesrule/rule_condition_product')
						  ->setType('salesrule/rule_condition_product')
						  ->setAttribute('sku')
						  ->setOperator('==')
						  ->setValue($product->getSku());
						$item_found->addCondition($conditions);

						$actions = Mage::getModel('salesrule/rule_condition_product')
						  ->setType('salesrule/rule_condition_product')
						  ->setAttribute('sku')
						  ->setOperator('==')
						  ->setValue($product->getSku());
						$coupon->getActions()->addCondition($actions);						
						$coupon->save();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Coupon created successfully."));
						$this->_save=true;
						$this->counterOfferAction();
						//$this->_redirect("*/*/");
					} else {
						//$oCoupon->delete();
						Mage::getSingleton("adminhtml/session")->addError("This promo already exists in the system. Please delete the existing one and try again.");
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
			
			//$this->GeneratePromoCode();
		}
		
		public function counterOfferAction() {
			$id=$this->getRequest()->getParam('id');
			if (!$id) {
				Mage::getSingleton("adminhtml/session")->addError("Not a valid id. Please save the offer information first and try again.");
				$this->_redirect("*/*/new");
				return;
			}
			$model = Mage::getModel("priceoffer/priceoffer");
			$offer = $model->load($id);

			$product=Mage::getModel('catalog/product')->load($offer->product_id);
			$emailOffer=$offer->getEmail();
			
			ob_start();
			?>
			<body style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
				<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; margin:0; padding:0;">
				<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
					<tr>
						<td align="center" valign="top" style="padding:20px 0 20px 0">
							<!-- [ header starts here] -->
							<table bgcolor="FFFFFF" cellspacing="0" cellpadding="10" border="0">
								<tr>
									<td valign="top">
										<a href="{{store url=""}}" style="color:#1E7EC8;"><img src="<?php echo Mage::getBaseUrl('skin')?>frontend/t2/default/images/logo.gif" alt="ChronoStore" border="0"/></a>
									</td>
								</tr>
								<!-- [ middle starts here] -->
								<tr>
									<td valign="top">
										<br><br>
										<h3 style="font-weight:normal; line-height:22px; margin:0 0 11px 0;">
											Dear <?php echo $emailOffer ?>,
										</h3><br>
										<div style="border:1px solid #E0E0E0; line-height:23px; margin:0; padding:13px 18px; background:#F9F9F9;">
											<p>
											<?php if ($offer->getOurOfferPrice()!=$offer->getOfferPrice()){ ?>
												ChronoStore has sent you a counteroffer.
											<?php } else { ?>
												Congratulations! ChronoStore has accepted your offer.
											<?php } ?>
											</p><br /><br />
											
											Counteroffer Price: $<?php echo number_format($offer->getOurOfferPrice(),2) ?><br />
											Product: <?php echo $product->getName() ?><br />
											Quantity: <?php echo $offer->getQty() ?><br />
											<p>Comments:</p>
											<p style="padding: 4px 15px"><?php echo $offer->getComments() ?></p>
											
											<p>
											<?php if ($offer->getOurOfferPrice()!=$offer->getOfferPrice()){ ?>
											Please review the counteroffer and if you are satisfied with the price, click on<a href="<?php echo $product->getProductUrl() ?>?link=outside&email=<?php echo $emailOffer ?>&coupon_code=<?php echo $offer->getPromo() ?>"> Agree &amp; Pay</a> and you will be redirected to our shopping cart with the item and a coupon already applied.
											<?php } else { ?>
											Please review your offer and click on<a href="<?php echo $product->getProductUrl() ?>?link=outside&email=<?php echo $emailOffer ?>&coupon_code=<?php echo $offer->getPromo() ?>"> Agree &amp; Pay</a> and you will be redirected to our shopping cart with the item and a coupon already applied.
											<?php } ?>
											</p>
											<br><p>Please note that this sale will expire in 24 hours.</p>
											
										</div>
									</td>
								</tr>
								<tr>
									<td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0; padding: 10px 0">Thank you again, <strong>ChronoStore.</strong></p></center></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				</div>
			</body>
			<?php
			
			
			$content=ob_get_clean();
			$mail = Mage::getModel('core/email');
			$mail->setToName($emailOffer);
			$mail->setToEmail($emailOffer);
			$mail->setBody($content);
			$mail->setSubject('Counteroffer from ChronoStore.');
			$mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_sales/email'));
			$mail->setFromName(Mage::getStoreConfig('trans_email/ident_sales/name'));
			$mail->setType('html');// You can use Html or text as Mail format
			$mail->send();
				
			$mail = Mage::getModel('core/email');
			$mail->setToName(Mage::getStoreConfig('trans_email/ident_sales/name'));
			$mail->setToEmail(Mage::getStoreConfig('trans_email/ident_sales/email'));
			$mail->setBody($content);
			$mail->setSubject('Review Counteroffer.');
			$mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_sales/email'));
			$mail->setFromName(Mage::getStoreConfig('trans_email/ident_sales/name'));
			$mail->setType('html');// You can use Html or text as Mail format
			$mail->send();
			
			Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Successfully sent an email to the customer"));
			$this->_redirect("*/*/");
		}
		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('offer_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("priceoffer/priceoffer");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'priceoffer.csv';
			$grid       = $this->getLayout()->createBlock('priceoffer/adminhtml_priceoffer_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'priceoffer.xml';
			$grid       = $this->getLayout()->createBlock('priceoffer/adminhtml_priceoffer_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
