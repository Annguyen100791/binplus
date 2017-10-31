<?php
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');

/**
 * FileManager controller
 *
 * Controller quản lý file và thư mục
 *
 * PHP version 5
 *
 * @category Controllers
 * @package  BIN
 * @version  1.0
 * @author   Thạnh Nguyễn <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.ptc.com.vn
 */
 
class FileManagersController extends AppController {
	
	var $name = 'FileManagers';

    var $uses = array('FileManager');

    var $helpers = array(
		'FileManager',
		'Number'
    );
	
	function browse() 
	{
		$this->layout = null;
        $this->folder = new Folder;
		
		$root = DS . 'uploads';
		
		//pr($this->request); 
        if (isset($this->request->query['path']) && !empty($this->request->query['path'])) 
		{
			$pos = strpos($this->request->query['path'], $this->Auth->user('username') . DS);
			
			if($pos !== false && $pos == 0)
			{
				$path = $this->request->query['path'];
			}else
	            $path = $this->Auth->user('username') .  DS . $this->request->query['path'];
        } else {
            $path = $this->Auth->user('username') .  DS;
        }
		if(!file_exists(APP . WEBROOT_DIR . $root . DS . $this->Auth->user('username')))
		{
			$data['FileManager']['path']  = 'uploads' . DS;
			$data['FileManager']['dir_name'] = $this->Auth->user('username');
			$this->FileManager->create_dir($data);
			$path = $this->Auth->user('username') .  DS;
		}
			
		if(!file_exists(APP . WEBROOT_DIR . $root . DS . $path))
		{
			$path = $this->Auth->user('username') .  DS;
		}
        $this->folder->path = $path;

        $this->set(
            'files',
            $this->FileManager->readFolder($root . DS . $path)
        );
		
		$this->set('root', $root);
        $this->set('path', $path);
		
    }
	
	function	upload()
	{
		// FileManager an image
        if (!empty($this->request->data)) 
		{
			$path = '';
			$root = 'uploads';
			
			if(!empty($this->request->data['FileManager']['path']))
			{
				$pos = strpos($this->request->data['FileManager']['path'], $this->Auth->user('username') . DS);
				
				if($pos !== false && $pos == 0)
				{
					$path = $this->request->data['FileManager']['path'];
				}else
					$path = $this->Auth->user('username') .  DS . $this->request->data['FileManager']['path'];
			} else {
				$path = $this->Auth->user('username') .  DS;
			}
			
			//$this->request->data['FileManager']['file']['path'] = $root . DS . $path;
            // Validate and move the file
			$filename = $this->FileManager->upload($this->request->data, $root . DS . $path);
            if($filename !== false) {
                $this->Session->setFlash('File ' . $filename . 'đã được upload thành công.');
            } else {
                $this->Session->setFlash('Đã phát sinh lỗi khi upload file lên server.');
            }
            
            $this->redirect('/file_managers/browse/?path=' . urlencode($path));
        } else {
            $this->redirect('/file_managers/browse/');
        }
	}
	
	function	create_dir()
	{
		if(!empty($this->request->data))
		{
			$root = 'uploads';
			$path = '';
			if(empty($this->request->data['FileManager']['path']))
				$path = $this->Auth->user('username') . DS;
			else
			{
				$pos = strpos($this->request->data['FileManager']['path'], $this->Auth->user('username') . DS);
				if($pos !== false && $pos == 0)
				{
					$path = $this->request->data['FileManager']['path'];
				}else
					$path = $this->Auth->user('username') .  DS . $this->request->data['FileManager']['path'];
			}
			$this->request->data['FileManager']['path'] = $root . DS . $path;
			
			if(empty($this->request->data['FileManager']['dir_name']))
				$this->redirect('/file_managers/browse/?path=' . urlencode($path));
			
            $this->FileManager->create_dir($this->request->data);
			$this->redirect('/file_managers/browse/?path=' . $this->request->data['FileManager']['path']);
		}
	}
	
	function	delete_file()
	{
		$this->layout = null;
		$root = 'uploads';
		if (isset($this->request->query['path']) && isset($this->request->query['file'])) 
		{
            $path = $this->request->query['path'];
			$file = $this->request->query['file'];
        } else {
            $this->redirect(array('controller' => 'file_managers', 'action' => 'browse'));
        }
		
		$pos = strpos($path, $this->Auth->user('username') . DS);
		
		if($pos !== false && $pos == 0)
		{
			$full_path = str_replace(DS, '/', $root . DS . $path . $file);
			if (file_exists($full_path) && unlink($full_path)) 
			{
				$this->Session->setFlash('File đã được xóa thành công.');
			} else {
				$this->Session->setFlash('Đã phát sinh lỗi khi xóa file.');
			}
		}else
			$this->Session->setFlash('Không được phép xóa file trong thư mục này.');
        $this->redirect('/file_managers/browse?path=' . $path);
	}
	
	function	delete_dir()
	{
		$this->layout = null;
		$root = 'uploads';
		if (isset($this->request->query['path']) && isset($this->request->query['dir'])) 
		{
            $path = $this->request->query['path'];
			$dir = $this->request->query['dir'];
        } else {
            $this->redirect(array('controller' => 'file_managers', 'action' => 'browse'));
        }
		
		$pos = strpos($path, $this->Auth->user('username') . DS);
		
		if($pos !== false && $pos == 0)
		{
			$full_path = str_replace(DS, '/', $root . DS . $path . $dir);
			if (is_dir($full_path) && rmdir($full_path)) {
				$this->Session->setFlash('Thư mục đã được xóa thành công.');
			} else {
				$this->Session->setFlash('Đã phát sinh lỗi khi xóa thư mục.');
			}
		}else
			$this->Session->setFlash('Không được phép xóa thư mục này.');
        $this->redirect('/file_managers/browse?path=' . $path);
	}
	
}