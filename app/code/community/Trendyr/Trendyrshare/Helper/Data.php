<?php

class Trendyr_Trendyrshare_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function formatTrendyrshare($amount){
		return Mage::helper('trendyrshare')->__('Trendyr Discount');
	}

}