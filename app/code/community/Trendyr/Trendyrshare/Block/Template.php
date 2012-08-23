<?php
	class Trendyr_Trendyrshare_Block_Template extends Mage_Core_Block_Template
	{
		public function fetchView($fileName)
		{					
			//ignores file name, just uses a simple include with template name
			$path = Mage::getModuleDir('', 'Trendyr_Trendyrshare').'/templates';
			$file = pathinfo($fileName);
			$file = $file['filename'].'.'.$file['extension'];
			$path = $path.'/'.$file;
			include($path);
		}
	}	
	
	