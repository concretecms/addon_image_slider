<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?> 
<div id="ccm-imagesliderBlock-fsRow" class="ccm-imagesliderBlock-fsRow" >
	<div class="backgroundRow" style="padding-left: 100px">
		<strong>File Set:</strong> <span class="ccm-file-set-pick-cb"><?php echo $form->select('fsID', $fsInfo['fileSets'], $fsInfo['fsID'])?></span><br/><br/>
	</div>
</div>
