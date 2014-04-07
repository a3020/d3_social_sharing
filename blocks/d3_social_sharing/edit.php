<?php      
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$al   = Loader::helper('concrete/asset_library');
?>

<style>
	label {
		min-width: 120px;
		float: left;
	}
</style>

<div class="ccm-block-field-group">
	<?php 	
	echo $form->label('network', t('Network'));
	echo $form->select('network', array_combine($this->controller->networks, $this->controller->networks), $this->controller->network);
	?>
</div>

<div class="ccm-block-field-group">
	<?php 
	echo $form->label('max_width', t('Maximum width'));
	echo $form->text('max_width', $this->controller->max_width);
	?>
</div>

<div class="ccm-block-field-group">
	<?php 
	$file = File::getByID($this->controller->fID);
	
	echo $form->label('fID', t('Icon Image'));
	echo $al->image('fID', 'fID', t('Choose file'), $file);
	?>
</div>

<div class="ccm-block-field-group">
	<?php 
	echo $form->label('css_classes', t('CSS Classes'));
	echo $form->text('css_classes', $this->controller->css_classes);
	?>
</div>

<div class="ccm-block-field-group">
	<?php 
	echo $form->label('tooltip', t('Tooltip'));
	echo $form->text('tooltip', $this->controller->tooltip);
	?>
</div>

<div class="ccm-block-field-group">
    <?php echo $form->label('show_likes', t('Show number of likes or tweets if available')); ?>
    
    <div class="input">
        <?php echo $form->checkbox('show_likes', 1, $show_likes); ?>
    </div>
</div>