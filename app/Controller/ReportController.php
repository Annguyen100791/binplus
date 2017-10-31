<?php
/**
 * Report controller
 *
 * controller dành cho đối tượng chức danh (danh mục chức danh)
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

class ReportController extends AppController {
	
	public $helpers = array('Xls', 'Bin');

	public $components = array('Bin', 'PhpExcel');
	
	public	function	signin()
	{
		if(!$this->check_permission('ThongKe.luottruycap'))
			throw new InternalErrorException('Bạn không có xem báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Thống kê tình hình truy cập hệ thống');
			$this->render('signin');
		}else
		{
			$this->loadModel('User');
			
			$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
			$end = $this->Bin->vn2sql($this->request->data['den_ngay']);
			
			$start = $start . ' 00:00:00';
			$end = $end . ' 23:59:59';
			
			$data = $this->User->query("
				SELECT A.ten_phong, CONCAT(B.ho,' ', B.ten_lot, ' ', B.ten) as ho_ten, count(C.nhanvien_id) as total, max(C.signin_date) as last_signin_date
				FROM nhansu_phong A, nhansu_chucdanh D, nhansu_nhanvien B LEFT JOIN signin_histories C ON C.nhanvien_id = B.id AND C.signin_date BETWEEN '" . $start . "' AND '" . $end . "'
				WHERE A.id = B.phong_id AND D.id = B.chucdanh_id
				
				GROUP BY CONCAT(B.ho,' ', B.ten_lot, ' ', B.ten)
				ORDER BY A.lft ASC, D.thu_tu ASC
			");
			$this->set('data', $data);
			$this->render('signin_result');
		}
		
	}
	
	public	function	signin_nhanvien()
	{
		if(!$this->check_permission('ThongKe.luottruycap'))
			throw new InternalErrorException('Bạn không có xem báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		//pr($this->Session->read('Auth'));die();
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Thống kê chi tiết việc truy cập website của Nhân viên' . $this->Session->read('Auth.User.fullname'));
			$this->render('signin_nhanvien');
		}else
		{
			$this->loadModel('User');
			
			$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
			$end = $this->Bin->vn2sql($this->request->data['den_ngay']);
			
			$start = $start . ' 00:00:00';
			$end = $end . ' 23:59:59';
			$data = $this->User->query("
									SELECT signin_date  
									FROM signin_histories
									WHERE nhanvien_id = " . $this->Session->read('Auth.User.id') . "
											AND signin_date BETWEEN '" . $start . "' AND '" . $end . "'
									
									ORDER BY signin_date ASC
			");
			//pr($data);die();
			$this->set('data', $data);
			$this->render('signin_nhanvien_result');
		}
		
	}
	
	public	function	signin_excel()
	{
		if(!$this->check_permission('ThongKe.luottruycap'))
			throw new InternalErrorException('Bạn không có xem báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		$this->layout = null;
			
		$this->loadModel('User');
			
		$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
		$end = $this->Bin->vn2sql($this->request->data['den_ngay']);
		
		$start = $start . ' 00:00:00';
		$end = $end . ' 23:59:59';
		
		$data = $this->User->query("
			SELECT A.ten_phong as ten_phong, CONCAT(B.ho,' ', B.ten_lot, ' ', B.ten) as ho_ten, count(C.nhanvien_id) as total, max(C.signin_date) as last_signin_date
			FROM nhansu_phong A, nhansu_chucdanh D, nhansu_nhanvien B LEFT JOIN signin_histories C ON C.nhanvien_id = B.id AND C.signin_date BETWEEN '" . $start . "' AND '" . $end . "'
			WHERE A.id = B.phong_id AND D.id = B.chucdanh_id
			
			GROUP BY CONCAT(B.ho,' ', B.ten_lot, ' ', B.ten)
			ORDER BY A.lft ASC, D.thu_tu ASC
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
		$sheet->mergeCells('A1:E1');
		$sheet->mergeCells('A2:E2');
		$sheet->getStyle('A1:A2')->applyFromArray($header_style);

		$sheet->setCellValue('A1', 'THỐNG KÊ LƯỢT TRUY CẬP BINPLUS');
		$sheet->setCellValue('A2', sprintf('Từ ngày %s đến ngày %s', $this->request->data['tu_ngay'], $this->request->data['den_ngay']));
		
		
		$this->PhpExcel->setRow(5);
		// define table cells
		$table = array(
			array('label' => __('STT'), 'filter' => false),
			array('label' => __('Họ tên'), 'filter' => true),
			array('label' => __('tổng số lần truy cập'), 'filter' => true),
		);
		
		// add heading with different font and bold text
		$this->PhpExcel->addTableHeader($table, array('bold' => true));
		
		// add data
		$ten_phong = '';
		$stt = 1;
		foreach ($data as $d) {
			if(empty($ten_phong) || $ten_phong != $d['A']['ten_phong'])
			{
				$ten_phong = $d['A']['ten_phong'];
				$stt = 1;
				$this->PhpExcel->addTableRow(array(
					'',
					$ten_phong,
					'',
				));
			}
				
			$this->PhpExcel->addTableRow(array(
				$stt++,
				$d['0']['ho_ten'],
				$d['0']['total'],
			));
		}
		// close table and output
		$this->PhpExcel->addTableFooter();
		
		$this->PhpExcel->output('luottruycap.xlsx');
	}
	
	public	function	signin_nhanvien_excel()
	{
		if(!$this->check_permission('ThongKe.luottruycap'))
			throw new InternalErrorException('Bạn không có xem báo cáo này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		$this->layout = null;
			
		$this->loadModel('User');
			
		$start = $this->Bin->vn2sql($this->request->data['tu_ngay']);
		$end = $this->Bin->vn2sql($this->request->data['den_ngay']);
		
		$start = $start . ' 00:00:00';
		$end = $end . ' 23:59:59';
		
		$data = $this->User->query("
									SELECT signin_date  
									FROM signin_histories
									WHERE nhanvien_id = " . $this->Session->read('Auth.User.id') . "
											AND signin_date BETWEEN '" . $start . "' AND '" . $end . "'
									
									ORDER BY signin_date ASC
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
		$sheet->mergeCells('A1:E1');
		$sheet->mergeCells('A2:E2');
		$sheet->getStyle('A1:A2')->applyFromArray($header_style);

		$sheet->setCellValue('A1', 'THỐNG KÊ SỐ LẦN TRUY CẬP BINPLUS');
		$sheet->setCellValue('A2', sprintf('Từ ngày %s đến ngày %s', $this->request->data['tu_ngay'], $this->request->data['den_ngay']));
		$this->PhpExcel->setRow(5);
		// define table cells
		$table = array(
			array('label' => __('STT'), 'filter' => false),
			array('label' => __('Số lần truy cập'), 'filter' => true),

		);
		
		// add heading with different font and bold text
		$this->PhpExcel->addTableHeader($table, array('bold' => true));
		
		// add data
		$stt = 1;
		foreach ($data as $d) {
			$this->PhpExcel->addTableRow(array(
				$stt++,
				$d['signin_histories']['signin_date'],
			));
		}
		// close table and output
		$this->PhpExcel->addTableFooter();
		
		$this->PhpExcel->output('luottruycap_nhanvien.xlsx');
	}
	
}