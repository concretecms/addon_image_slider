var ImageSliderBlock = {
	
	init:function(){},	
	
	chooseImg:function(){ 
		ccm_launchFileManager('&fType=' + ccmi18n_filemanager.FTYPE_IMAGE);
	},
	
	showImages:function(){
		$("#ccm-imagesliderBlock-imgRows").show();
		$("#ccm-imagesliderBlock-chooseImg").show();
		$("#ccm-imagesliderBlock-fsRow").hide();
	},

	showFileSet:function(){
		$("#ccm-imagesliderBlock-imgRows").hide();
		$("#ccm-imagesliderBlock-chooseImg").hide();
		$("#ccm-imagesliderBlock-fsRow").show();
	},

	selectObj:function(obj){
		if (obj.fsID != undefined) {
			$("#ccm-imagesliderBlock-fsRow input[name=fsID]").attr("value", obj.fsID);
			$("#ccm-imagesliderBlock-fsRow input[name=fsName]").attr("value", obj.fsName);
			$("#ccm-imagesliderBlock-fsRow .ccm-imagesliderBlock-fsName").text(obj.fsName);
        } else {
            this.addNewImage(obj.fID, obj.height, obj.width, obj.title, obj.thumbnailLevel1);
		}
	},

	addImages:null, 
	addNewImage: function(fID, imgHeight, imgWidth, title, thumbPath) { 
		if (this.addImages == null) this.addImages = $("#ccm-imagesliderBlock-imgRows .ccm-imagesliderBlock-imgRow").length;
		this.addImages++; 
		var imagesliderImgId=this.addImages;
		var templateHTML=$('#imgRowTemplateWrap .ccm-imagesliderBlock-imgRow').html().replace(/tempFID/g,fID);
		templateHTML=templateHTML.replace(/tempKey/g,this.addImages);
		templateHTML=templateHTML.replace(/tempFilename/g,title);
		templateHTML=templateHTML.replace(/tempImageSliderImgId/g,imagesliderImgId).replace(/tempHeight/g,imgHeight).replace(/tempWidth/g,imgWidth);
		var imgRow = document.createElement("div");
		imgRow.innerHTML=templateHTML;
		imgRow.id='ccm-imagesliderBlock-imgRow'+Math.abs(parseInt(this.addImages));	
		imgRow.className='ccm-imagesliderBlock-imgRow'; 
		var bgRow=$(imgRow).find('.backgroundRow')
		bgRow.css('background-image','url('+thumbPath+')');
		bgRow.css('height','60px');
		$('#ccm-imagesliderBlock-imgRows').append(imgRow);
	},
	
	removeImage: function(fID){
		$('#ccm-imagesliderBlock-imgRow'+fID).remove();
	},
	
	checkOptions: function() {
		var controlOpt = $("#ccm-imageslider-options-tab input[name=controls]:checked").val();	
		var autoplayOpt = $("#ccm-imageslider-options-tab input[name=autoplay]:checked").val();	
		switch(controlOpt) {
			case 'movie':
				$("#ccm-imageslider-options-tab select[name=controlsPlacement]").attr('disabled', false);
				$("#ccm-imageslider-options-tab input[name=controlsPlayPause]").attr('disabled', false);
				$("#ccm-imageslider-options-tab input[name=autoplay]").attr('disabled', false);
				break;
			case 'arrows':
				$("#ccm-imageslider-options-tab select[name=controlsPlacement]").attr('disabled', true);
				$("#ccm-imageslider-options-tab input[name=controlsPlayPause]").attr('disabled', true);
				$("#ccm-imageslider-options-tab input[name=autoplay]").attr('disabled', false);
				break;
			case 'none':
				$("#ccm-imageslider-options-tab select[name=controlsPlacement]").attr('disabled', true);
				$("#ccm-imageslider-options-tab input[name=controlsPlayPause]").attr('disabled', true);
				$("#ccm-imageslider-options-tab input[name=autoplay]").attr('disabled', true);
				$("#ccm-imageslider-options-tab input[name=autoplay][value=1]").attr('checked', true);
				break;
		}
		
		if (autoplayOpt == 0 && controlOpt == 'arrows') {
			$("#ccm-imageslider-options-tab input[name=duration]").attr('disabled', true);
		} else {
			$("#ccm-imageslider-options-tab input[name=duration]").attr('disabled', false);
		}
	},
	
	moveUp:function(fID){
		var thisImg=$('#ccm-imagesliderBlock-imgRow'+fID);
		var qIDs=this.serialize();
		var previousQID=0;
		for(var i=0;i<qIDs.length;i++){
			if(qIDs[i]==fID){
				if(previousQID==-1) break; 
				thisImg.after($('#ccm-imagesliderBlock-imgRow'+previousQID));
				break;
			}
			previousQID=qIDs[i];
		}	 
	},
	moveDown:function(fID){
		var thisImg=$('#ccm-imagesliderBlock-imgRow'+fID);
		var qIDs=this.serialize();
		var thisQIDfound=0;
		for(var i=0;i<qIDs.length;i++){
			if(qIDs[i]==fID){
				thisQIDfound=1;
				continue;
			}
			if(thisQIDfound){
				$('#ccm-imagesliderBlock-imgRow'+qIDs[i]).after(thisImg);
				break;
			}
		} 
	},
	serialize:function(){
		var t = document.getElementById("ccm-imagesliderBlock-imgRows");
		var qIDs=[];
		for(var i=0;i<t.childNodes.length;i++){ 
			if( t.childNodes[i].className && t.childNodes[i].className.indexOf('ccm-imagesliderBlock-imgRow')>=0 ){ 
				var qID=t.childNodes[i].id.replace('ccm-imagesliderBlock-imgRow','');
				qIDs.push(qID);
			}
		}
		return qIDs;
	},	

	validate:function(){
		var failed=0; 
		
		if ($("#newImg select[name=type]").val() == 'FILESET')
		{
			if ($("#ccm-imagesliderBlock-fsRow input[name=fsID]").val() <= 0) {
				alert(ccm_t('choose-fileset'));
				$('#ccm-imagesliderBlock-AddImg').focus();
				failed=1;
			}	
		} else {
			qIDs=this.serialize();
			if( qIDs.length<2 ){
				alert(ccm_t('choose-min-2'));
				$('#ccm-imagesliderBlock-AddImg').focus();
				failed=1;
			}	
		}
		
		if(failed){
			ccm_isBlockError=1;
			return false;
		}
		return true;
	} 
}

ccmValidateBlockForm = function() { return ImageSliderBlock.validate(); }
ccm_chooseAsset = function(obj) { ImageSliderBlock.selectObj(obj); }

$(function() {
	
	ImageSliderBlock.checkOptions();
	
	if ($("#newImg select[name=type]").val() == 'FILESET') {
		$("#newImg select[name=type]").val('FILESET');
		ImageSliderBlock.showFileSet();
	} else {
		$("#newImg select[name=type]").val('CUSTOM');
		ImageSliderBlock.showImages();
	}
	
	$("#ccm-imageslider-options-tab input[name=controls]").click(function() {
		ImageSliderBlock.checkOptions();
	});
	$("#ccm-imageslider-options-tab input[name=autoplay]").click(function() {
		ImageSliderBlock.checkOptions();
	});
	$("#newImg select[name=type]").change(function(){
		if (this.value == 'FILESET') {
			ImageSliderBlock.showFileSet();
		} else {
			ImageSliderBlock.showImages();
		}
	});
	
});

