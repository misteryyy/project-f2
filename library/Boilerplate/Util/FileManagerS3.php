<?php
use App\Entity\Project;
use App\Entity\User;

class Boilerplate_Util_FileManagerS3
{	

		// *** Class variables
		private $file;
		private $type;
		private $uploadDir; // path from the web root
		private $storageLocalProject; // absolut path to the root
		private $storageLocalUser;
		private $webPath;
		private $project; // project entity
		private $s3;
		private $bucket = 'floplatform-storage';
		private $resolutionsProject;
		private $resolutionsUser;
		
		/**
		 * Set project resolutions
		 */
		function setProjectResolutions(){
			$this->resolutionsProject =  array(
					array('type'=>"tiny",
							'width'=>Project::PROJECT_PHOTO_RESOLUTION_TINY_WIDTH,
							'height'=> Project::PROJECT_PHOTO_RESOLUTION_TINY_HEIGHT
					),
			
					array('type'=>"small",
							'width'=>Project::PROJECT_PHOTO_RESOLUTION_SMALL_WIDTH,
							'height'=> Project::PROJECT_PHOTO_RESOLUTION_SMALL_HEIGHT
					),
					array('type'=>"medium",
							'width'=>Project::PROJECT_PHOTO_RESOLUTION_MEDIUM_WIDTH,
							'height'=> Project::PROJECT_PHOTO_RESOLUTION_MEDIUM_HEIGHT
					),
					array('type'=>"big",
							'width'=>Project::PROJECT_PHOTO_RESOLUTION_BIG_WIDTH,
							'height'=> Project::PROJECT_PHOTO_RESOLUTION_BIG_HEIGHT
					),
			
					array('type'=>"large",
							'width'=>Project::PROJECT_PHOTO_RESOLUTION_LARGE_WIDTH,
							'height'=> Project::PROJECT_PHOTO_RESOLUTION_LARGE_HEIGHT
					)
			);
			
			
		}
		
		/**
		 * Set project resolutions
		 */
		function setUserResolutions(){
			$this->resolutionsUser =  array(
					array('type'=>"tiny",
							'width'=>User::PROFILE_PHOTO_RESOLUTION_TINY_WIDTH,
							'height'=> User::PROFILE_PHOTO_RESOLUTION_TINY_HEIGHT
					),
						
					array('type'=>"small",
							'width'=>User::PROFILE_PHOTO_RESOLUTION_SMALL_WIDTH,
							'height'=> User::PROFILE_PHOTO_RESOLUTION_SMALL_HEIGHT
					),
					array('type'=>"medium",
							'width'=> User::PROFILE_PHOTO_RESOLUTION_MEDIUM_WIDTH,
							'height'=> User::PROFILE_PHOTO_RESOLUTION_MEDIUM_HEIGHT
					),
					array('type'=>"big",
							'width'=> User::PROFILE_PHOTO_RESOLUTION_BIG_WIDTH,
							'height'=> User::PROFILE_PHOTO_RESOLUTION_BIG_HEIGHT
					),
						
					array('type'=>"large",
							'width'=> User::PROFILE_PHOTO_RESOLUTION_LARGE_WIDTH,
							'height'=> User::PROFILE_PHOTO_RESOLUTION_LARGE_HEIGHT
					)
			);
				
				
		}
		
		function __construct($project = null)
		{
			// settinf of bucket
			$config = new \Zend_Config(\Zend_Registry::get('config'));
			$this->bucket =  $config->app->s3->storage->bucket;	
			$this->storageLocalProject = $config->app->storage->project;
			$this->storageLocalUser = $config->app->storage->user;
			$this->project = $project;				
			$this->s3 = new Zend_Service_Amazon_S3('AKIAIIT4QVXXNFSXG3BA','aPe8De9tNYpl5kz8aCYUBWVg2aEcFsV/rMyMx9fT');
			if(!$this->s3->isBucketAvailable($this->bucket)) {
				throw new \Exception("Can't connect to file server. Try it later.");
			}
			
			// set resolutions
			$this->setProjectResolutions();
			$this->setUserResolutions();	
		}
		
		/**
		 * Generates new file name in format ftimestamp
		 * @param unknown_type $file
		 * @throws \Exception
		 */
		function generateNewFileInformation($file,$name,$type,$size){
			if(!is_file($file)) throw new \Exception("Is not valid file.");
			$ext = substr(strrchr($name,'.'), 1);  // get file extension	
			$newFileName = 'pf-'.date("Ymd-H:i:s").'.'.$ext;
			return array('temp' => $file,'file' => $newFileName, 'type' => $type,'size'=> $size);	
		}
		
		
		/**
		 * Creates dir for project
		 */
		function createDirForProject($user_id){
			$dir = sha1($this->project->id .'+' . $user_id)."_".$this->project->id;
			return $dir;	
		}
		
