<?php

App::uses('Controller', 'Controller');

/**
* Application controller
*
* This file is the base controller of all other controllers
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

class Dg360Controller extends AppController {
/**
 * Components
 *
 * @var array
 * @access public
 */
 public $components = array(
       //'RequestHandler',
   'ImageResizer',
   'Bin', 'PhpExcel',
   'Cookie'
   );
/**
 * Models
 *
 * @var array
 * @access public
 */
    public $uses = array(

    );
    public function initialize()
    {
        parent::initialize();

    }


/**
 * Cache pagination results
 *
 * @var boolean
 * @access public
 */
    public $usePaginationCache = true;
	
/**
 * View
 *
 * @var string
 * @access public
 */
    //public $viewClass = 'Theme';
/**
 * Theme
 *
 * @var string
 * @access public
 */
    //public $theme;
     public $helpers = array('Xls', 'Bin');

 	 protected $paginate = array(

        'limit' => 	20,
		'order'	=>	'Obdanhgia.id DESC'
        );
		
	public function 	index()
  	{
	if(!$this->check_permission('360.DanhGia'))
			throw new InternalErrorException('Bạn không có quyền đánh giá và xem đánh giá 360. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
	  $username = $this->Auth->user('username');
      //  $ch = curl_init();
      //  curl_setopt($ch, CURLOPT_URL, "http://203.210.240.102:5013/api/user/hiennv");
      //  curl_setopt($ch, CURLOPT_HEADER, 0);
      //  $getAppreciatedUser = curl_exec($ch);
      //  curl_close($ch);
      //  echo var_dump($getAppreciatedUser);die();
      if($this->Cookie->read('360cookie') == '0' || is_null($this->Cookie->read('360cookie')))
      {
        $this->Cookie->write('360cookie', '1');
        $this->Cookie->write('return_url', '/dg360/index');
        $this->redirect($this->Auth->logout());
      }
      $this->set('title_for_layout', 'Đánh giá 360');
      $this->set('username',$username);
    }

    public function danhgia()
  	{
      //echo var_dump($this->Cookie->read('return_url'));die();
      if(!$this->check_permission('360.DanhGia'))
			throw new InternalErrorException('Bạn không có quyền đánh giá và xem đánh giá 360. Vui lòng liên hệ quản trị để biết thêm chi tiết.');

	  if($this->Cookie->read('360cookie') == '0' || is_null($this->Cookie->read('360cookie')))
      {
        $this->Cookie->write('360cookie', '1');
        $this->Cookie->write('return_url', '/dg360/index');
        $this->redirect($this->Auth->logout());
      }
	  $username = $this->Auth->user('username');


      $this->set('title_for_layout', 'Danh sách đánh giá');
      $this->set('username',$username);
    }

    public function export(){
      $jsonString = $_POST['data'];
      $data = json_decode($jsonString,true);
      require_once dirname(__FILE__) . '/../Vendor/PHPExcel.php';
      if(!class_exists('PHPExcel'))
        return;
      $objExcel = new PHPExcel();
      $objExcel->getProperties()->setTitle("Danh sách đánh giá");

      $objExcel->setActiveSheetIndex(0);
      //Set title at 1st row
      $objExcel->getActiveSheet()
                  ->setCellValue('A1', "ID")
                  ->setCellValue('B1', "Người đánh giá")
                  ->setCellValue('C1', "Người được đánh giá")
                  ->setCellValue('D1', "Phòng ban")
                  ->setCellValue('E1', "Câu hỏi")
                  ->setCellValue('F1', "Ngày đánh giá")
                  ->setCellValue('G1', "Điểm");
      $r = 2;
      foreach($data as $item)
      {
        $objExcel->getActiveSheet()
                    ->setCellValue("A$r", $item["ID"])
                    ->setCellValue("B$r", $item["appreciate"])
                    ->setCellValue("C$r", $item["appreciated"])
                    ->setCellValue("D$r", $item["group"])
                    ->setCellValue("E$r", $item["question"])
                    ->setCellValue("F$r", $item["date"])
                    ->setCellValue("G$r", $item["mark"]);
        $r++;
      }


      $filename = 'export_' . date('dmyHis') . '.xls';
      $file_path = realpath(dirname(__FILE__) . '/../tmp') . '/' . $filename;
      $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
      $objWriter->save($file_path);
      echo $filename;
      exit();
    }

    public function download() {
      $file_name = rawurldecode($_REQUEST['file']);
      if(empty($file_name))
      {
        header("HTTP/1.0 404 Not Found");
        exit();
      }
      $filepath = realpath(dirname(__FILE__) . '/../tmp') . '/' . $file_name;
      if(!file_exists($filepath))
      {
        header("HTTP/1.0 404 Not Found");
        exit();
      }
      header("Content-Type: application/xls");
      header('Content-Disposition: attachment; filename="'.$file_name.'"');
      //header('Content-Length: ' . filesize($filepath));
      $file_handler = fopen($filepath,"rb");
      $content = fread($file_handler,filesize($filepath));
      fclose($file_handler);
      unlink($filepath);
      echo $content;
      exit();
    }

	//////////
	/* OB đánh gia TTĐH */
	public	function	ob_ttdh()
	{
		if(!$this->check_permission('OB.danhgia') || ($this->check_permission('HeThong.toanquyen')))
			throw new InternalErrorException('Bạn không có quyền đánh giá OB. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->loadModel('Obdanhgia');
		$month = date("m",time());
		$danh_gia = $this->Obdanhgia->field('ngay_danhgia', array('nhanvien_id' => $this->Auth->user('nhanvien_id'),'MONTH(ngay_danhgia)' => $month));
		if(!empty($danh_gia))
		{
			$this->Session->setFlash('Tháng này bạn đã đánh giá xong. Vui lòng xem kết quả bên dưới.', 'flash_attention');
			$this->redirect('/dg360/view_result');
		}

		$this->Obdanhgia->bindModel(array(
			'hasMany'	=>	array(
				'Obchitiet'	=>	array('foreignKey'	=>	'ketqua_id')
			)
		));
		$f = false;
		if (!empty($this->request->data))
		{

			App::uses('Sanitize', 'Utility');
			$this->request->data['Obdanhgia']['nhanvien_id'] 	= $this->Auth->user('nhanvien_id');
			$this->request->data['Obdanhgia']['donviduocdanhgia_id'] 	= 15; // Trung tâm Điều hành thông tin
			$this->request->data['Obdanhgia']['ket_qua'] 	= $this->request->data['ket_qua'];
			$this->request->data['Obdanhgia']['ngay_danhgia']		= date("Y-m-d");
			if(!empty($this->request->data['Obdanhgia']['lydo_id']))
				$ly_do = $this->request->data['Obdanhgia']['lydo_id'];
			$this->request->data['Obchitiet'] = array();
			if(!empty($ly_do))
			{
				foreach($ly_do as $n)
				{
					array_push($this->request->data['Obchitiet'], array('lydo_id' => $n));
				}
			}
			//pr($this->request->data);die();

			if ($this->Obdanhgia->saveAssociated($this->request->data))

				$f = true;
			else
				$f = false;
			//pr($f);die();
			if ($f)
			{
				$this->Session->setFlash('Gửi nhận xét thành công.', 'flash_success');
				$this->redirect('/dg360/view_result');

            } else {

				$this->Session->setFlash('Đã phát sinh lỗi khi gửi nhận xét. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/dg360/ob_ttdh');

            }
        }
		$this->loadModel('Oblydo');
		$ly_do = $this->Oblydo->find('all', array('fields' => array('id', 'ten_lydo')));
		$this->set(compact('ly_do'));
	}
	public	function	view_result()
	{
		if(!$this->check_permission('OB.xemketqua')|| ($this->check_permission('HeThong.toanquyen')))
			throw new InternalErrorException('Bạn không có quyền xem kết quả đánh giá OB. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->loadModel('Obdanhgia');
		$this->Obdanhgia->bindModel(array(
			'hasMany'	=>	array(
				'Obchitiet'	=>	array('foreignKey'	=>	'ketqua_id')
			)
		));
		$this->Obdanhgia->Obchitiet->bindModel(array(
			'belongsTo'	=>	array('Oblydo' => array('foreignKey' => 'lydo_id')
							),
	 	), false);
		$filters = array();
		$filters['Obdanhgia.nhanvien_id']	= $this->Auth->user('nhanvien_id');
		$this->Obdanhgia->recursive = 2;
		$ds =  $this->paginate('Obdanhgia', $filters);
		//pr($ds);die();
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
	}
	public	function	ob_satis()
	{
		if(!$this->check_permission('OB.satis')|| ($this->check_permission('HeThong.toanquyen')))
			throw new InternalErrorException('Bạn không có quyền xem báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		if(!empty($this->request->data))
		{
			$this->loadModel('Obdanhgia');
			$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
			$end = $this->Bin->vn2sql($this->request->data['den_ngay']);
			$data = $this->Obdanhgia->query("
				SELECT count(A.id) as total,
					   count(case when A.ket_qua = 1 then 1 end) as total_satis,
					   (count(case when A.ket_qua = 1 then 1 end)/count(A.id))* 100 as ty_le
				FROM ob_ketqua A
				WHERE A.ngay_danhgia BETWEEN '" . $start . "' AND '" . $end . "'
			");
			if(empty($data))
			{
				$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
			}
			$this->set('data', $data);
			$this->render('obsatis_chitiet');
		}
	}
	public	function	ob_unsatis()
	{
		if(!$this->check_permission('OB.unsatis') || ($this->check_permission('HeThong.toanquyen')))
			throw new InternalErrorException('Bạn không có quyền xem báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		if(!empty($this->request->data))
		{
			$this->loadModel('Obdanhgia');
			$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
			$end = $this->Bin->vn2sql($this->request->data['den_ngay']);
			$data = $this->Obdanhgia->query("
				SELECT B.ID,B.TEN_LYDO, B.SL AS SO_LUONG,
				   ROUND(B.SL * 100 /
						 (SELECT COUNT(*)
							FROM ob_ketqua A
						   INNER JOIN ob_ketquachitiet B
							  ON A.ID = B.KETQUA_ID
						   INNER JOIN ob_lydo C
							  ON B.LYDO_ID = C.ID
						   WHERE A.NGAY_DANHGIA BETWEEN '" . $start . "' AND '" . $end . "'
							 AND A.KET_QUA = 0),
						 2) AS TYLE
			  FROM (SELECT C.ID, COUNT(*) AS SL, C.TEN_LYDO
					 FROM ob_ketqua A
					 INNER JOIN ob_ketquachitiet B
						ON A.ID = B.KETQUA_ID
					 INNER JOIN ob_lydo C
						ON B.LYDO_ID = C.ID
					 WHERE A.NGAY_DANHGIA BETWEEN '" . $start . "' AND '" . $end . "'
					   AND A.KET_QUA = 0
					 GROUP BY C.ID) B
			");
			$total = $this->Obdanhgia->query("
				SELECT SUM(C.SO_LUONG) AS TOTAL
				FROM
				(SELECT B.ID,B.TEN_LYDO, B.SL AS SO_LUONG,
				   ROUND(B.SL * 100 /
						 (SELECT COUNT(*)
							FROM ob_ketqua A
						   INNER JOIN ob_ketquachitiet B
							  ON A.ID = B.KETQUA_ID
						   INNER JOIN ob_lydo C
							  ON B.LYDO_ID = C.ID
						   WHERE A.NGAY_DANHGIA BETWEEN '" . $end . "' AND '" . $end . "'
							 AND A.KET_QUA = 0),
						 2) AS TYLE
			  FROM (SELECT C.ID, COUNT(*) AS SL, C.TEN_LYDO
					 FROM ob_ketqua A
					 INNER JOIN ob_ketquachitiet B
						ON A.ID = B.KETQUA_ID
					 INNER JOIN ob_lydo C
						ON B.LYDO_ID = C.ID
					 WHERE A.NGAY_DANHGIA BETWEEN '" . $start . "' AND '" . $end . "'
					   AND A.KET_QUA = 0
					 GROUP BY C.ID) B ) C
			");
			//pr($data);die();
			if(empty($data))
			{
				$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
			}
			$this->set('data', $data);
			$this->set('total', $total);
			$this->render('obunsatis_chitiet');
		}
	}

	public	function	satis_excel()
	{
		if(!$this->check_permission('OB.satis') || ($this->check_permission('HeThong.toanquyen')))
			throw new InternalErrorException('Bạn không có quyền xuất báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->layout = null;
		$this->loadModel('Obdanhgia');
		$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
		$end = $this->Bin->vn2sql($this->request->data['den_ngay']);

		$data = $this->Obdanhgia->query("
			SELECT count(A.id) as total,
					   count(case when A.ket_qua = 1 then 1 end) as total_satis,
					   (count(case when A.ket_qua = 1 then 1 end)/count(A.id))* 100 as ty_le
				FROM ob_ketqua A
				WHERE A.ngay_danhgia BETWEEN '" . $start . "' AND '" . $end . "'
		");
		$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);
		$this->PhpExcel->setActiveSheetIndex(0);
		$sheet = $this->PhpExcel->getActiveSheet();

		// style

		$header_style = array(
			'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
			'font' =>	array(
					'bold'	=>	true,
					'size'	=>	16
				)
		);

		$table_header_style = array(
			'font' =>	array(
					'bold'	=>	true
				),

		);
		$sheet->mergeCells('A1:C1');
		$sheet->mergeCells('A2:C2');
		$sheet->getStyle('A1:A2')->applyFromArray($header_style);

		$sheet->setCellValue('A1', 'BÁO CÁO ĐỘ HÀI LÒNG');
		$sheet->setCellValue('A2', sprintf('Từ ngày %s đến ngày %s', $this->request->data['tu_ngay'], $this->request->data['den_ngay']));


		$this->PhpExcel->setRow(3);
		// define table cells
		$table = array(
			array('label' => __('Tổng số ý kiến'), 'filter' => true),
			array('label' => __('Số ý kiến hài lòng'), 'filter' => true),
			array('label' => __('Tỷ lệ hài lòng(%)'), 'filter' => true),
			array('label' => __('Tỷ lệ không hài lòng(%)'), 'filter' => true),
		);

		// add heading with different font and bold text
		$this->PhpExcel->addTableHeader($table, array('bold' => true));
			$this->PhpExcel->addTableRow(array(
				$data['0']['0']['total'],
				$data['0']['0']['total_satis'],
				number_format($data['0']['0']['ty_le'],1),
				100 - number_format($data['0']['0']['ty_le'],1)
			));
		// close table and output
		$this->PhpExcel->addTableFooter();

		$this->PhpExcel->output('ob_satis.xlsx');
	}
	public	function	unsatis_excel()
	{
		if(!$this->check_permission('OB.unsatis') || ($this->check_permission('HeThong.toanquyen')))
			throw new InternalErrorException('Bạn không có quyền xuất báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->layout = null;
		$this->loadModel('Obdanhgia');
		$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
		$end = $this->Bin->vn2sql($this->request->data['den_ngay']);

		$data = $this->Obdanhgia->query("
				SELECT B.ID,B.TEN_LYDO, B.SL AS SO_LUONG,
				   ROUND(B.SL * 100 /
						 (SELECT COUNT(*)
							FROM ob_ketqua A
						   INNER JOIN ob_ketquachitiet B
							  ON A.ID = B.KETQUA_ID
						   INNER JOIN ob_lydo C
							  ON B.LYDO_ID = C.ID
						   WHERE A.NGAY_DANHGIA BETWEEN '" . $start . "' AND '" . $end . "'
							 AND A.KET_QUA = 0),
						 2) AS TYLE
			  FROM (SELECT C.ID, COUNT(*) AS SL, C.TEN_LYDO
					 FROM ob_ketqua A
					 INNER JOIN ob_ketquachitiet B
						ON A.ID = B.KETQUA_ID
					 INNER JOIN ob_lydo C
						ON B.LYDO_ID = C.ID
					 WHERE A.NGAY_DANHGIA BETWEEN '" . $start . "' AND '" . $end . "'
					   AND A.KET_QUA = 0
					 GROUP BY C.ID) B
			");
		$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);
		$this->PhpExcel->setActiveSheetIndex(0);
		$sheet = $this->PhpExcel->getActiveSheet();

		// style

		$header_style = array(
			'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
			'font' =>	array(
					'bold'	=>	true,
					'size'	=>	16
				)
		);

		$table_header_style = array(
			'font' =>	array(
					'bold'	=>	true
				),

		);
		$sheet->mergeCells('A1:C1');
		$sheet->mergeCells('A2:C2');
		$sheet->getStyle('A1:A2')->applyFromArray($header_style);

		$sheet->setCellValue('A1', 'BÁO CÁO CÁC LÝ DO KHÔNG HÀI LÒNG');
		$sheet->setCellValue('A2', sprintf('Từ ngày %s đến ngày %s', $this->request->data['tu_ngay'], $this->request->data['den_ngay']));


		$this->PhpExcel->setRow(3);
		// define table cells
		$table = array(
			array('label' => __('Lý do không hài lòng'), 'filter' => true),
			array('label' => __('Số lượng'), 'filter' => true),
			array('label' => __('Tỷ lệ(%)'), 'filter' => true),
		);

		// add heading with different font and bold text

		$this->PhpExcel->addTableHeader($table, array('bold' => true));
		//pr($data);die();
		foreach($data as $d)
		{
			$this->PhpExcel->addTableRow(array(
				$d['B']['TEN_LYDO'],
				$d['B']['SO_LUONG'],
				number_format($d[0]['TYLE'],1)
			));
		}
		// close table and output
		$table_footer = array(
			array('label' => __('Tổng'), 'filter' => true),
			array('label' => __('')),
			array('label' => __('100%'), 'filter' => true),
		);

		$this->PhpExcel->addTableHeader($table_footer, array('bold' => true));
		//$this->PhpExcel->addTableFooter($table_footer, array('bold' => true));
		$this->PhpExcel->addTableFooter();

		$this->PhpExcel->output('ob_unsatis.xlsx');
	}

	public function alertOB()
	{
		$timeinfo = getdate();
    	$lastDayofMonth = date('t');
		$this->loadModel('Nhanvien');
		$this->Nhanvien->bindModel(array(
						'belongsTo'=>array('Phong'=>array('fields'=>array('thuoc_phong')))
					));
		$this->Nhanvien->UnbindModel(array(
						'hasAndBelongsToMany'	=>	array('Nhomquyen')
					));
		$nv_tonghop = $this->Nhanvien->find('all', array('conditions' => array('phong_id IN (54,59,64,72,78)','nguoi_quanly' => 0,'tinh_trang' => 1, 'Phong.loai_donvi <>' => 2,'Phong.thuoc_phong IN  (24,25,26,27,28)'), 'fields' => 'id'));
		//pr($nv_tonghop); die();
		$ds_nvtonghop = array();
		foreach($nv_tonghop as $nv)
		{
			array_push($ds_nvtonghop,$nv['Nhanvien']['id']);
		}
		$d = in_array($this->Auth->User('nhanvien_id'),$ds_nvtonghop);
		//var_dump($d);die();
		if($d || ( !$this->check_permission('OB.danhgia') 
			 && !in_array($this->Auth->User('chucdanh_id'), array('30','31'))
			 && !in_array($this->Auth->User('donvi_id'), array('24','25','26','27','28')) )
             || $timeinfo['mday'] < $lastDayofMonth - 5 ){
		  $alertOB = array(
			'nv' => array(
			  'num' => 0,
			  'ds' => null
			),
			'tt' => array(
			  'num' => 0,
			  'ds' => array()
			),
			'gd' => array(
			  'num' => 0,
			  'ds' => array()
			),
			'day' => $timeinfo['mday'],
      'lastday' => $lastDayofMonth
		  );
		  header('Content-Type: text/plaintext');
			echo json_encode($alertOB);
			die();
		}

		$alertOB = array();
		$this->loadModel('Obdanhgia');
		$ds_tennhanvien = array();
		$ten_phong = array();
		$month = date("m",time());
		$allnv_danhgia = $this->Obdanhgia->find('all', array('conditions' => array('MONTH(ngay_danhgia)' => $month),
															   'fields' => 'nhanvien_id'));
		$ds_danhgia = array();
		$alertOB_nhanvien = 0;
		$alertOB_totruong = 0;
		$alertOB_giamdoc = 0;
		if(!empty($allnv_danhgia))
		{
			foreach($allnv_danhgia as $n)
			{
				foreach($n as $m)
				{
				array_push($ds_danhgia, $m['nhanvien_id']);
				}
			}
			$this->Nhanvien->UnbindModel(array(
						'hasAndBelongsToMany'	=>	array('Nhomquyen')
					));
			//// Trung tâm VT, loại trừ BGĐ (có 2 PGĐ kiêm Tổ trưởng Tổ tổng hợp)
			if((in_array($this->Auth->User('nhanvien_id'),array('632','573')))
	 			|| !in_array($this->Auth->user('chucdanh_id'),array('30','31')) && in_array($this->Auth->user('donvi_id'),array('24','25','26','27','28')))
			{
				$nv_danhgia = $this->Obdanhgia->field('ngay_danhgia', array('nhanvien_id' => $this->Auth->user('nhanvien_id'),'MONTH(ngay_danhgia)' => $month));
				if(empty($nv_danhgia))
					$alertOB_nhanvien = 1;
			}
			// Là Tổ trưởng Tổ KTVT
			if($this->Auth->user('chucdanh_id') == 28 && in_array($this->Auth->user('donvi_id'),array('24','25','26','27','28')) &&  !in_array($this->Auth->user('nhanvien_id'),array('445','580','503')))
			{
				$nv_to = $this->Nhanvien->find('all', array('conditions' => array('phong_id' => $this->Auth->user('phong_id'),'tinh_trang' => 1,'Nhanvien.id <>' => $this->Auth->user('nhanvien_id')), 'fields' => 'id'));
				$ds_nhanviento = array();
				foreach($nv_to as $k)
				{
					foreach($k as $i)
					{
					array_push($ds_nhanviento, $i['id']);
					}
				}
				//Kiểm tra ds nhân viên thuộc tổ đã đánh giá trong tháng hết chưa?
				$j_to = 0;
				$ds_nvchuadanhgia = array();
				foreach($ds_nhanviento as $ds)
				{
					if(!(in_array($ds,$ds_danhgia))) {
						$j_to++;
						array_push($ds_nvchuadanhgia,$ds);
					}
				}
				$alertOB_totruong = $j_to;

				foreach($ds_nvchuadanhgia as $d)
				{
					$ten_nhanvien = $this->User->find('all', array('conditions' => array('Nhanvien.id' => $d), 'fields' => 'User.fullname'));
					array_push($ds_tennhanvien,$ten_nhanvien);
				}
				/*pr($alertOB_totruong);
				pr($ds_tennhanvien);
				die();*/
			}
			// Là Giám đốc, Phó GĐ TTVT
			if(in_array($this->Auth->user('chucdanh_id'),array(30,31)) && in_array($this->Auth->user('donvi_id'),array('24','25','26','27','28') ))
			{
				//Nhân viên thuộc TTVT có quyền đánh giá

				$nv_trungtam = $this->Nhanvien->query("
												SELECT A.id 
												FROM nhansu_nhanvien A JOIN nhansu_phong B ON A.phong_id = B.id 
																AND B.thuoc_phong = '" . $this->Auth->user('donvi_id') . "'
																JOIN sys_nhanvien_nhomquyen C ON A.ID = C.nhanvien_id 
																AND C.nhomquyen_id = 52
												WHERE A.tinh_trang = 1 "
				
				);
				//pr($nv_trungtam);die();
				$ds_nvtrungtam = array();
				foreach($nv_trungtam as $k)
				{
					foreach($k as $i)
					{
					array_push($ds_nvtrungtam, $i['id']);
					}
				}
				//Kiểm tra ds nhân viên thuộc Trung tâm đã đánh giá trong tháng hết chưa?
				$ds_nvchuadanhgia = array();
				foreach($ds_nvtrungtam as $ds)
				{
					if(!(in_array($ds,$ds_danhgia)))
					{
						array_push($ds_nvchuadanhgia,$ds); 
					}
				}
				$ds_nvchuadanhgia_str ="(".implode(",",$ds_nvchuadanhgia).")";
				$this->loadModel('Phong');
				$ten_phong = $this->Phong->query("
												SELECT A.ten_phong FROM nhansu_phong A JOIN nhansu_nhanvien B ON A.id = B.phong_id
																						AND B.id IN  " . $ds_nvchuadanhgia_str . "
												WHERE A.thuoc_phong  = '" . $this->Auth->user('donvi_id') . "'
												GROUP BY A.id
												ORDER BY A.lft
											");
				$alertOB_giamdoc = count($ten_phong);

			}
		}
		else {
        $alertOB_nhanvien = 1;
        $alertOB_totruong = 99;
        $alertOB_giamdoc = 99;


    }
	
    $alertOB = array(
      'nv' => array(
        'num' => $alertOB_nhanvien,
        'ds' => null
      ),
      'tt' => array(
        'num' => $alertOB_totruong,
        'ds' => $ds_tennhanvien
      ),
      'gd' => array(
        'num' => $alertOB_giamdoc,
        'ds' => $ten_phong
      ),
      'day' => $timeinfo['mday'],
      'lastday' => $lastDayofMonth
    );
		//pr($alertOB);die();
		//pr($alertOB);die();
    header('Content-Type: text/plaintext');
		echo json_encode($alertOB);
		die();

    }

	/////////
  // Lấy DS người chưa đánh giá hoặc phòng chưa đánh giá



}



?>
