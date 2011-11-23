<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?> 
<div id="ccm-imagesliderBlock-imgRow<?php echo $imgInfo['counter']?>" class="ccm-imagesliderBlock-imgRow" >
	<div class="backgroundRow" style="background: url(<?php echo $imgInfo['thumbPath']?>) no-repeat left top; padding-left: 100px">
		<div class="cm-imagesliderBlock-imgRowIcons" >
			<div style="float:right">
				<a onclick="ImageSliderBlock.moveUp('<?php echo $imgInfo['counter']?>')" class="moveUpLink"></a>
				<a onclick="ImageSliderBlock.moveDown('<?php echo $imgInfo['counter']?>')" class="moveDownLink"></a>
			</div>
			<div style="margin-top:4px"><a onclick="ImageSliderBlock.removeImage('<?php echo $imgInfo['counter']?>')"><img src="<?php echo ASSETS_URL_IMAGES?>/icons/delete_small.png" /></a></div>
		</div>
		<strong><?php echo $imgInfo['fileName']?></strong>
		<div style="margin-top:4px;">
		Link URL: <input name="url[]" type="text" value="<?=$imgInfo['url'] ?>" />
		</div>
		
		<div style="margin-top:4px">
		<input type="hidden" name="imgFIDs[]" value="<?php echo $imgInfo['fID']?>">
		<input type="hidden" name="imgHeight[]" value="<?php echo $imgInfo['imgHeight']?>">
		<input type="hidden" name="imgWidth[]" value="<?php echo $imgInfo['imgWidth']?>">
		</div>
	</div>
</div>
