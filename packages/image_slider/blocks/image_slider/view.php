<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="ccm-imageSlider-wrapper" style="height: <?=$sliderHeight?>px; width: <?=$sliderWidth?>px">
<?
// The arrow left/right/top/bottom variables
$arrowHorzWidth = '30';
$arrowHorzHeight = '77';
$arrowVertWidth = '77';
$arrowVertHeight = '30';
?>

<? switch($controls) { 
	case 'arrows': ?>
		<? if ($transitionType == 'VERT') { ?>
			<?
			$arrowLeft = ($sliderWidth / 2) - ($arrowVertWidth / 2);
			?>
			<span id="ccm-imageSlider-prevBtn<?=$bID?>" class="ccm-imageSlider-prevBtn" style="left: <?=$arrowLeft?>px; top: -<?=$arrowVertHeight?>px" ><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/btn_up.gif" width="<?=$arrowVertWidth?>" height="<?=$arrowVertHeight?>" /></a></span>
			<span id="ccm-imageSlider-nextBtn<?=$bID?>" class="ccm-imageSlider-nextBtn" style="left: <?=$arrowLeft?>px; bottom: -<?=$arrowVertHeight?>px"><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/btn_down.gif"  width="<?=$arrowVertWidth?>" height="<?=$arrowVertHeight?>" /></a></span>

		<? } else { ?>

			<? // horizontal arrows ?>
			<?
			$arrowTop = ($sliderHeight / 2) - ($arrowHorzHeight / 2);
			?>
			<span id="ccm-imageSlider-prevBtn<?=$bID?>" class="ccm-imageSlider-prevBtn" style="left: -<?=$arrowHorzWidth?>px; top: <?=$arrowTop?>px" ><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/btn_prev.gif" width="<?=$arrowHorzWidth?>" height="<?=$arrowHorzHeight?>" /></a></span>
			<span id="ccm-imageSlider-nextBtn<?=$bID?>" class="ccm-imageSlider-nextBtn" style="right: -<?=$arrowHorzWidth?>px; top: <?=$arrowTop?>px"><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/btn_next.gif"  width="<?=$arrowHorzWidth?>" height="<?=$arrowHorzHeight?>" /></a></span>

		<? } ?>
		
		<? break;
	case 'movie': 
		switch($controlsPlacement) {
			case 'UL':
				$controlsClass = 'ccm-imageSlider-controls-upper-left';
				break;
			case 'UR':
				$controlsClass = 'ccm-imageSlider-controls-upper-right';
				break;
			case 'LL':
				$controlsClass = 'ccm-imageSlider-controls-lower-left';
				break;
			default:
				$controlsClass = 'ccm-imageSlider-controls-lower-right';
				break;
		}
		?>
	
		<div id="ccm-imageSlider-movie-controls<?=$bID?>" class="<?=$controlsClass?>">
			<span id="ccm-imageSlider-prevBtn<?=$bID?>" class="ccm-imageSlider-prevBtnMovie"><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/movie_prev.gif" width="16" height="16" /></a></span>
			<? if ($controlsPlayPause == 1) { ?>
				<span id="ccm-imageSlider-playBtn<?=$bID?>" class="ccm-imageSlider-playBtnMovie"><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/movie_play.gif" width="16" height="16" /></a></span>
				<span id="ccm-imageSlider-pauseBtn<?=$bID?>" class="ccm-imageSlider-pauseBtnMovie"><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/movie_pause.gif" width="16" height="16" /></a></span>
			<? } ?>
			<span id="ccm-imageSlider-nextBtn<?=$bID?>" class="ccm-imageSlider-nextBtnMovie"><a href="javascript:void(0)"><img src="<?php echo $this->getBlockURL()?>/images/movie_next.gif"  width="16" height="16" /></a></span>
		</div>
	
	<?
		break;
} ?>

<div id="ccm-imageSlider<?=$bID ?>" class="ccm-imageSlider" style="height: <?=$sliderHeight?>px; width: <?=$sliderWidth?>px">
<ul>
	<?php  for($i=0;$i<count($images);$i++){ ?>
	<?php  $imgInfo = $images[$i]; ?>
<?
		$url = $imgInfo['url'];
		if (!$url) {
			$url = 'javascript:void(0)';
		}

	?>
	<?php  $f = File::getByID($imgInfo['fID']); ?>			
	<li id="file_<?php echo $imgInfo['fID'];?>" class="imageSliderImages" style="width: <?=$sliderWidth?>px; height: <?=$sliderHeight?>px">
		<a href="<?=$url?>"><img src="<?php echo $f->getRelativePath()?>" alt="slider Image" width="<?=$f->getAttribute('width')?>" height="<?=$f->getAttribute('height')?>" /></a>
	</li>		
	<?php  } ?>
</ul>		
</div>

</div>

<script type="text/javascript">
	//var imageSliderHeight = <?php echo $minHeight ?>; 
	
	$(window).load( function(){
	
		$("#ccm-imageSlider<?=$bID ?>").easySlider({
			loop: <? if ($playbackLoop == 1) { ?> true <? } else { ?> false <? } ?>,
			<? switch($transitionType) { 
				case 'HORZ': ?>
					orientation: 'horizontal',
				<? break;
				case 'VERT': ?>
					orientation: 'vertical',
				<? break;
				default: ?>
					orientation: 'fade',
					
				<? break;
			} ?>
			
			<? if ($duration > 0) { ?>
				autoplayDuration: <?=floatval($duration) * 1000 ?>,
			<? } ?>
			<? if ($autoplay == 1) { ?>
				autoplay: true,
			<? } ?>
			
			<? if ($controlsPlayPause) { ?>
				includePlayPause: true,
				pauseButtons: true,
			<? } ?>
			
			<? if ($controls != 'movie') { ?>
				pauseable: true,
				hoverPause: true,
			<? } ?>
			
			prevId: 'ccm-imageSlider-prevBtn<?=$bID ?>',
			nextId: 'ccm-imageSlider-nextBtn<?=$bID ?>',
			playId: 'ccm-imageSlider-playBtn<?=$bID ?>',			
			pauseId: 'ccm-imageSlider-pauseBtn<?=$bID ?>'			
			
		});
	});
</script>
