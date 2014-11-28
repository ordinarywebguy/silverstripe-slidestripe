<?php
/**
 * Data holder of set of slides
 */
class Slideshow extends DataObject {
	
	public static $db = array(
		'Title' => 'Varchar',
		'Width' => 'Varchar(10)',
		'Height' => 'Varchar(10)',
		'ShowPager' => 'Boolean',
		'PagerCSSClass' => 'Varchar',
		'TransitionLength' => 'Int',
		'TransitionStyle' => 'Varchar',
		'TransitionEase' => 'Varchar',
	);
	
	public static $has_many = array(
		'Images' => 'SlideshowImage'
	);
		
	public static $casting = array( 
	   'Dimensions' => 'Varchar',
	   'NumberOfSlides' => 'Int',
	   'Pages' => 'Varchar',
	);
	
	public static $summary_fields  = array(
		'Title', 'Dimensions', 'NumberOfSlides' 
	);
	
	public function getDimensions() {
		return "{$this->Width}X{$this->Height}";
	}

	
	public function getNumberOfSlides() {
		return $this->Images()->count();
	}
	

	public function getCMSValidator() {
		return new RequiredFields('Title');
	}
	
	public function jsSettings() {
		$settings = array(
			'fx' => $this->TransitionStyle,
			'speed' => (int) $this->TransitionLength,
			'easing' => $this->TransitionEase
		);
		if($this->ShowPager) {
			$settings['pager'] = '#pager_' . $this->ID;
		}
		return json_encode(array_filter($settings));
	}
	
	
	public function getCMSFields() {
		Session::set('SlideStripe.SlideshowID', $this->ID);
		$fields = parent::getCMSFields();
		$slideshowCount = $this->get()->count() + 1;
		$this->TransitionLength = ($this->convertMillisecondsToSeconds((int)$this->TransitionLength));
	
		$fields->replaceField('Title', new TextField('Title', 'Title', (!$this->exists() ? 'Slideshow No. ' . $slideshowCount : $this->Title)));
		$fields->replaceField('Width', new TextField('Width', 'Width', (!$this->exists() ? 'auto' : $this->Width)));
		$fields->replaceField('Height', new TextField('Height', 'Height', (!$this->exists() ? 'auto' : $this->Height)));
		$fields->replaceField('TransitionLength', $transitionLength = new NumericField('TransitionLength', 'Transition Length', (!$this->exists() ? 5 : $this->TransitionLength)));
		$fields->replaceField('TransitionStyle', $transitionStyle = new DropdownField('TransitionStyle', 'Effects', Config::inst()->get('Slideshow', 'Effects')));
		$fields->replaceField('TransitionEase', $transitionEase = new DropdownField('TransitionEase', 'Easing', Config::inst()->get('Slideshow', 'Easings')));
		$transitionLength->setRightTitle('In seconds');
		$transitionStyle->setEmptyString('');
		$transitionEase->setEmptyString('');
		
		return $fields;
	}
	
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->TransitionLength = $this->convertSecondsToMilliseconds($this->TransitionLength);
	}
	

	
	/**
	 * Converts seconds to milliseconds 
	 */
	private function convertSecondsToMilliseconds(Int $int) {
		return $int * 1000;
	}
	/**
	 * Converts  milliseconds to seconds
	 */	
	private function convertMillisecondsToSeconds(Int $int) {
		return $int / 1000;
	}
	
	
}



class SlideshowImage extends DataObject {
	
	public static $db = array(
		'Title' => 'Varchar',
		'Caption' => 'Varchar',
		'Link' => 'Varchar',
		'OpenLinkInNewTab' => 'Boolean'
	);
	
	public static $has_one = array(
		'Slideshow' => 'Slideshow',
		'Image' => 'Image'
	);
	
	
	public static $summary_fields  = array(
		'Title', 'Caption', 'Image.CMSThumbnail'
	);
	
	
	public function getImagePath() {
		$image = $this->Image();
		if($image && $image->exists()) {
			$width = $this->Slideshow()->Width;
			$height = $this->Slideshow()->Height;
			if(is_numeric($width) && is_numeric($height)) {
				$image = $image->SetSize($width, $height);
			}
			return $image->getRelativePath();
		}
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('SlideshowID');
		
		$uploadField = new UploadField('Image', 'Image'); 
		$uploadField->getValidator()->allowedExtensions = array('jpg', 'gif', 'png'); 
		$uploadField->setConfig('allowedMaxFileNumber', 1);
		$uploadField->setRecord($this);
		$fields->replaceField('Image', $uploadField);
		return $fields;
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->SlideshowID = (int) Session::get('SlideStripe.SlideshowID');
		
	}
	
}



