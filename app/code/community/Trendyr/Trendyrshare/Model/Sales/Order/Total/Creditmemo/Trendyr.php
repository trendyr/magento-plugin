<?php
class Trendyr_Trendyrshare_Model_Sales_Order_Total_Creditmemo_Trendyrshare extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
	{
		$order = $creditmemo->getOrder();
		$trendyrshareAmountLeft = $order->getTrendyrshareAmountInvoiced() - $order->getTrendyrshareAmountRefunded();
		$basetrendyrshareAmountLeft = $order->getBaseTrendyrshareAmountInvoiced() - $order->getBaseTrendyrshareAmountRefunded();
		if ($basetrendyrshareAmountLeft > 0) {
			$creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $trendyrshareAmountLeft);
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basetrendyrshareAmountLeft);
			$creditmemo->setTrendyrshareAmount($trendyrshareAmountLeft);
			$creditmemo->setBaseTrendyrshareAmount($basetrendyrshareAmountLeft);
		}
		return $this;
	}
}
