<?php
class Trendyr_Trendyrshare_Model_Sales_Quote_Address_Total_Trendyrshare extends Mage_Sales_Model_Quote_Address_Total_Abstract{
	protected $_code = 'trendyrshare';

	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
		parent::collect($address);
		
		$this->_setAmount(0);
		$this->_setBaseAmount(0);

		$items = $this->_getAddressItems($address);
		if (!count($items)) {
			return $this; //this makes only address type shipping to come through
		}

		$quote = $address->getQuote();


		$exist_amount = $quote->getTrendyrshareAmount();
		$trendyrshare = Trendyr_Trendyrshare_Model_Trendyrshare::getTrendyrshare();
		$balance = $trendyrshare - $exist_amount;
		
		// 			$balance = $trendyrshare;

		//$this->_setAmount($balance);
		//$this->_setBaseAmount($balance);

		$address->setTrendyrshareAmount($balance);
		$address->setBaseTrendyrshareAmount($balance);
		    
		$quote->setTrendyrshareAmount($balance);

		$address->setGrandTotal($address->getGrandTotal() + $address->getTrendyrshareAmount());
		$address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseTrendyrshareAmount());
		
	}

	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		$amt = $address->getTrendyrshareAmount();
		if($address->getTrendyrshareAmount() == 0){return $this;}
		$address->addTotal(array(
				'code'=>$this->getCode(),
				'title'=>Mage::helper('trendyrshare')->__('Trendyr Discount'),
				'value'=> $amt
		));
		return $this;
	}
}