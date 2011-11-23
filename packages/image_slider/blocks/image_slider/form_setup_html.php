<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
$al = Loader::helper('concrete/asset_library');
$ah = Loader::helper('concrete/interface');
?>
<style>
#ccm-imagesliderBlock-imgRows a{cursor:pointer}
#ccm-imagesliderBlock-imgRows .ccm-imagesliderBlock-imgRow,
#ccm-imagesliderBlock-fsRow {margin-bottom:16px;clear:both;padding:7px;background-color:#eee}
#ccm-imagesliderBlock-imgRows .ccm-imagesliderBlock-imgRow a.moveUpLink{ display:block; background:url(<?php echo DIR_REL?>/concrete/images/icons/arrow_up.png) no-repeat center; height:10px; width:16px; }
#ccm-imagesliderBlock-imgRows .ccm-imagesliderBlock-imgRow a.moveDownLink{ display:block; background:url(<?php echo DIR_REL?>/concrete/images/icons/arrow_down.png) no-repeat center; height:10px; width:16px; }
#ccm-imagesliderBlock-imgRows .ccm-imagesliderBlock-imgRow a.moveUpLink:hover{background:url(<?php echo DIR_REL?>/concrete/images/icons/arrow_up_black.png) no-repeat center;}
#ccm-imagesliderBlock-imgRows .ccm-imagesliderBlock-imgRow a.moveDownLink:hover{background:url(<?php echo DIR_REL?>/concrete/images/icons/arrow_down_black.png) no-repeat center;}
#ccm-imagesliderBlock-imgRows .cm-imagesliderBlock-imgRowIcons{ float:right; width:35px; text-align:left; }

</style>
<ul class="ccm-dialog-tabs" id="ccm-imageslider-tabs">
	<li class="ccm-nav-active"><a href="javascript:void(0)" id="ccm-imageslider-type"><?php echo t('Choose Images')?></a></li>
	<li><a href="javascript:void(0)" id="ccm-imageslider-options"><?php echo t('Options')?></a></li>
</ul>
<div id="ccm-imageslider-options-tab" style="display:none">

	<div class="ccm-block-field-group">
	<h2><?php echo t('Looping');?></h2>
		<?=t('The images should:')?>
		<select name="playbackLoop" style="vertical-align: middle">
			<option value="1"<?php  if ($playbackLoop == '1') { ?> selected<?php  } ?>><?php echo t('loop continuously')?></option>
			<option value="0"<?php  if ($playbackLoop == '0') { ?> selected<?php  } ?>><?php echo t('have a beginning and an end')?></option>
		</select>
	</div>

	<div class="ccm-block-field-group">
	<h2><?php echo t('Transition Type');?></h2>
		<?=t('The images should:')?>
		<select name="transitionType" style="vertical-align: middle">
			<option value="FADE"<?php  if ($transitionType == 'FADE') { ?> selected<?php  } ?>><?php echo t('fade between images')?></option>
			<option value="HORZ"<?php  if ($transitionType == 'HORZ') { ?> selected<?php  } ?>><?php echo t('scroll horizontally')?></option>
			<option value="VERT"<?php  if ($transitionType  == 'VERT') { ?> selected<?php  } ?>><?php echo t('scroll vertically')?></option>
		</select>
	</div>

	<div class="ccm-block-field-group">
	<h2><?php echo t('Playback Controls');?></h2>
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td style="padding-right: 20px" valign="top">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td><input type="radio" name="controls" value="none" <? if ($controls == 'none') { ?> checked <? } ?> style="vertical-align: middle" />&nbsp;</td><td><?=t('None')?></td>
		</tr>
		</table>
	</td>
	<td style="padding-right: 20px" valign="top">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td><input type="radio" name="controls" value="arrows" <? if ($controls == 'arrows') { ?> checked <? } ?> style="vertical-align: middle" />&nbsp;</td><td><?=t('Arrows on Edges')?></td>
		</tr>
		</table>
	</td>
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><input type="radio" name="controls" value="movie" <? if ($controls == 'movie') { ?> checked <? } ?> style="vertical-align: middle" />&nbsp;</td><td><?=t('Movie-Style Controls, in')?>
			<select name="controlsPlacement" style="vertical-align: middle" <? if ($controls != 'movie') { ?> disabled <? } ?> >
				<option value="UL"<?php  if ($controlsPlacement == 'UL') { ?> selected<?php  } ?>><?php echo t('Upper Left Corner')?></option>
				<option value="LL"<?php  if ($controlsPlacement  == 'LL') { ?> selected<?php  } ?>><?php echo t('Lower Left Corner')?></option>
				<option value="UR"<?php  if ($controlsPlacement == 'UR') { ?> selected<?php  } ?>><?php echo t('Upper Right Corner')?></option>
				<option value="LR"<?php  if ($controlsPlacement  == 'LR') { ?> selected<?php  } ?>><?php echo t('Lower Right Corner')?></option>
			</select>

			</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>
			<input type="checkbox" name="controlsPlayPause" value="1" <? if ($controls != 'movie') { ?> disabled <? } ?> <? if ($controlsPlayPause == 1) { ?> checked <? } ?> /> <?=t('Include play/pause button.')?>
			</td>
			</tr>
			</table>
			
		</td>
	</tr>
	</table>
	</div>

	<div class="ccm-block-field-group">
	
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="150" valign="top">
	
	<h2><?php echo t('Autoplay');?></h2>
	<div><input type="radio" name="autoplay" value="0" <? if ($autoplay == 0) { ?> checked <? } ?> style="vertical-align: middle" /> <?=t('No')?></div>
	<div><input type="radio" name="autoplay" value="1" <? if ($autoplay == 1) { ?> checked <? } ?> style="vertical-align: middle" /> <?=t('Yes')?></div>

	</td>
	<td valign="top">
	
	<h2><?=t('Display Duration')?></h2>
	
	<? if ($autoplay == 0 && $controls == 'arrows') {
		$durationEnabled = 'disabled';
	} ?>
	<?=t("While playing, display each image for %s seconds.", '<input type="text" style="width: 30px" name="duration" ' . $durationEnabled . '" value="' . $duration . '" />');
	?>
	
	
	</td>
	</tr>
	</table>
	</div>
