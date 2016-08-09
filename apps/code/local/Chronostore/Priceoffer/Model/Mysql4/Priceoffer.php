<?php
class Chronostore_Priceoffer_Model_Mysql4_Priceoffer extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("priceoffer/priceoffer", "offer_id");
    }
}