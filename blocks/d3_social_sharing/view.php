<?php     
defined('C5_EXECUTE') or die("Access Denied.");
$ih = Loader::helper('image');

if($this->controller->fID){
	$file = File::getByID($this->controller->fID);
	
	if($file){
		$icon = $ih->getThumbnail($file, (int)$this->controller->getMaxWidth(), 100, false);
		$url = $this->controller->getNetworkURL();
		
		$likes = $this->controller->getNumberOfInteractions();
		$likes = ($likes > 9999) ? $likes .'+' : $likes;
		
		echo '<a title="'.$file->getDescription().'" class="d3-social-sharing-icon '.$this->controller->css_classes.'" target="'.$this->controller->getLinkTarget().'" href="'.$url.'">';
			echo '<img src="'.$icon->src.'" width="'.$icon->width.'" height="'.$icon->height.'" alt="'.$file->getDescription().'" />';
			
			if($this->controller->show_likes != 0){
				$likes = $this->controller->getNumberOfInteractions();
				$likes = ($likes > 9999) ? $likes .'+' : $likes;
				
				if($likes){
					echo '<span class="likes">'.$likes.'</span>';
				}
			}
			
		echo '</a>';
	} else {
		echo t('No valid icon image has been selected');
	}
	
	
} else {
	echo t('No icon image has been selected');
}