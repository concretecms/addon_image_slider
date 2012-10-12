<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));

class ImageSliderPackage extends Package {

	protected $pkgHandle = 'image_slider';
	protected $appVersionRequired = '5.3.2b1';
	protected $pkgVersion = '1.1-devel';
	
	public function getPackageDescription() {
		return t("Provides a full-featured image slider.");
	}
	
	public function getPackageName() {
		return t("Image Slider");
	}
	
	public function install() {
		$pkg = parent::install();
		
		// install block		
		BlockType::installBlockTypeFromPackage('image_slider', $pkg);
		
	}

}