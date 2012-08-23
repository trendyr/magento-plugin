<?php


class Trendyr_Trendyrshare_Model_Trendyrshare extends Varien_Object{


	public static function getTrendyrshare()
	{
	
	   //if there's not a merch key, shut it down.	
	 	if(!($merchant_public_key = self::get_merch_key())){return false;}

	  //prep an array of cart items for Trendyr	
		$checkout_items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
		$trendyr_product_data = self::prep_trendyr_products($merchant_public_key, $checkout_items);

	
	  //make the call to Trendyr, get back an array of data about the transaction		
		$trendyr_data = self::trendyr_curl($trendyr_product_data, $_SESSION['trendyr']['transaction_social_key']);
		
		return -($trendyr_data->discount_amount);		
	}

	private function trendyr_curl($trendyr_product_data, $social_key = null)
	{
			
	  //grab the URL from the db
		$resource = Mage::getSingleton('core/resource');
	    $read_connection = $resource->getConnection('core_read');
	    $table_name = $resource->getTableName('trendyrshare');
	    $q = "SELECT * from $table_name";
	    $r = $read_connection->fetchAll($q);

	    $api_url = pathinfo($r[0]['jsmode']);
	    $api_url = $api_url['dirname'];
	    
	  //is there a social key? make a url choice based on that.
		$social_key ? 
			$url = $api_url.'/transaction/update/'.$social_key :
			$url = $api_url.'/transaction/create'; 

		$trendyr_product_data_string =  http_build_query($trendyr_product_data);
	 
	  //kick off a cURL 
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $trendyr_product_data_string);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
		
		$result = curl_exec($ch);		
		$result = json_decode($result);
		
	  //make sure the return is good to go, else kill it.
		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
		{
			unset($_SESSION['trendyr']);
			return false;
			
		} //Trendyr data unavilable.
		curl_close($ch);

	  //save the terndyr social key in post for later use if needed
		
		$max_discount     = number_format($result->max_discount_amount, 2);
		$current_discount = number_format($result->discount_amount, 2);

		$_SESSION['trendyr']['max_savings'] = $max_discount;
		
		!$_SESSION['trendyr']['transaction_social_key'] ? $_SESSION['trendyr']['transaction_social_key'] = $result->transaction_social_key : null;
	   
	  //determin the right message for the modal button
	  self::prep_trendyr_btn_msg($max_discount, $current_discount);

	  //the server sends back some JSON, change it to an object then return it.
	
		return $result;

	}
	
	private function prep_trendyr_btn_msg($max, $current)
	{
		$btn_message = '';
		$btn_code    = '';

		if($current == 0)
		{
			$btn_message = 'Save $'.number_Format($max, 2) .' by social sharing!';
			$btn_code    = 0;
		} 		
		
		else if ($max != $current) {
		
			$dif = number_format(($max - $current), 2);
			$btn_message = 'Save another $'.$dif.' instantly!';
			$btn_code    = 1;

		} else {
		
			$btn_message = 'Max savings applied!';
			$btn_code = 2;
		}
		
		$_SESSION['trendyr']['btn_msg_text'] = $btn_message;
		$_SESSION['trendyr']['btn_msg_code'] = $btn_code;
	}
	
	private function prep_trendyr_products($merchant_public_key, $checkout_items)
	{

	  //before getting into the products, stick the merch key on the array
		$trendyr_product_data['merchant_public_key'] = $merchant_public_key;
	

	  //iterate over the products, add them to the array with details.
		$i = 0;	
		foreach($checkout_items as $item)
		{			
			$_product = Mage::getModel('catalog/product')->load($item->getProductId());	
			$product_image_url = Mage::getModel('catalog/product_media_config')->getMediaUrl( $_product->getImage());				
						
			$trendyr_product_data['products'][$i]['product_sku']		 = $_product->getSku();
			$trendyr_product_data['products'][$i]['product_name']		 = $_product->getName();
			$trendyr_product_data['products'][$i]['product_url']		 = $_product->getProductUrl();
			$trendyr_product_data['products'][$i]['product_quantity']	 = $item->getQty();
			$trendyr_product_data['products'][$i]['product_price']		 = $_product->getPrice();
			$trendyr_product_data['products'][$i]['product_description'] = $_product->getDescription();
			$trendyr_product_data['products'][$i]['product_image_url']	 = $product_image_url;
			
			//$trendyr_product_data['products'][$i]['major_category']     =  // add this later
			//$trendyr_product_data['products'][$i]['minor_category']     =  // add this later
			
			$i++;
			
		}
		return $trendyr_product_data;
	}
	
	
	private function get_merch_key()
	{
	  //read in the Trendyr social key from admin config
		$resource = Mage::getSingleton('core/resource');
		$read_connection = $resource->getConnection('core_read');
 		$table_name = $resource->getTableName('trendyrshare');
 	
	 	$q = "SELECT merchantkey from $table_name";
 		$r = $read_connection->fetchAll($q);
 		
	 	return $r[0]['merchantkey'];

	}
	
	public static function canApply($address){
		//put here your business logic to check if trendyrshare should be applied or not
		//if($address->getAddressType() == 'billing'){
		return true;
		//}

	}
}