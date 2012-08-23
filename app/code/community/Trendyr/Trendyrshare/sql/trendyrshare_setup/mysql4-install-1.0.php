<?php

  $installer = $this;

  $installer->startSetup();

  $installer->run("

    DROP TABLE IF EXISTS {$this->getTable('trendyrshare')};

    CREATE TABLE {$this->getTable('trendyrshare')} (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `merchantkey` varchar(255) NOT NULL DEFAULT '',
    `jsmode` varchar(255) DEFAULT NULL,
    `tcopy` text,
     PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;	
  ");
    

	$installer->run("INSERT INTO {$this->getTable('trendyrshare')} (merchantkey) VALUES ('');"); 
	$installer->run("UPDATE {$this->getTable('trendyrshare')} set jsmode='https://api.trendyr.com/trendyr.js' WHERE id=1;");

  $installer->run("

		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `trendyrshare_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_trendyrshare_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;

		ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `trendyrshare_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `base_trendyrshare_amount` DECIMAL( 10, 2 ) NOT NULL;

		ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `trendyrshare_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `base_trendyrshare_amount` DECIMAL( 10, 2 ) NOT NULL;			
		
		ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `trendyrshare_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `base_trendyrshare_amount` DECIMAL( 10, 2 ) NOT NULL;

		");


  $installer->endSetup();
  