		/**
		 * File method to create thumbnails
		 */		
		function createThumbnailsForProject($user_id,$image_path,$file,$dir){

			// create project folder and save path for directory
			$config = new \Zend_Config(\Zend_Registry::get('config'));
			$path = $config->app->storage->project;
	
			$thumbDir = $dir.'/thumbs/';
			rrmdir($path.$dir); // delete previous files
			mkdir($path.$dir); // creating of the new directory
			mkdir($path.$thumbDir); // creating dir for thumbs
				
			$newImagePath = $path.$thumbDir.$file;
			
			if (copy($image_path,$newImagePath)) {
				// creating new thumbs
				$imageManager = new \Boilerplate_Util_ImageManager($newImagePath);
				$ext = substr(strrchr($newImagePath, '.'), 1);
				
				$pre = substr($newImagePath,0,strrpos($newImagePath, '.'));
				$preFile = substr($file,0,strrpos($file, '.'));

				$output = $this->bucket.'/projects/'.$thumbDir;

				//Create all resolutions and copy them to s3 server
				foreach($this->resolutionsProject as $r){
					$imagePath = $pre.'_'.$r['type'].'.'.$ext;
					$imageS3Path = $output.$preFile.'_'.$r['type'].'.'.$ext; // addres to S3 server
					$imageManager->resizeImage($r['width'], $r['height'], 'crop');
					$imageManager->saveImage($imagePath, 100);
					
					// upload to S3 server
					$this->s3->putFile($imagePath, $imageS3Path,
							array(Zend_Service_Amazon_S3::S3_ACL_HEADER =>
									Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ));
				}
				
				return array('file'=>$preFile.'_large.'.$ext);
				
			} else {
					
				throw new \Exception("Can't copy the file." );
			}

		}
		
		/**
		 * Return file
		 * @param unknown_type $file
		 */
		function findProjectBoardFile($file){
			
			$output = $this->bucket.'/projects/'.$this->project->dir."/pb/".$file->file;
			
			// check if the file exists
			if(!$this->s3->isObjectAvailable($output)) {
				throw new \Exception("The file doesn't exists." );
			}
			
			header( 'Content-Type:'.$file->type );
			header( 'Content-Disposition: attachment;filename="'.$file->name.'"');
			echo $this->s3->getObject($output);
			
		}
		
