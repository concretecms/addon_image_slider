<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
class ImageSliderBlockController extends BlockController {
	
	var $pobj;
	
	protected $btTable = 'btImageSlider';
	protected $btInterfaceWidth = "550";
	protected $btInterfaceHeight = "400";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = true;
	protected $btCacheBlockOutputLifetime = 300;
	
	public $images = array();

	public $transitionType = 'FADE';
	public $controls = 'none';
	public $autoplay = 0;
	public $playbackLoop = 0;
	public $playback = 'ORDER';
	
	
	/** 
	 * Used for localization. If we want to localize the name/description we have to include this
	 */
	public function getBlockTypeDescription() {
		return t("Display a group of images in a slider.");
	}
	
	public function getBlockTypeName() {
		return t("Image Slider");
	}
	
	public function getJavaScriptStrings() {
		return array(
			'choose-file' => t('Choose Image/File'),
			'choose-min-2' => t('Please choose at least two images.'),
			'choose-fileset' => t('Please choose a file set.')
		);
	}
	
	public function __construct($obj = null) {		
		/* Defaults */
				
		parent::__construct($obj);

		$this->db = Loader::db();
		if ($this->fsID == 0) {
			$this->loadImages();
		} else {
			$this->loadFileSet();
		}
		$this->randomizeImages();	

		$this->set('playback', $this->playback);
		$this->set('playbackLoop', $this->playbackLoop);
		$this->set('transitionType', $this->transitionType);
		$this->set('autoplay', $this->autoplay);
		$this->set('controls', $this->controls);
		$this->set('fsID', $this->fsID);
		$this->set('fsName', $this->getFileSetName());
		$type = ($this->fsID > 0) ? 'FILESET' : 'CUSTOM';
		$this->set('type', $type);
		$this->set('bID', $this->bID);			

		$this->prepareImageArray();
	}	
	
	public function prepareImageArray(){
		$imageslider_json 		= new stdClass();
		$imageslider_json->meta = new stdClass();
		$imageslider_json->meta->order = Array();
		$imageslider_json->meta->orderPointer = 0;
		$image_info = Array();
		foreach($this->images as $image){
			$tmp = $image;
			$f = File::getByID($image['fID']);
			$tmp['f'] = $f;
			if (!isset($imageslider_json->{$tmp['fID']})) {
				$imageslider_json->{$tmp['fID']} = new stdClass();
			}
			$imageslider_json->{$tmp['fID']}->src = $f->getRelativePath();			
			$imageslider_json->meta->order[]=$tmp['fID'];
			$image_info[] = $tmp;
		}
		$jse = Loader::helper('json');
		$this->set('imageslider_json', $jse->encode($imageslider_json));	
		$this->set('images', $image_info);
	}
	
	protected function getSliderWidth() {
		$width = Cache::get('image_slider_width', $this->bID);
		if ($width != false) {
			return $width;
		}
		
		$width = 99999;
		foreach($this->images as $fa) {
			$f = File::getByID($fa['fID']);
			$size = getimagesize($f->getPath());
			if ($size[0] < $width) {
				$width = $size[0];
			}
		}
		
		Cache::set('image_slider_width', $this->bID, $width, 180);
		return $width;
	}
	
	protected function getSliderHeight() {
		$height = Cache::get('image_slider_height', $this->bID);
		if ($height != false) {
			return $height;
		}
		
		$height = 99999;
		foreach($this->images as $fa) {
			$f = File::getByID($fa['fID']);
			$size = getimagesize($f->getPath());
			if ($size[1] < $height) {
				$height = $size[1];
			}
		}
		
		Cache::set('image_slider_height', $this->bID, $height, 180);
		return $height;
	}
	

	public function view() {
		$this->set('sliderWidth', $this->getSliderWidth());
		$this->set('sliderHeight', $this->getSliderHeight());
	}
	
	public function getFileSetName(){
		$sql = "SELECT fsName FROM FileSets WHERE fsID=".intval($this->fsID);
		return $this->db->getOne($sql); 
	}