</div>
<div id="ccm-imageslider-type-tab">
	<div id="newImg" style="margin-top:10px;">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
		<td>
		<strong><?php echo t('Type')?></strong>
		<select name="type" style="vertical-align: middle">
			<option value="CUSTOM"<?php  if ($type == 'CUSTOM') { ?> selected<?php  } ?>><?php echo t('Custom Slider')?></option>
			<option value="FILESET"<?php  if ($type == 'FILESET') { ?> selected<?php  } ?>><?php echo t('Images from File Set')?></option>
		</select>
		</td>
		<td>
		<strong><?php echo t('Order')?></strong>
		<select name="playback" style="vertical-align: middle">
			<option value="ORDER"<?php  if ($playback == 'ORDER') { ?> selected<?php  } ?>><?php echo t('Display Order')?></option>
			<option value="RANDOM"<?php  if ($playback == 'RANDOM') { ?> selected<?php  } ?>><?php echo t('Random')?></option>
		</select>
		&nbsp;
		</td>
		</tr>
		<tr style="padding-top: 8px">
		<td colspan="2">
		<br />
		<span id="ccm-imagesliderBlock-chooseImg"><?php echo $ah->button_js(t('Add Image'), 'ImageSliderBlock.chooseImg()', 'left');?></span>
		</td>
		</tr>
		</table>
	</div>
	<br/>
	
	<div id="ccm-imagesliderBlock-imgRows">
	<?php  
	if ($fsID <= 0) {
		$counter = 0;
		foreach($images as $imgInfo){ 
			$f = File::getByID($imgInfo['fID']);
			$fp = new Permissions($f);
			$fv=$f->getApprovedVersion();
			$imgInfo['fileName']  = $f->getTitle();
			$imgInfo['thumbPath'] = $fv->getThumbnail(1,false);
			$imgInfo['absThumbPath'] = $fv->getThumbnailPath(1);
			$sizes=@getimagesize( $imgInfo['absThumbPath'] ); 
			$imgInfo['thumbHeight'] = (intval($sizes[1])>0)?intval($sizes[1]):60;
			$imgInfo['counter'] = $counter;
			$counter++;
			if ($fp->canRead()) { 
				$this->inc('image_row_include.php', array('imgInfo' => $imgInfo));
			}
		}
	} ?>
	</div>
	
	<?php 
	Loader::model('file_set');
	$s1 = FileSet::getMySets();
	$sets = array();
	foreach ($s1 as $s){
		$sets[$s->fsID] = $s->fsName;
	}
	$fsInfo['fileSets'] = $sets;
	
	if ($fsID > 0) {
		$fsInfo['fsID'] = $fsID;
		$fsInfo['duration']=$duration;
		$fsInfo['fadeDuration']=$fadeDuration;
	} else {
		$fsInfo['fsID']='0';
		$fsInfo['duration']=$defaultDuration;
		$fsInfo['fadeDuration']=$defaultFadeDuration;
	}
	$this->inc('fileset_row_include.php', array('fsInfo' => $fsInfo)); ?> 
	
	<div id="imgRowTemplateWrap" style="display:none">
	<?php 
	$imgInfo['imagesliderImgId']='tempImageSliderImgId';
	$imgInfo['fID']='tempFID';
	$imgInfo['counter']='tempKey';
	$imgInfo['fileName']='tempFilename';
	$imgInfo['origfileName']='tempOrigFilename';
	$imgInfo['imgHeight']=tempHeight;
	$imgInfo['imgWidth']=tempWidth;
	$imgInfo['url']='';
	$imgInfo['class']='ccm-imagesliderBlock-imgRow';
	?>
	<?php  $this->inc('image_row_include.php', array('imgInfo' => $imgInfo)); ?> 
	</div>
</div>

<!-- Tab Setup -->
<script type="text/javascript">
$(function() {
	var ccm_fpActiveTab = "ccm-imageslider-type";	
	$("#ccm-imageslider-tabs a").click(function() {
		$("li.ccm-nav-active").removeClass('ccm-nav-active');
		$("#" + ccm_fpActiveTab + "-tab").hide();
		ccm_fpActiveTab = $(this).attr('id');
		$(this).parent().addClass("ccm-nav-active");
		$("#" + ccm_fpActiveTab + "-tab").show();
	});
	$("input[name='controlsShow']").click(function(){
		if ($("input[name='controlsShow']:checked").val() != 0) {
			$(".controlsShowOnly").show();
		} else {
			$(".controlsShowOnly").hide();
		}
	});
	$("input[name='autoScroll']").click(function(){
		if ($("input[name='autoScroll']:checked").val() != 0) {
			$(".autoScrollOnly").show();
		} else {
			$(".autoScrollOnly").hide();
		}
	});
	
	if ($("input[name='autoScroll']:checked").val() == 0) {
		$(".autoScrollOnly").hide();
	}
	if ($("input[name='controlsShow']:checked").val() == 0) {
		$(".controlsShowOnly").hide();
	}
});
</script>
