<?php
class SlideshowWidgetAreaEditor extends WidgetAreaEditor {
	
	
	public function AvailableWidgets() {
		$widgets= new ArrayList();
		$widgets->push(singleton('SlideshowWidget'));
		return $widgets;
	}
	
}