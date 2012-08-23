<?php
class Trendyr_Trendyrshare_Model_Observer
{
	public function invoiceSaveAfter(Varien_Event_Observer $observer)
	{
		$invoice = $observer->getEvent()->getInvoice();
		if ($invoice->getBaseTrendyrshareAmount()) {
			$order = $invoice->getOrder();
			$order->setTrendyrshareAmountInvoiced($order->getTrendyrshareAmountInvoiced() + $invoice->getTrendyrshareAmount());
			$order->setBaseTrendyrshareAmountInvoiced($order->getBaseTrendyrshareAmountInvoiced() + $invoice->getBaseTrendyrshareAmount());
		}
		return $this;
	}
	public function creditmemoSaveAfter(Varien_Event_Observer $observer)
	{
		/* @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
		$creditmemo = $observer->getEvent()->getCreditmemo();
		if ($creditmemo->getTrendyrshareAmount()) {
			$order = $creditmemo->getOrder();
			$order->setTrendyrshareAmountRefunded($order->getTrendyrshareAmountRefunded() + $creditmemo->getTrendyrshareAmount());
			$order->setBaseTrendyrshareAmountRefunded($order->getBaseTrendyrshareAmountRefunded() + $creditmemo->getBaseTrendyrshareAmount());
		}
		return $this;
	}
	
	
/*
	public function block_inject_cart_totals(Varien_Event_Observer $observer)
	{
		//http://www.magentocommerce.com/boards/viewthread/197627/#t247757
			
		$block = $observer->getEvent()->getBlock();

        if ($block->getId() == 'mage_checkout_block_cart_totals') 
        {
             $extendBlock = Mage::app()->getLayout()->createBlock('Mage_Core_Block_Text');
             
             $extendBlock->setText('<h1>This is a Test</h1>');
                 Mage::app()->getLayout()->getBlock('content')->append($extendBlock, 'block_td');            
            return $this;
		}	
	}
*/
	
	public function updatePaypalTotal($evt)
	{
		$cart = $evt->getPaypalCart();
		$cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL,$cart->getSalesEntity()->getTrendyrshareAmount());
	}

}