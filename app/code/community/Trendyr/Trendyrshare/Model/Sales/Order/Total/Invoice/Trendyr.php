<?php
class Trendyr_Trendyrshare_Model_Sales_Order_Total_Invoice_Trendyrshare extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Invoice $invoice)
	{
		$order = $invoice->getOrder();
		$trendyrshareAmountLeft = $order->getTrendyrshareAmount() - $order->getTrendyrshareAmountInvoiced();
		$baseTrendyrshareAmountLeft = $order->getBaseTrendyrshareAmount() - $order->getBaseTrendyrshareAmountInvoiced();
		if (abs($baseTrendyrshareAmountLeft) < $invoice->getBaseGrandTotal()) {
			$invoice->setGrandTotal($invoice->getGrandTotal() + $trendyrshareAmountLeft);
			$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseTrendyrshareAmountLeft);
		} else {
			$trendyrshareAmountLeft = $invoice->getGrandTotal() * -1;
			$baseTrendyrshareAmountLeft = $invoice->getBaseGrandTotal() * -1;

			$invoice->setGrandTotal(0);
			$invoice->setBaseGrandTotal(0);
		}
			
		$invoice->setTrendyrshareAmount($trendyrshareAmountLeft);
		$invoice->setBaseTrendyrshareAmount($baseTrendyrshareAmountLeft);
		return $this;
	}
}