	public function loadFileSet(){
		if (intval($this->fsID) < 1) {
			return false;
		}
        
		Loader::helper('concrete/file');
		Loader::model('file_list');
		Loader::model('file_set');
		
		$fs = FileSet::getByID($this->fsID);
		$fileList = new FileList();		
		$fileList->filterBySet($fs);
		$fileList->filterByType(FileType::T_IMAGE);
		$files = $fileList->get(1000,0);
		
		$image = array();
		$image['duration'] = $this->duration;
		$image['fadeDuration'] = $this->fadeDuration;
		$image['groupSet'] = 0;
		$image['url'] = '';
		$images = array();
		foreach ($files as $f) {
			$fp = new Permissions($f);
			if(!$fp->canRead()) { continue; }
							
			$image['fID'] 			= $f->getFileID();
			$image['fileName'] 		= $f->getFileName();
			$image['fullFilePath'] 	= $f->getRelativePath();		
			
			$images[] = $image;
		}
		$this->images = $images;
	}


	public function loadImages(){
		if(intval($this->bID)==0) $this->images=array();
		$sortChoices=array('ORDER'=>'position','RANDOM'=>'rand()');
		if( !array_key_exists($this->playback,$sortChoices) ) 
			$this->playback='ORDER';
		if(intval($this->bID)==0) return array();
		$sql = "SELECT * FROM btImageSliderImg WHERE bID=".intval($this->bID).' ORDER BY '.$sortChoices[$this->playback];
		$this->images = $this->db->getAll($sql); 
	}
	
	public function delete(){
		$this->db->query("DELETE FROM btImageSliderImg WHERE bID=".intval($this->bID));		
		parent::delete();
	}
	
	public function save($data) { 

		$data['playbackLoop'] = ($data['playbackLoop'] == 1) ? 1 : 0;
		$data['transitionType'] = (isset($data['transitionType'])) ? $data['transitionType'] : 'FADE';
		$data['controls'] = (isset($data['controls'])) ? $data['controls'] : 'none';
		$data['controlsPlayPause'] = ($data['controlsPlayPause'] == 1) ? 1 : 0;
		$data['autoplay'] = ($data['autoplay'] == 1) ? 1 : 0;
		$data['duration'] = ($data['duration'] != '') ? $data['duration'] : 0;
		
		if ($data['controls'] == 'none') { 
			$data['autoplay'] = 1;
		}
		
		if (($data['autoplay'] == 1 || $data['controlsPlayPause'] == 1) && $data['duration'] == 0) { 
			$data['duration'] = 5;
		}
		
		if( $data['type'] == 'FILESET' && $data['fsID'] > 0){

			$files = $this->db->getAll("SELECT fv.fID FROM FileSetFiles fsf, FileVersions fv WHERE fsf.fsID = " . $data['fsID'] .
			         " AND fsf.fID = fv.fID AND fvIsApproved = 1");
			
			//delete existing images
			$this->db->query("DELETE FROM btImageSliderImg WHERE bID=".intval($this->bID));
		} else if( $data['type'] == 'CUSTOM' && count($data['imgFIDs']) ){
			
			$data['fsID'] = 0;

			//delete existing images
			$this->db->query("DELETE FROM btImageSliderImg WHERE bID=".intval($this->bID));
			
			//loop through and add the images
			$pos=0;
			foreach($data['imgFIDs'] as $imgFID){ 
				if(intval($imgFID)==0 || $data['fileNames'][$pos]=='tempFilename') continue; 
				$vals = array(intval($this->bID),intval($imgFID), trim($data['url'][$pos]),$pos);
				$this->db->query("INSERT INTO btImageSliderImg 
				(bID,fID,url,position) 
				values (?,?,?,?)",$vals);
				$pos++;
			}
		}
		
		Cache::delete('image_slider_width', $this->bID);
		Cache::delete('image_slider_height', $this->bID);
		parent::save($data);
	}
	
	public function randomizeImages() {
		if($this->playback == 'RANDOM') {
			shuffle($this->images);
		}
	}
}

?>