		/**
		 * Create new thumbnail in temporary storage
		 */
		function createTemporaryThumbnailFromPost($user_id){
			
			$upload = new Zend_File_Transfer();
			// Returns all known internal file information
			$adapter = new Zend_File_Transfer_Adapter_Http();
			
			$config = Zend_Registry::get('config');
			$tempPath = $config['app']['storage']['project_temp'];
			$tempWebPath = $config['app']['storage']['project_web_temp'];
			
			
			// setting upload file
			$adapter->setDestination($tempPath);
			$adapter->addValidator('Size', false, 4*10*102400)
			->addValidator('Count', false, 1)
			->addValidator('Extension', false, 'jpg,jpeg,png');
			
			$files = array();
			$i= 1;
			foreach ($adapter->getFileInfo() as $file => $info) {
				// check if uploaded
				if (!$adapter->isUploaded($file)) {
					throw new \Exception("You haven't choose the file. Try it again :D.");
					break;
				}
			
				// validators are ok ?
				if (!$adapter->isValid($file)) {
					throw new \Exception("Please check the file: ".$info["name"] );	
					break;
				}
			
				$ext = substr(strrchr($info['name'],'.'), 1);
				$fileName = 'project'.sha1("s@4d".$user_id);
			
				// resolution path
				$path = $tempPath.$fileName.'.'.$ext;
				$web_url = $tempWebPath.$fileName.'.'.$ext;
		
				$adapter->addFilter('Rename',array('target' => $path,'overwrite' => true));
				
				// receiving files
				if(!$adapter->receive($file)){
					throw new \Exception("Can't upload file. Try it later please" );
					break;
				}
			
				//PROCESSING OF IMAGE
				$imageManager = new \Boilerplate_Util_ImageManager($path);
				$imageManager->resizeImage(Project::PROJECT_PHOTO_RESOLUTION_LARGE_WIDTH,Project::PROJECT_PHOTO_RESOLUTION_LARGE_HEIGHT,'crop');
				$imageManager->saveImage($path, 100);
					
				$files[] = array('path'=> $path,'web_url'=>$web_url,'file'=> $fileName.'.'.$ext);
				$i++; // increment file
				} // end foreach through all files
			 return $files[0];
		}
		
		
		/**
		 * Create new thumbnail in temporary storage
		 */
		function updateThumbnail($user_id){
				
			$upload = new Zend_File_Transfer();
			// Returns all known internal file information
			$adapter = new Zend_File_Transfer_Adapter_Http();
				
			$config = Zend_Registry::get('config');
			$pathToTheProjectDir =$config['app']['storage']['project'].$this->project->dir.'/thumbs/';
				
				
			// setting upload file
      		$adapter->setDestination($pathToTheProjectDir);
           				$adapter->addValidator('Size', false, 4*10*102400)
    	  				->addValidator('Count', false, 1)
	      				->addValidator('Extension', false, 'jpg,jpeg,png');
				
			$files = array();
			$i= 1;
			foreach ($adapter->getFileInfo() as $file => $info) {
				// check if uploaded
				if (!$adapter->isUploaded($file)) {
					throw new \Exception("You haven't choose the file. Try it again :D.");
					break;
				}
					
				// validators are ok ?
				if (!$adapter->isValid($file)) {
					throw new \Exception("Please check the file: ".$info["name"] );
					break;
				}
					
				$ext = substr(strrchr($info['name'],'.'), 1);
				$pre = 'project'.sha1("s@4d".$user_id);
					
				// resolution path
				$path = $pathToTheProjectDir.$pre.'.'.$ext;
				$web_url = $config['app']['storage']['project_web'].$this->project->dir.'/thumbs/'.$pre.'.'.$ext;
				
				$adapter->addFilter('Rename',array('target' => $path,'overwrite' => true));
		
				// receiving files
				if(!$adapter->receive($file)){
					throw new \Exception("Can't upload file. Try it later please" );
					break;
				}
					
				//PROCESSING OF IMAGE
				$imageManager = new \Boilerplate_Util_ImageManager($path);
				$imageManager->resizeImage(Project::PROJECT_PHOTO_RESOLUTION_LARGE_WIDTH,Project::PROJECT_PHOTO_RESOLUTION_LARGE_HEIGHT,'crop');
				$imageManager->saveImage($path, 100);	
				$files[] = array('path'=> $path,'web_url'=>$web_url,'file'=> $pre.'_large.'.$ext);
				$i++; // increment file
			} // end foreach through all files
			
		
			// create new version resolutions for pictures
			$output = $this->bucket.'/projects/'.$this->project->dir.'/thumbs/';
			
			$oldExt = substr(strrchr($this->project->getPicture(),'.'), 1); // original extension, application can do png,jpeg,jpg
		
			//Create all resolutions and copy them to s3 server
			foreach($this->resolutionsProject as $r){
				$imagePath = $pathToTheProjectDir.$pre.'_'.$r['type'].'.'.$ext;
				$imageS3Path = $output.$pre.'_'.$r['type'].'.'.$ext; // addres to S3 server
				$imageManager->resizeImage($r['width'], $r['height'], 'crop');
				$imageManager->saveImage($imagePath, 100);
				
				// delete previous files if different extension for picture
				if($oldExt != $ext){
					$deleteImageS3Path = $output.$pre.'_'.$r['type'].'.'.$oldExt; // addres to S3 server
					$deleteImagePath = $pathToTheProjectDir.$pre.'_'.$r['type'].'.'.$oldExt;
					$this->s3->removeObject($deleteImageS3Path);
					if(is_file($deleteImagePath)){
						 			unlink($deleteImagePath);
				}
					
				}
			
				// upload to S3 server
				$this->s3->putFile($imagePath, $imageS3Path,
						array(Zend_Service_Amazon_S3::S3_ACL_HEADER =>
								Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ));
			}
			
			return $files[0];
		}
		
		
		
