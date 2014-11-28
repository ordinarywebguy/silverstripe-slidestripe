<?php

class SlideshowWidget extends Widget {
	
	public static $title = 'Slideshow';
	public static $cmsTitle = 'Slideshow';
	public static $description = 'Displays gallery slideshow';
	
	
	public static $has_one = array(
		'Slideshow' => 'Slideshow'
	);
	
	
	public function getCMSFields() {
		return new FieldList(
			new DropdownField('SlideshowID', 'Select Slideshow', Slideshow::get()->map('ID', 'Title'))		
		);
	}
	
	
}


class SlideshowWidget_Controller extends Widget_Controller  {
	
	public function init() {
		parent::init();
		Requirements::css('slidestripe/css/slidestripe.css');
		Requirements::javascript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
		Requirements::javascript('slidestripe/js/jquery.cycle.all.js');
		Requirements::javascript('slidestripe/js/jquery.easing.1.3.js');
		Requirements::customScript("jQuery('#slideshow_". $this->SlideshowID . "').cycle(".$this->Slideshow()->jsSettings().")");
		
		// Bind click event on images if Link is available
		if($this->Slideshow()->getNumberOfSlides() > 0) {
			$ctr = 0; 
			$script = '';
			foreach($this->Slideshow()->Images() as $image) {
				if($image->Link) {
					if($image->OpenLinkInNewTab) {
						$event = "window.open('{$image->Link}');";
					} else {
						$event = "window.location = '{$image->Link}';";
					}
					$script .= "jQuery('#slideshow_". $this->SlideshowID . " img:eq($ctr)').click(function(){ $event }).css('cursor', 'pointer');\n";
				}
				$ctr++;
			}
				
			if(!empty($script)) {
				Requirements::customScript($script);
			}
				
		}
	}
	
	
	
}