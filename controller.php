<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Social Sharing add-on.
 * 
 * @author Adri Kodde
 */
class D3SocialSharingPackage extends Package {

	protected $pkgHandle = 'd3_social_sharing';
	protected $appVersionRequired = '5.6.0.2';
	protected $pkgVersion = '1.4';

	public function getPackageDescription() {
		return t('Displays social sharing icons');
	}

	public function getPackageName() {
		return t('Social Sharing');
	}

	public function install(){
		$pkg = parent::install();
		
		// install block
		BlockType::installBlockTypeFromPackage('d3_social_sharing', $pkg);
	}

	public function upgrade(){
		$pkg = parent::upgrade();
	}
}