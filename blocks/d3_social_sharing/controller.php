<?php     
defined('C5_EXECUTE') or die("Access Denied.");
    
class D3SocialSharingBlockController extends BlockController {
    protected $btInterfaceWidth    = "470";
    protected $btInterfaceHeight   = "300";
    
    protected $btTable = "btD3SocialSharing";
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
    
	public $default_max_width = 32;
	public $networks = array(
		'Facebook', 'Twitter', 'LinkedIn', 'Google+', 'Pinterest', 'Email',
	);

    public function getBlockTypeDescription() {
		$p = Package::getByHandle('d3_social_sharing');
		return $p->getPackageDescription();
    }
    
    public function getBlockTypeName() {
    	$p = Package::getByHandle('d3_social_sharing');
		return $p->getPackageName();
    }
    
    
    /*
        Default values when block is being added
    */
	public function add(){
		$this->max_width = $this->default_max_width;
	}
    
    
    /*
        Images will be cropped if they exceed the maximum width
    */
	public function getMaxWidth(){
		return (!$this->max_width OR $this->max_width == 0) ? $this->default_max_width : $this->max_width;
	}
	
	
	/*
	   This function can be overridden easily if you want to add more networks
	*/
	public function getNetworks(){
	    return $this->networks;
	}
	
	
	/*
		Returns integer with number of likes, shares or tweets
	*/
	public function getNumberOfInteractions(){
		$nh = Loader::helper('navigation');
		
		$page     = Page::getCurrentPage();
		$page_url = $nh->getCollectionURL($page);
		
		switch($this->network){
			case 'Facebook':
				$request = "https://graph.facebook.com/fql?q=".rawurlencode("SELECT like_count, share_count, total_count FROM link_stat WHERE url='".$page_url."'");
				$json = json_decode(file_get_contents($request), true);
				
				// total_count = like_cont + share_count - change the code if you need
				if($json && isset($json['data'][0]['total_count'])){
					return $json['data'][0]['total_count'];
				}
			break;
			case 'Twitter':
				$request = "http://urls.api.twitter.com/1/urls/count.json?url=".urlencode($page_url);
				$json = json_decode(file_get_contents($request), true); 
				
				if($json && isset($json[count])){
					return $json[count];
				}
			break;				
		}
		
		return false;
	}
	
	
	/*
	   Each social network has its own sharing URL
	*/
	public function getNetworkURL(){
		$nh = Loader::helper('navigation');
		
		/*
		  Share the current page, not the domain
		*/
		$page     = Page::getCurrentPage();
		$page_url = $nh->getCollectionURL($page);
		
		/*
		  Go for the meta_title, if it's empty use the page title
		*/
		$share_text = $page->getAttribute('meta_title');
		$share_text = ($share_text) ? $share_text : $page->getCollectionName();
		
		
		switch($this->network){
			case 'Facebook':
				$url = 'https://www.facebook.com/sharer/sharer.php?u='.$page_url;
			break;
			case 'Twitter':
				$url = 'http://twitter.com/share?url='.$page_url.'&amp;text='.$share_text;
			break;
			case 'Google+':
				$url = 'https://plus.google.com/share?url='.$page_url;
			break;
			case 'LinkedIn':
				$url = 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.$page_url;
			break;
			case 'Pinterest':
				// need to provide a valid URL to an image - if not provided the Pinterest link will not work
				// this should be implemented via a custom page attribute or set a default image 
				$hostUrl = 'http://'.$_SERVER['HTTP_HOST'];
				$v         = View::getInstance();
				$themePath = $v->getThemePath();
				if ($page->getAttribute('pinterest_img')) {
					$urlPinterestImg = $hostUrl . $page->getAttribute('pinterest_img')->getVersion()->getRelativePath();
				} else {
					$urlPinterestImg = $hostUrl.$themePath."/img/jakisport-agencija-pinterest.jpg";
				}
				$url = "http://pinterest.com/pin/create/link/?url=".$page_url."&amp;media=".$urlPinterestImg."&amp;description=".$share_text;
			break;
			case 'Email':
				$url = 'mailto:?Subject='.$share_text.'&Body='.$page_url;
			break;
		}
				
		return $url;
	}
	
	
	/*
	   By default, links open in a new window.
	   For some networks, this is not ideal (e.g. email and Pinterest)
	*/
	public function getLinkTarget(){
		switch($this->network){
			case 'Email':
				return '_self';
			break;
			case 'Pinterest':
				return '_blank';
			break;
			default: 
				return '_blank';
		}
	}
	
	
	public function save($args){
		$args['show_likes'] = (isset($args['show_likes']) ? 1 : 0);
		
		parent::save($args);
	}
}