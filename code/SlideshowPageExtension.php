<?php
class SlideshowPageExtension extends DataExtension {
	
	

	public static $has_one = array(
		'SlideshowWidget' => 'WidgetArea',
		//'SlideshowWidget2' => 'WidgetArea'
	);
	
	
	
	
	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab('Root.SlideshowWidget', new SlideshowWidgetAreaEditor('SlideshowWidget'));
		//$fields->addFieldToTab('Root.SlideshowWidget', new WidgetAreaEditor('SlideshowWidget2'));
		
		return $fields;
	}
	
}