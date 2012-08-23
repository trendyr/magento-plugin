<?php

class Trendyr_Trendyrshare_IndexController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
		$this->loadLayout();			
    	//$this->_initCssBlock();

        $this->_addContent($this->getLayout()->createBlock('trendyrshare/template')->setTemplate('form_block_action.phtml'));
    	$this->renderLayout();		

    	
    	
/*

    	$this->loadLayout();

        //create a text block with the name of "example-block"
        $block = $this->getLayout()
        ->createBlock('core/text', 'Trendyr_Trendyrshare-block')
        ->setText('<h1>Trendyr Configuration</h1>');
		
        $this->_addContent($block);

        $this->renderLayout();  
*/ 
        
        

     }	
}



