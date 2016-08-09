<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table price_offer(offer_id int not null auto_increment, product_id int(10), product_name varchar(255), email varchar(100), offer_price decimal(12,2), qty int(3), phone varchar(15), zip_code varchar(11), comments varchar(1024), promo varchar(10), primary key(offer_id));
		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 