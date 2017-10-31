<?php 
App::uses('BinComponent', 'Controller/Component');
class FileManager extends AppModel {

	var $name = 'FileManager';
	var $validate = array(
		'file' => array(
			'rule' => array(
				'validFile',
				array(
					'required' => true,
					'extensions' => array(
						'jpg', 'jpeg', 'gif', 'png',
						'swf', 'flv', 'avi', 'wmv', 'mp3', 'wma',
						'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', 'ppt', 'pptx',						
						'tif', 'tiff', 'pdf'
					)
				)
			)
		)
		
	);
    var $useTable = false;
	
	
	var $error = false;

    function readFolder($folderName = null) 
	{
        $folder = new Folder(APP . WEBROOT_DIR . DS . $folderName);
        //$files = $folder->read(true, array('.', '..', 'Thumbs.db'), true);
		$files = $folder->read();
        return $files;
    }

    function upload($data = null, $destFolder = null) 
	{
        $this->set($data);
		
        if(empty($this->data)) {
			$this->error = "Lỗi khi upload file.";
            return false;
        }

        // Validation
        if(!$this->validates()) 
		{
			$this->error = 'Chỉ được phép upload các file *.jpg, *.jpeg, *.gif, *.png, *.swf, *.flv, *.avi, *.wmv, *.wma, *.mp3, *.doc, *.docx, *.xls, *.xlsx, *.ppt, *.pptx, *.zip, *.rar, *.tif, *.tiff, *.pdf';
            return false;
        }
		
		$t = explode(".", $data['FileManager']['file']['name']);
		$ext = end($t);
		
		// slug filename
		//$filename = date("YmdHis") . '_' . BinComponent::slug(basename($data['FileManager']['file']['name'], $ext)) . '.' . strtolower($ext);
		//$filename = BinComponent::slug(basename($data['FileManager']['file']['name'], $ext)) . '.' . strtolower($ext);
        // Move the file to the uploads folder
		$ten_moi = md5(time() + rand());
		if(!move_uploaded_file($data['FileManager']['file']['tmp_name'], APP . WEBROOT_DIR . DS . $destFolder . $ten_moi)) 
		{
			$this->error = "Lỗi khi upload file " . $ten_moi;
			return false;
		}

        return $ten_moi;
    }
	
	
	function	create_dir($data = null)
	{
		if(empty($data))	return false;
		
		if(file_exists(APP . WEBROOT_DIR . DS . $data['FileManager']['path'] . $data['FileManager']['dir_name']))
		{
			return false;
		}
		
		$this->folder = new Folder;
		if ($this->folder->create(APP . WEBROOT_DIR . DS . $data['FileManager']['path'] . $data['FileManager']['dir_name']))
		{
			return true;
		}
		else
		{
			return false;
		}
			
	}



    function validFile($check, $settings) {
    	$_default = array(
    		'required' => false,
    		'extensions' => array(
    			'jpg',
    			'jpeg',
    			'gif',
    			'png'
    		)
    	);

    	$_settings = array_merge(
    		$_default,
    			is_array($settings) ? $settings : array()
    	);

		// Remove first level of Array
		$_check = array_shift($check);

		if($_settings['required'] == false && $_check['size'] == 0) {
			return true;
        }

        // No file uploaded.
        if($_settings['required'] && $_check['size'] == 0) {
			return false;
        }

        // Check for Basic PHP file errors.
        if($_check['error'] !== 0) {
			return false;
        }

        // Use PHPs own file validation method.
        if(is_uploaded_file($_check['tmp_name']) == false) {
        	return false;
        }

        // Valid extension
        return Validation::extension(
        	$_check,
        	$_settings['extensions']
        );
	}
}
?>