<?php  
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Social Sharing add-on.
 * 
 */
class D3SocialSharingPackage extends Package {

	protected $pkgHandle = 'd3_social_sharing';
	protected $appVersionRequired = '5.6.0.2';
	protected $pkgVersion = '1.4.3';

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
		
		Loader::model('collection_attributes');
		
		$ak = CollectionAttributeKey::getByHandle('d3_ss_pinterest_image');
        if(!$ak || !intval($ak->getAttributeKeyID()) ) {
			$ak = CollectionAttributeKey::add(
        		'image_file', 
        		array(
        			'akHandle' => 'd3_ss_pinterest_image',
        			'akName' => 'Social Sharing Pinterest Image',
        			'akIsSearchable' => true
        		)
			);
		}
	}

	public function upgrade(){
		$pkg = parent::upgrade();
	}
}