		/**
		 * Upload file from Project Board to S3 server
		 */
		function uploadFileToS3FromPost(){
			// check files
			$upload = new Zend_File_Transfer();
			$adapter = new Zend_File_Transfer_Adapter_Http();
			
			// setting upload file
			$adapter->setDestination($this->storageLocalProject.$this->project->dir);
			$adapter->addValidator('Size', false, 4*10*102400)
			->addValidator('Count', false, 5)
			->addValidator('Extension', false, 'pdf,doc,docx,odt,jpg,jpeg,png');
			
			// validate files
			$i= 1;
			foreach ($adapter->getFileInfo() as $file => $info) {
				// check if uploaded
				if ($adapter->isUploaded($file)) {
					// validators are ok ?
					if (!$adapter->isValid($file)) {
						throw new \Exception($info['name'] . " is not valid. ");
					}
				}
				$i++; // increment file
			}
			$files = array();
			
			// copying and renaming files
			$i= 1;
			foreach ($adapter->getFileInfo() as $file => $info) {
				if ($adapter->isUploaded($file)) {
				
					$file = $this->generateNewFileInformation($info['tmp_name'],$info['name'],$info['type'],$info['size']);					
					$output = $this->bucket.'/projects/'.$this->project->dir.'/pb/'.$file['file'];					
					$this->s3->putFile($file['temp'], $output,
							array(Zend_Service_Amazon_S3::S3_ACL_HEADER =>
									Zend_Service_Amazon_S3::S3_ACL_PRIVATE));
				 	$files[] = array('name'=>$info['name'],'type'=> $info['type'],'file'=> $file['file'],"size"=> $info['size']);
				}
				$i++; // increment file
			}
		
			return $files;
		}
	
		
		
		/**
		 * Creates thumbnaisl for user on S3 and on file storage
		 * @param unknown_type $user_id
		 */
		public function createThumbnailsForUser($user){
			$adapter = new Zend_File_Transfer_Adapter_Http(); 
		
			// setting upload file
			$adapter->setDestination($this->storageLocalUser);
			$adapter->addValidator('Size', false, 4*10*102400)
					->addValidator('Count', false, 1)
					->addValidator('Extension', false, 'jpg,jpeg,png')
					->addValidator('IsImage', false);
		
		// go trought all files in post	
		$i= 1;
		foreach ($adapter->getFileInfo() as $file => $info) {
		
			// check if uploaded
			if (!$adapter->isUploaded($file)) {
				throw new \Exception("You haven't choose the file. Try it again :D.");
				break;
			}
		
			// validators are ok ?
			if (!$adapter->isValid($file)) {
						throw new \Exception($info['name'] . " is not valid. ");
						break;
			}
			 
			
			// rename the file
			$ext = substr(strrchr($info['name'],'.'), 1);
			$fileName = 'profile'.sha1("s@4d".$user->id);
			 
			// copy file
			$path = $this->storageLocalUser.$fileName.'.'.$ext;
			$adapter->addFilter('Rename',array('target' => $path,'overwrite' => true));
			 
			// receiving files
			if(!$adapter->receive($file)){
				throw new \Exception("Can't upload the file. Please, try it later.");
				break;
			}
			
			$i++;
			 
		} // end foreach through all files
		//IMAGE PROCESSING
		$output = $this->bucket.'/users/';
		
		$imageManager = new \Boilerplate_Util_ImageManager($path); //open image
			
		// delete previous pictures
		$current = $user->getProfilePicture();
		$oldExt = substr(strrchr($current, '.'), 1);
		$pre = $fileName;
		//Create all resolutions and copy them to s3 server
		foreach($this->resolutionsUser as $r){
				$imagePath = $this->storageLocalUser.$pre.'_'.$r['type'].'.'.$ext;
				$imageS3Path = $output.$pre.'_'.$r['type'].'.'.$ext; // addres to S3 server
				$imageManager->resizeImage($r['width'], $r['height'], 'crop');
				$imageManager->saveImage($imagePath, 100);
					 
					// upload to S3 server
					 	$this->s3->putFile($imagePath, $imageS3Path,
					 			array(Zend_Service_Amazon_S3::S3_ACL_HEADER =>
					 					Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ));
					 	
					 	
					 	// delete previous files if different extension for picture
					 	if($oldExt != $ext){
					 		$deleteImageS3Path = $output.$pre.'_'.$r['type'].'.'.$oldExt; // addres to S3 server
					 		$deleteImagePath = $this->storageLocalUser.$pre.'_'.$r['type'].'.'.$oldExt;
					 		$this->s3->removeObject($deleteImageS3Path);
					 		if(is_file($deleteImagePath)){
					 			unlink($deleteImagePath);
					 		}
					 			
					 	}
				 
				}	 
		// delete original 
		unlink($this->storageLocalUser.$fileName.'.'.$ext); 
				
		return array("file"=>$fileName."_large.".$ext);
	}
		
}
