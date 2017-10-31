 <?php
class CongviecController extends AppController {

	protected $paginate = array(
        'limit' => 	20,
		'order'	=>	'Congviec.id DESC'
        );
	
	public $helpers = array('Xls', 'Bin');              
	public $components = array(

		'Bin', 'PhpExcel'
    );
	public function 	index()
	{
		if(!$this->check_permission('CongViec.nhan'))
			throw new InternalErrorException();
		$this->set('title_for_layout', 'Danh sách công việc');
	}
	
	
	function	search($action = 'duocgiao')
	{
		$this->set('title_for_layout', 'Tra cứu công việc');
		
		if($this->RequestHandler->isAjax())
		{
			switch($action)
			{
				case 'duocgiao':
					$this->Congviec->bindModel(array(
						'belongsTo'	=> array(
							'CongviecChinh'	=>	array('className' 	=> 'Congviec', 'foreignKey' 	=> 'congviecchinh_id', 'fields' => 'mucdo_hoanthanh')
						)
					));
				
					$conds = array('Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'));
					
					if(!empty($this->request->data['keyword']))
						$this->passedArgs['keyword'] = $this->request->data['keyword'];
					if(!empty($this->request->data['ngay_batdau']) && !empty($this->request->data['ngay_ketthuc']))
					{
						$this->passedArgs['ngay_batdau']  	= $this->Bin->vn2sql($this->request->data['ngay_batdau']);
						$this->passedArgs['ngay_ketthuc']  	= $this->Bin->vn2sql($this->request->data['ngay_ketthuc']);
					}
					if(!empty($this->request->data['mucdo_hoanthanh']))
						$this->passedArgs['mucdo_hoanthanh']  	= $this->request->data['mucdo_hoanthanh'];
				
					if(isset($this->passedArgs['keyword']))
						array_push($conds, array(
							"OR"	=>	array(
								"Congviec.ten_congviec LIKE" => 	'%' . $this->passedArgs['keyword'] . '%',
								"Congviec.noi_dung LIKE"	=>	'%' . $this->passedArgs['keyword'] . '%'
							)
						));
					if(isset($this->passedArgs['ngay_batdau']))
					{
						array_push($conds, "Congviec.ngay_batdau >='" . $this->passedArgs['ngay_batdau'] . "'");
						array_push($conds, "Congviec.ngay_ketthuc <='" . $this->passedArgs['ngay_ketthuc'] . "'");
					}
					
					if(!empty($this->passedArgs['mucdo_hoanthanh']))
					{
						if($this->passedArgs['mucdo_hoanthanh'] == 'dahoanthanh')
							array_push($conds, 'Congviec.mucdo_hoanthanh = 10');
						else
							array_push($conds, 'Congviec.mucdo_hoanthanh < 10');
					}
					
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					$this->render('search_duocgiao');
					//pr($ds); die();
					break;
					
				case 'dagiao':
					//pr($this->request->data);die();

					$conds = array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'));
				
					if(!empty($this->request->data['keyword']))
						$this->passedArgs['keyword'] = $this->request->data['keyword'];
					if(!empty($this->request->data['ngay_batdau']) && !empty($this->request->data['ngay_ketthuc']))
					{
						$this->passedArgs['ngay_batdau']  	= $this->Bin->vn2sql($this->request->data['ngay_batdau']);
						$this->passedArgs['ngay_ketthuc']  	= $this->Bin->vn2sql($this->request->data['ngay_ketthuc']);
					}
					if(!empty($this->request->data['mucdo_hoanthanh']))
						$this->passedArgs['mucdo_hoanthanh']  	= $this->request->data['mucdo_hoanthanh'];
					if(!empty($this->request->data['nguoinhan_id']))
						$this->passedArgs['nguoinhan_id']  	= $this->request->data['nguoinhan_id'];
					if(!empty($this->request->data['loaicongviec_id']))
						$this->passedArgs['loaicongviec_id']  	= $this->request->data['loaicongviec_id'];
					if(isset($this->passedArgs['keyword']))
						array_push($conds, array(
							"OR"	=>	array(
								"ten_congviec LIKE" => 	'%' . $this->passedArgs['keyword'] . '%',
								"noi_dung LIKE"	=>	'%' . $this->passedArgs['keyword'] . '%'
							)
						));
					if(isset($this->passedArgs['ngay_batdau']))
					{
						array_push($conds, "Congviec.ngay_batdau >='" . $this->passedArgs['ngay_batdau'] . "'");
						array_push($conds, "Congviec.ngay_ketthuc <='" . $this->passedArgs['ngay_ketthuc'] . "'");
					}
					
					if(!empty($this->passedArgs['mucdo_hoanthanh']))
					{
						if($this->passedArgs['mucdo_hoanthanh'] == 'dahoanthanh')
							array_push($conds, 'Congviec.mucdo_hoanthanh = 10');
						else
							array_push($conds, 'Congviec.mucdo_hoanthanh < 10');
					}
					if(!empty($this->passedArgs['nguoinhan_id']))
					{
						array_push($conds, array("Congviec.nguoinhan_id"	=>	$this->passedArgs['nguoinhan_id']));
					}
					if(!empty($this->passedArgs['loaicongviec_id']))
					{
						array_push($conds, array("Congviec.loaicongviec_id"	=>	$this->passedArgs['loaicongviec_id']));
					}
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					$this->render('search_dagiao');
					break;
			}
		}else
			$nv = $this->cboNhanvien('CongViec.khoitao');
			$loai_congviec	= array("0"	=>	'Chỉ view',
								"1"	=>	'Theo dõi và báo cáo');
			$this->set(compact('nv','loai_congviec'));
			
			//$this->render('search');
	}
	
	/////
	function	search_kq($action = 'vtdn')
	{
		$this->set('title_for_layout', 'Tra cứu kết quả xử lý công việc');
		if($this->RequestHandler->isAjax())
		{
			switch($action)
			{
				case 'vtdn':
					$this->Congviec->bindModel(array(
						'belongsTo'	=> array(
							'CongviecChinh'	=>	array('className' 	=> 'Congviec', 'foreignKey' 	=> 'congviecchinh_id', 'fields' => 'mucdo_hoanthanh')
						)
					));
				
					$conds = array('Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'));
					
					if(!empty($this->request->data['keyword']))
						$this->passedArgs['keyword'] = $this->request->data['keyword'];
					if(!empty($this->request->data['ngay_batdau']) && !empty($this->request->data['ngay_ketthuc']))
					{
						$this->passedArgs['ngay_batdau']  	= $this->Bin->vn2sql($this->request->data['ngay_batdau']);
						$this->passedArgs['ngay_ketthuc']  	= $this->Bin->vn2sql($this->request->data['ngay_ketthuc']);
					}
					if(!empty($this->request->data['mucdo_hoanthanh']))
						$this->passedArgs['mucdo_hoanthanh']  	= $this->request->data['mucdo_hoanthanh'];
				
					if(isset($this->passedArgs['keyword']))
						array_push($conds, array(
							"OR"	=>	array(
								"Congviec.ten_congviec LIKE" => 	'%' . $this->passedArgs['keyword'] . '%',
								"Congviec.noi_dung LIKE"	=>	'%' . $this->passedArgs['keyword'] . '%'
							)
						));
					if(isset($this->passedArgs['ngay_batdau']))
					{
						array_push($conds, "Congviec.ngay_batdau >='" . $this->passedArgs['ngay_batdau'] . "'");
						array_push($conds, "Congviec.ngay_ketthuc <='" . $this->passedArgs['ngay_ketthuc'] . "'");
					}
					
					if(!empty($this->passedArgs['mucdo_hoanthanh']))
					{
						if($this->passedArgs['mucdo_hoanthanh'] == 'dahoanthanh')
							array_push($conds, 'Congviec.mucdo_hoanthanh = 10');
						else
							array_push($conds, 'Congviec.mucdo_hoanthanh < 10');
					}
					
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					$this->render('search_duocgiao');
					//pr($ds); die();
					break;
					
				case 'dagiao':
					//pr($this->request->data);die();

					$conds = array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'));
				
					if(!empty($this->request->data['keyword']))
						$this->passedArgs['keyword'] = $this->request->data['keyword'];
					if(!empty($this->request->data['ngay_batdau']) && !empty($this->request->data['ngay_ketthuc']))
					{
						$this->passedArgs['ngay_batdau']  	= $this->Bin->vn2sql($this->request->data['ngay_batdau']);
						$this->passedArgs['ngay_ketthuc']  	= $this->Bin->vn2sql($this->request->data['ngay_ketthuc']);
					}
					if(!empty($this->request->data['mucdo_hoanthanh']))
						$this->passedArgs['mucdo_hoanthanh']  	= $this->request->data['mucdo_hoanthanh'];
					if(!empty($this->request->data['nguoinhan_id']))
						$this->passedArgs['nguoinhan_id']  	= $this->request->data['nguoinhan_id'];
					if(!empty($this->request->data['loaicongviec_id']))
						$this->passedArgs['loaicongviec_id']  	= $this->request->data['loaicongviec_id'];
					if(isset($this->passedArgs['keyword']))
						array_push($conds, array(
							"OR"	=>	array(
								"ten_congviec LIKE" => 	'%' . $this->passedArgs['keyword'] . '%',
								"noi_dung LIKE"	=>	'%' . $this->passedArgs['keyword'] . '%'
							)
						));
					if(isset($this->passedArgs['ngay_batdau']))
					{
						array_push($conds, "Congviec.ngay_batdau >='" . $this->passedArgs['ngay_batdau'] . "'");
						array_push($conds, "Congviec.ngay_ketthuc <='" . $this->passedArgs['ngay_ketthuc'] . "'");
					}
					
					if(!empty($this->passedArgs['mucdo_hoanthanh']))
					{
						if($this->passedArgs['mucdo_hoanthanh'] == 'dahoanthanh')
							array_push($conds, 'Congviec.mucdo_hoanthanh = 10');
						else
							array_push($conds, 'Congviec.mucdo_hoanthanh < 10');
					}
					if(!empty($this->passedArgs['nguoinhan_id']))
					{
						array_push($conds, array("Congviec.nguoinhan_id"	=>	$this->passedArgs['nguoinhan_id']));
					}
					if(!empty($this->passedArgs['loaicongviec_id']))
					{
						array_push($conds, array("Congviec.loaicongviec_id"	=>	$this->passedArgs['loaicongviec_id']));
					}
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					$this->render('search_dagiao');
					break;
			}
		}else
			$donvigiao	= array("0"	=>	'VTĐN',
								"1"	=>	'Phòng ban/Đơn vị');
			$this->set(compact('donvigiao'));
			
			//$this->render('search');
	}
	/////////
	
	public	function	excel_search()

	{
		$this->layout = null;
		///
		$conds = array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'));
				
		if(!empty($this->request->data['keyword']))
			$this->passedArgs['keyword'] = $this->request->data['keyword'];
		if(!empty($this->request->data['ngay_batdau']) && !empty($this->request->data['ngay_ketthuc']))
		{
			$this->passedArgs['ngay_batdau']  	= $this->Bin->vn2sql($this->request->data['ngay_batdau']);
			$this->passedArgs['ngay_ketthuc']  	= $this->Bin->vn2sql($this->request->data['ngay_ketthuc']);
		}
		if(!empty($this->request->data['mucdo_hoanthanh']))
			$this->passedArgs['mucdo_hoanthanh']  	= $this->request->data['mucdo_hoanthanh'];
		if(!empty($this->request->data['nguoinhan_id']))
			$this->passedArgs['nguoinhan_id']  	= $this->request->data['nguoinhan_id'];
		if(!empty($this->request->data['loaicongviec_id']))
			$this->passedArgs['loaicongviec_id']  	= $this->request->data['loaicongviec_id'];
		if(isset($this->passedArgs['keyword']))
			array_push($conds, array(
				"OR"	=>	array(
					"ten_congviec LIKE" => 	'%' . $this->passedArgs['keyword'] . '%',
					"noi_dung LIKE"	=>	'%' . $this->passedArgs['keyword'] . '%'
				)
			));
		if(isset($this->passedArgs['ngay_batdau']))
		{
			array_push($conds, "Congviec.ngay_batdau >='" . $this->passedArgs['ngay_batdau'] . "'");
			array_push($conds, "Congviec.ngay_ketthuc <='" . $this->passedArgs['ngay_ketthuc'] . "'");
		}
		
		if(!empty($this->passedArgs['mucdo_hoanthanh']))
		{
			if($this->passedArgs['mucdo_hoanthanh'] == 'dahoanthanh')
				array_push($conds, 'Congviec.mucdo_hoanthanh = 10');
			else
				array_push($conds, 'Congviec.mucdo_hoanthanh < 10');
		}
		if(!empty($this->passedArgs['nguoinhan_id']))
		{
			array_push($conds, array("Congviec.nguoinhan_id"	=>	$this->passedArgs['nguoinhan_id']));
		}
		if(!empty($this->passedArgs['loaicongviec_id']))
		{
			array_push($conds, array("Congviec.loaicongviec_id"	=>	$this->passedArgs['loaicongviec_id']));
		}
		//$data =  $this->paginate('Congviec', $conds);
		$ds =  $this->Congviec->find('all', array('conditions' => $conds));
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
		$sheet->setCellValue('A1', 'TÌM KIẾM CÔNG VIỆC');
		$this->PhpExcel->setRow(5);
		// define table cells
		$table = array(
			array('label' => __('STT'), 'filter' => false),
			array('label' => __('Tên công việc'), 'filter' => true),
			array('label' => __('Người giao việc'), 'filter' => true),
			array('label' => __('Người nhận việc'), 'filter' => true),
			array('label' => __('Ngày bắt đầu'), 'filter' => true),
			array('label' => __('Ngày hoàn thành'), 'filter' => true),
			array('label' => __('Hoàn thành'), 'filter' => true),
		);
		// add heading with different font and bold text
		$this->PhpExcel->addTableHeader($table, array('bold' => true));
		// add data
		if(!empty($ds)):
			$stt = 1;
			foreach ($ds as $d):
				$this->PhpExcel->addTableRow(array(
					$stt++,
					$d['Congviec']['ten_congviec'],
					$d['NguoiGiaoviec']['full_name'],
					$d['NguoiNhanviec']['full_name'],
					$d['Congviec']['ngay_batdau'],
					$d['Congviec']['ngay_ketthuc'],
					$d['Congviec']['mucdo_hoanthanh']*10 . '%'
				));
			endforeach;
		endif;
		// close table and output
		$this->PhpExcel->addTableFooter();
		$this->PhpExcel->output('dscongviec.xlsx');

	}
	////////
	
	public	function	add($vanban_id = null)
	{
		if(!$this->check_permission('CongViec.khoitao'))
			throw new InternalErrorException('Bạn không có quyền giao việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		
		$this->set('title_for_layout', 'Khởi tạo công việc mới');
			
		$this->loadModel('Chitietcongviec');
		$this->loadModel('Tinhchatcongviec');
		if(!empty($this->request->data))
		{
			$this->request->data['Congviec']['nguoi_giaoviec_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Congviec']['ngay_giao'] = date("Y-m-d H:i:s");
			$this->request->data['Congviec']['ngay_batdau'] = $this->Bin->vn2sql($this->request->data['Congviec']['ngay_batdau']);
			$this->request->data['Congviec']['ngay_ketthuc'] = $this->Bin->vn2sql($this->request->data['Congviec']['ngay_ketthuc']);
			$this->request->data['Congviec']['giaoviec_tiep'] = isset($this->request->data['Congviec']['giaoviec_tiep']) ? 1 : 0;
			$this->request->data['Congviec']['mucdo_hoanthanh'] = 0;
			//pr($this->request->data); die();
			$dataSource = $this->Congviec->getDataSource();
			$dataSource->begin($this); // begin transaction
			$tinhchat_id = $this->request->data['Congviec']['tinhchat_id'];
			if($this->Congviec->save($this->request->data))
			{
				if(isset($this->request->data['Congviec']['vanban_id']))
				{
					if(!$this->vanbangiaoviec($this->request->data['Congviec']['nguoinhan_id'], $this->request->data['Congviec']['vanban_id']))
					{
						$this->Session->setFlash('Đã phát sinh lỗi khi đính kèm văn bản cho nhân viên nhận việc. Vui lòng thử lại.', 'flash_error');
						$dataSource->rollback();
					}
				}
				$id = $this->Congviec->getLastInsertID();
				$this->Congviec->query("UPDATE congviec_thongtin SET congviecchinh_id=" . $id . " WHERE id=" . $id);
				$nguoinhan = $this->Session->read('CONGVIEC.nguoinhanviec');
				if(!empty($nguoinhan))
				{
					/*$this->Session->setFlash('Vui lòng chọn người nhận việc.', 'flash_error');
					$dataSource->rollback();
				}else
				'tinhchat_id'=>	$this->request->data['Nguoinhanviec']['nn_tinhchat_id'],
				{*/
				//$tinhchat_id = $this->request->data['Nguoinhanviec']['nn_tinhchat_id'];
					foreach($nguoinhan as $n)
					{
						$a['Congviec'] = array('id'			=>	NULL,
											  'parent_id'	=>	$id,
											  'congviecchinh_id' => $id,
											  'nguoinhan_id'=>	$n['nguoinhan_id'],
											  'nguoi_giaoviec_id'=>	$this->Auth->user('nhanvien_id'),
											  'ten_congviec'=>	$n['ten_congviec'],
											  'tinhchat_id' =>  $tinhchat_id,
											  'noi_dung'	=>	$n['noi_dung'],
											  'ngay_batdau'	=>	$n['ngay_batdau'],
											  'ngay_ketthuc'=>	$n['ngay_ketthuc'],
											  'ngay_giao'=>	date('Y-m-d H:i:s'),
											  'vanban_id'	=>	isset($this->request->data['Congviec']['vanban_id']) ? $this->request->data['Congviec']['vanban_id'] : null,
											  'mucdo_hoanthanh'=>	0,
											  'giaoviec_tiep'	=>	$n['giaoviec_tiep']);
						//pr($a['Congviec']); die();
						if($this->Congviec->save($a))
						{
							if(isset($this->request->data['Congviec']['vanban_id']))
							{
								if(!$this->vanbangiaoviec($n['nguoinhan_id'], $this->request->data['Congviec']['vanban_id']))
								{
									$this->Session->setFlash('Đã phát sinh lỗi khi đính kèm văn bản cho nhân viên nhận việc. Vui lòng thử lại.', 'flash_error');
									$dataSource->rollback();
								}
							}
						}else
						{
							$this->Session->setFlash('Đã phát sinh lỗi khi giao việc cho nhân viên. Vui lòng thử lại.', 'flash_error');
							$dataSource->rollback();
						}
					}
					$this->Session->delete('CONGVIEC.nguoinhanviec');
					
				}//end of if
				
				$dataSource->commit();
				$this->Session->setFlash('Khởi tạo công việc thành công.', 'flash_success');
				$this->redirect('/congviec/dagiao');
				
			}
		}
		
		$vanban = null;
		
		if(!empty($vanban_id))
		{
			$this->loadModel('Vanban');
			$this->Vanban->recursive = -1;
			$vanban = $this->Vanban->read(array('id', 'trich_yeu', 'so_hieu'), $vanban_id);
			
		}
		$this->loadModel('Congviec');
		$giaoviec_vanban = $this->Congviec->find('first', array('conditions' => array('Congviec.vanban_id' => $vanban_id)));
		//pr($giaoviec_vanban);die();

		$tinhchat = $this->Tinhchatcongviec->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_tinhchat')));
		//pr($tinhchat);die();
		$loai_congviec	= array("0"	=>	'Chỉ view',
								"1"	=>	'Theo dõi và báo cáo');
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array('belongsTo' => array('Vanban' => array('fields' => array('Vanban.id', 'trich_yeu')))), false);
		//$giaoviec_vanban = $this->Congviec->find('first', array('conditions' => array( 'Congviec.vanban_id' => $vanban, 'Congviec.nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'))));
		//pr($giaoviec_vanban);die();
		// kiểm tra quyền hạn giao việc theo phòng
		$nv = $this->cboNhanvien('CongViec.khoitao');
		$this->set(compact('tinhchat', 'loai_congviec','vanban', 'nv'));
		
		$this->Session->delete('CONGVIEC.nguoinhanviec');
	}
	
	public	function	edit($congviec_id = null)
	{
		if(!$this->check_permission('CongViec.khoitao'))
			throw new InternalErrorException('Bạn không có quyền giao việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
			
		$this->set('title_for_layout', 'Hiệu chỉnh công việc đã giao');
		
		$this->Congviec->bindModel(
			array(
				'belongsTo' => array(
					'Vanban'	=>	array('fields' => array('trich_yeu', 'so_hieu', 'ngay_phathanh', 'noi_gui')),
					'Tinhchatcongviec'	=>	array('className'	=>	'Tinhchatcongviec', 'foreignKey' => 'tinhchat_id')
					),
				'hasMany'	=>	array(
					'Chitietcongviec' => array(
									'className'	=>	'Congviec',
									'foreignKey'	=>	'parent_id',
									'order'			=>	'lft ASC'))
				)
			, false);
		
		// empty session
		
			
		if(!empty($this->request->data))
		{
			$ds = $this->Session->read('CONGVIEC.nguoinhanviec');
			
			$this->request->data['Congviec']['ngay_batdau'] = $this->Bin->vn2sql($this->request->data['Congviec']['ngay_batdau']);
			$this->request->data['Congviec']['ngay_ketthuc'] = $this->Bin->vn2sql($this->request->data['Congviec']['ngay_ketthuc']);
			
			$dataSource = $this->Congviec->getDataSource();
			$dataSource->begin($this); // begin transaction
			
			if($this->Congviec->save($this->request->data))
			{
				$f = true;
				$chitiet = $this->Congviec->find('list', array('conditions' => array('Congviec.parent_id' => $this->request->data['Congviec']['id'], 'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id')), 'fields' => 'id'));
				
				if(empty($chitiet))
					$chitiet = array();
				$del = array_diff_key($chitiet, $ds);
				//pr($del);die();
				$ins = array_diff_key($ds, $chitiet);
				$upd = array_intersect_key($ds, $chitiet);
				
				//update
				foreach($upd as $item)
				{
					if(!$this->Congviec->save($item))
					{
						$f = false; break;
					}
				}
				
				//insert
				if($f)
					foreach($ins as $item)
					{
						$item['id'] = NULL;
						$item['parent_id'] = $this->request->data['Congviec']['id'];
						$item['nguoi_giaoviec_id'] =	$this->Auth->user('nhanvien_id');
						$item['mucdo_hoanthanh'] = 0;
						$item['ngay_giao'] = date('Y-m-d H:i:s');
						if(!$this->Congviec->save($item))
						{
							$f = false;	break;
						}
					}
				//delete
				if($f)
					foreach($del as $k=>$v)
					{
						if(!$this->Congviec->delete($k))
						{
							$f = false;	break;
						}
					}
				if($f)
				{
					$this->Session->delete('CONGVIEC.nguoinhanviec');
					$dataSource->commit();
					$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
				}
				else
				{
					$dataSource->rollback();
					$this->Session->setFlash('Bị lỗi khi hiệu chỉnh công việc.', 'flash_error');
				}
			}
			
		}
		
		$data = $this->Congviec->find('first', array('conditions' => array('Congviec.id' => $congviec_id, 'Congviec.nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id')), 'recursive' => 1));
		
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy dữ liệu.');
		
		$tinhchat = $this->Congviec->Tinhchatcongviec->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_tinhchat')));
		
		$nv = $this->cboNhanvien('CongViec.khoitao');
		
		$this->data = $data;
		$this->set(compact('tinhchat', 'nv'));
		$this->Session->delete('CONGVIEC.nguoinhanviec');	// delete Session
		
		$ds = array();
		//pr($data['Chitietcongviec']);die();
		if(!empty($data['Chitietcongviec']))
			foreach($data['Chitietcongviec'] as $item)
			{
				if($item['nguoi_giaoviec_id'] == $data['Congviec']['nguoi_giaoviec_id'])
				{
					$tmp = array('id'			=>	$item['id'],
								 'nguoinhan_id'	=>	$item['nguoinhan_id'],
								 'full_name'	=>	$item['NguoiNhanviec']['full_name'],
								 'ten_congviec'	=>	$item['ten_congviec'],
								 'noi_dung'		=>	$item['noi_dung'],
								 'giaoviec_tiep'=>	$item['giaoviec_tiep'],
								 'ngay_batdau'	=>	$item['ngay_batdau'],
								 'ngay_ketthuc'	=>	$item['ngay_ketthuc']);
					$ds[$tmp['id']] = $tmp;
				}
			}
		$this->Session->write('CONGVIEC.nguoinhanviec', $ds);	// reset Session
	}
	
	public	function	add_nguoinhanviec($congviec_id = null)
	{
		if(!empty($this->request->data))
		{
			$ds = $this->Session->read('CONGVIEC.nguoinhanviec');
			$this->loadModel('Nhanvien');
				
			if(empty($this->request->data['Nguoinhanviec']['nv_selected']))
				die(json_encode(array('success' => false, 'message' => 'Vui lòng chọn Nhân viên thực hiện.')));
			
			$nhanviennhan = explode(',', $this->request->data['Nguoinhanviec']['nv_selected']);
			foreach($nhanviennhan as $v)
			{
				$fullname = $this->Nhanvien->field('full_name', array('Nhanvien.id' => $v));
				//if($this->kiemtraNguoinhanviec($item['id'], $congviec_id, $this->request->data['Nguoinhanviec']['ngay_batdau'], $this->request->data['Nguoinhanviec']['ngay_ketthuc']))
				if($this->request->data['Nguoinhanviec']['ngay_batdau'] <= $this->request->data['Nguoinhanviec']['ngay_ketthuc'])
				{
					$key = $this->Bin->rndString();
					$tmp = array(
						'id'			=>	$key,
						'nguoinhan_id'	=>	$v,
						'full_name'		=>	$fullname,
						'ngay_batdau'	=>	$this->Bin->vn2sql($this->request->data['Nguoinhanviec']['ngay_batdau']),
						'ngay_ketthuc'	=>	$this->Bin->vn2sql($this->request->data['Nguoinhanviec']['ngay_ketthuc']),
						'ten_congviec'	=>	$this->request->data['Nguoinhanviec']['ten_congviec'],
						'noi_dung'		=>	$this->request->data['Nguoinhanviec']['noi_dung'],
						/*'tinhchat_id'		=>	$this->request->data['Nguoinhanviec']['tinhchat_id'],*/
						'giaoviec_tiep'=>	isset($this->request->data['Nguoinhanviec']['giaoviec_tiep']) ? 1 : 0
					);
					$ds[$key] = $tmp;
				}else
				{
					//die(json_encode(array('success' => false, 'message' => 'Phân việc cho nhân viên ' . $fullname . ' không hợp lệ.')));
					die(json_encode(array('success' => false, 'message' => 'Ngày bắt đầu và ngày kết thúc không hợp lệ.')));
				}
			}
			$this->Session->write('CONGVIEC.nguoinhanviec', $ds);
			die(json_encode(array('success' => true)));
		}
	}
	
	public	function	edit_nguoinhanviec($id = null)
	{
		//pr($this->Session->read('CONGVIEC.nguoinhanviec'));
		if(is_null($id))	// if submit
		{
			$ds = $this->Session->read('CONGVIEC.nguoinhanviec');
			$this->loadModel('Nhanvien');
			$tmp = array(
						'id'			=>	$this->request->data['Nguoinhanviec']['id'],
						'nguoinhan_id'	=>  $this->request->data['Nguoinhanviec']['nguoinhan_id'],
						'full_name'		=>	$this->Nhanvien->field('full_name', array('Nhanvien.id' => $this->request->data['Nguoinhanviec']['nguoinhan_id'])),
						'ngay_batdau'	=>	$this->Bin->vn2sql($this->request->data['Nguoinhanviec']['ngay_batdau']),
						'ngay_ketthuc'	=>	$this->Bin->vn2sql($this->request->data['Nguoinhanviec']['ngay_ketthuc']),
						'ten_congviec'	=>	$this->request->data['Nguoinhanviec']['ten_congviec'],
						'noi_dung'		=>	$this->request->data['Nguoinhanviec']['noi_dung'],
						'giaoviec_tiep'=>	isset($this->request->data['Nguoinhanviec']['giaoviec_tiep']) ? 1 : 0
			);
			$ds[$tmp['id']] = $tmp;
			
			$this->Session->delete('CONGVIEC.nguoinhanviec');	// delete old session
			$this->Session->write('CONGVIEC.nguoinhanviec', $ds);
			
			die(json_encode(array('success' => true)));
		}else
		{
			$ds = $this->Session->read('CONGVIEC.nguoinhanviec');
			//pr($ds); die();
			if(!empty($ds))
				$this->data = $ds[$id];
		}
		
	}
	
	
	public	function	del_nguoinhanviec($id = null)
	{
		$ds = $this->Session->read('CONGVIEC.nguoinhanviec');
		unset($ds[$id]);
		$this->Session->delete('CONGVIEC.nguoinhanviec');	// delete old session
		$this->Session->write('CONGVIEC.nguoinhanviec', $ds);
		die(json_encode(array('success' => true)));
	}
	
	public	function	ds_nguoinhanviec($congviec_id = null)
	{
		$this->layout = null;
		$arr = array();
		
		// Session
		$ds = $this->Session->read('CONGVIEC.nguoinhanviec');
		if(!empty($ds))
			foreach($ds as $item)
			{
				$desc = '';
				if($item['ngay_batdau'] == $item['ngay_ketthuc'])
					$desc = sprintf("<b>Ngày:</b> %s<BR><b>Tên công việc:</b>%s<BR><b>Nội dung:</b> %s<BR><i>(Click để hiệu chỉnh)</i>", 
							 				$this->Bin->sql2vn($item['ngay_batdau']),
											$item['ten_congviec'],
							 				$item['noi_dung']);
				else
					
					$desc = sprintf("<b>Từ ngày:</b> %s <b>Đến ngày:</b> %s<BR><b>Tên công việc:</b>%s<BR><b>Nội dung:</b> %s<BR><i>(Click để hiệu chỉnh)</i>", 
							 				$this->Bin->sql2vn($item['ngay_batdau']),
											$this->Bin->sql2vn($item['ngay_ketthuc']),
											$item['ten_congviec'],
							 				$item['noi_dung']);
					
				$tmp = array('id'		=>	$item['id'],
							 'nguoinhan_id'		=>	$item['nguoinhan_id'],
							 'start'	=>	$item['ngay_batdau'],
							 'end'		=>	$item['ngay_ketthuc'],
							 'allDay'	=>	true,
							 'title'	=>	$item['full_name'],
							 'description'=>	$desc
							 );	
				array_push($arr, $tmp);
			}
		
		die(json_encode($arr));
	}
	
	public	function	dagiao()
	{
		if(!$this->check_permission('CongViec.khoitao'))
			throw new InternalErrorException('Bạn không có quyền giao việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
			
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Việc đã giao');
		}else
		{
			$opt = $this->passedArgs['opt'];
			$conds = array();
			$this->Congviec->recursive = 1;
			
			switch($opt)
			{
				case 'all':
					$conds = array(
						'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id')
					);
					/*$this->Congviec->bindModel(array(
						'hasMany' => array(
							'Filecongviec' => array('foreignKey' => 'congviec_id')
						)
					));*/
							
					$data =  $this->paginate('Congviec', $conds);
					if(empty($data))
						$this->Session->setFlash('Không có số liệu cho công việc đã giao.', 'flash_attention');
					$this->set(compact('data'));
					$this->render('dagiao_all');
					break;
				case 'instant':
					$conds = array(
						'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
						'mucdo_hoanthanh <'	=>	10,
						'tinhchat_id' => 11
					);
					$data =  $this->paginate('Congviec', $conds);
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đã giao đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					$this->render('dagiao_instant');
					break;
				case 'baocao': //hỏi lại cái này
					$conds = array(
						'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
						'loaicongviec_id' => 1
					);
					/*$conds = array(
						'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
						'loaicongviec_id' => 1,
						'mucdo_hoanthanh <'	=>	10,
						'ngay_ketthuc <'	=>	date('Y-m-d', time())
					);*/
					$this->Congviec->bindModel(array(
						'hasMany' => array(
							'Filecongviec' => array('foreignKey' => 'congviec_id')
						)
					));
					$data =  $this->paginate('Congviec', $conds);
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đã giao cần theo dõi, xem báo cáo.', 'flash_attention');
					}
					$this->set(compact('data'));
					$this->render('dagiao_baocao');
					break;
				case 'progressing':
					$conds = array(
						'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
						'mucdo_hoanthanh <'	=>	10,
						'ngay_ketthuc >='	=>	date('Y-m-d', time())
					);
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đã giao đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('dagiao_progressing');
					break;
					
				case 'unfinished'://hỏi lại cái này
					$conds = array(
						'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
						'mucdo_hoanthanh <'	=>	10,
						'loaicongviec_id'	=> 1,
						'ngay_ketthuc <'	=>	date('Y-m-d', time())
					);
					$data =  $this->paginate('Congviec', $conds);
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đã giao trễ tiến độ.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('dagiao_unfinished');
					break;
					
				case 'finished':
					$conds = array(
						'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
						'mucdo_hoanthanh'	=>	10
					);
					$data =  $this->paginate('Congviec', $conds);
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đã giao hoàn thành.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('dagiao_finished');
					break;
			}
		}
	}

	
	public	function	duocgiao()
	{
		if(!$this->check_permission('CongViec.thuchien'))
			throw new InternalErrorException('Bạn không có quyền thực hiện công việc được giao. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
			
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Việc được giao');
		}else
		{
			$conds = array();
			$this->Congviec->bindModel(array(
				'belongsTo'	=> array(
					'CongviecChinh'	=>	array('className' 	=> 'Congviec', 'foreignKey' 	=> 'congviecchinh_id', 'fields' => 'mucdo_hoanthanh')
				)
			));
		
			$this->Congviec->recursive = 1;
			
			$opt = $this->passedArgs['opt'];
			
			switch($opt)
			{
				case 'all':
					
					$data = $this->paginate('Congviec', array('Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id')));
					if(empty($data))
					{
						$this->Session->setFlash('Hiện tại chưa được giao công việc nào.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('duocgiao_all');
					break;
				case 'instant':
					$conds = array(
						'Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'),
						'Congviec.mucdo_hoanthanh <'	=>	10,
						'Congviec.tinhchat_id' =>11
					);
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('duocgiao_instant');
					break;
				case 'baocao':
					/*$conds = array(
						'Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'),
						'Congviec.loaicongviec_id' => 1,
						'Congviec.mucdo_hoanthanh <'	=>	10,
						'Congviec.ngay_ketthuc >='	=>	date('Y-m-d', time())
					);*/
					
					
					$conds = array(
						'Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'),
						'Congviec.loaicongviec_id' => 1
					);
					$data =  $this->paginate('Congviec', $conds);
					//pr($data);die();
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc cần theo dõi báo cáo.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('duocgiao_baocao');
					break;
				case 'progressing':
					$conds = array(
						'Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'),
						'Congviec.mucdo_hoanthanh <'	=>	10,
						'Congviec.ngay_ketthuc >='	=>	date('Y-m-d', time())
					);
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đang thực hiện.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('duocgiao_progressing');
					break;
					
				case 'unfinished':
					$conds = array(
						'Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'),
						'Congviec.mucdo_hoanthanh <'	=>	10,
						'Congviec.ngay_ketthuc <'	=>	date('Y-m-d', time())
					);
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc trễ tiến độ.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('duocgiao_unfinished');
					break;
				case 'finished':
					$conds = array(
						'Congviec.nguoinhan_id' => $this->Auth->user('nhanvien_id'),
						'Congviec.mucdo_hoanthanh '	=>	10
					);
					$data =  $this->paginate('Congviec', $conds);
					
					if(empty($data))
					{
						$this->Session->setFlash('Không có số liệu cho công việc đã hoàn thành.', 'flash_attention');
					}
					$this->set(compact('data'));
					
					$this->render('duocgiao_finished');
					break;
			}
		}
		
	}
	
	public	function	view($congviec_id = null,$refer = null)
	{
		$this->Congviec->bindModel(
			array(
				'belongsTo' => array(
					'Vanban'	=>	array('fields' => array('trich_yeu', 'so_hieu', 'ngay_phathanh', 'noi_gui')),
					'Tinhchatcongviec'	=>	array('className'	=>	'Tinhchatcongviec', 'foreignKey' => 'tinhchat_id')
					)
				)
			, false);
			
		$this->Congviec->bindModel(array(
						'hasMany' => array(
							'Filecongviec' => array('foreignKey' => 'congviec_id')
						)
					));
		$this->set('title_for_layout', 'Xem chi tiết công việc');
		//pr($this->referer());die();
		$data = $this->Congviec->find('first', array(
			'conditions'	=>	array(
						"Congviec.id"	=>	$congviec_id,
						"OR"	=>	array(
								'nguoi_giaoviec_id'	=>	$this->Auth->user('nhanvien_id'),
								'nguoinhan_id'		=>	$this->Auth->user('nhanvien_id')
							)
					)
		));
		
		
		if(empty($data))
		{
			$this->Session->setFlash('Không tìm thấy dữ liệu.', 'flash_attention');
			$this->redirect('/congviec/dagiao/#congviec_all');
		}
		
		$this->data = $data;
		$this->set(compact(array('refer')));
	}
	
	public	function	delete()
	{
		$this->layout = null;
		$opt = 'all';
		if(isset($this->passedArgs['opt']))
			$opt = $this->passedArgs['opt'];
		if(!empty($this->request->data))
		{
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			foreach( $ids as $k => $v )
			{
				if($this->Congviec->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/congviec/dagiao/opt:' . $opt);
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/congviec/dagiao/opt:' . $opt);
	}
	
	private	function	kiemtraNguoinhanviec($nguoinhan_id, $congviec_id, $b, $e)
	{
		// kiem tra ngày bắt đầu va ngày kết thúc
		if($b > $e)
			return false;
			
		// kiem tra session
		$ds = $this->Session->read('CONGVIEC.nguoinhanviec');
		if(!empty($ds))
		{
			foreach($ds as $item)
			{
				if($item['nguoinhan_id'] == $nguoinhan_id &&
				   (($b >= $item['ngay_batdau'] && $b <= $item['ngay_ketthuc'])
					|| ($e >= $item['ngay_batdau'] && $e <= $item['ngay_ketthuc'])))
					return false;
			}
		}
		return true;
	}
	
	public	function	chon_vanban()
	{
		if(!empty($this->request->data))
		{
			$this->loadModel('Vanban');
			$this->Vanban->unbindModel(array('hasMany' => array('Nhanvanban', 'Theodoivanban', 'Filevanban', 'Xulyvanban')), false);
			$this->Vanban->bindModel(
					array(
						'hasOne'	=>	array('Nhanvanban' => array('foreignKey'	=>	'vanban_id',
																	'className'	=>	'Nhanvanban'))
					), false
				);
			$conds = array();
			$conds['Nhanvanban.nguoi_nhan_id'] = $this->Auth->user('nhanvien_id');
			$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			if(!empty($this->request->data['Vanban']['start']) && !empty($this->request->data['Vanban']['end']))
			{
				$start_date = $this->Bin->vn2sql($this->request->data['Vanban']['start']);
				$end_date = $this->Bin->vn2sql($this->request->data['Vanban']['end']);
				$conds['ngay_phathanh BETWEEN ? AND ?'] = array($start_date, $end_date);
			}
			$data = $this->Vanban->find('all', array('conditions' => $conds, 'order' => 'Vanban.id DESC', 'limit' => 50));
			$this->set('data', $data);
			
			if(empty($data))
				$this->Session->setFlash('Không tìm thấy dữ liệu thỏa điều kiện tìm kiếm. Vui lòng thử lại.', 'flash_attention');
			$this->render('ketqua_vanban');
		}
	}
	
	
	public	function	update_progress($congviec_id = null, $refer = null)
	{
		$data = $this->Congviec->find('first', 
			array('conditions' => array('id' 				=> $congviec_id, 
										'nguoinhan_id' => $this->Auth->user('nhanvien_id')),
				  'recursive'	=>	-1));
		if(!empty($this->request->data))
		{
			/// files báo cáo đính kèm
			if(!empty($this->request->data['Filecongviec']))
			{
				$t['id'] = NULL;
				$t['congviec_id'] = $congviec_id;
				$t['nguoibaocao_id'] = $this->Auth->User('nhanvien_id');;
				$old = str_replace("/", DS, Configure::read('File.tmp'));
				$old = substr($old, 1, strlen($old)-1);
				$new = str_replace("/", DS, Configure::read('VanBan.attach_path'));
				$new = substr($new, 1, strlen($new)-1);
				//pr($new);die();
				foreach($this->request->data['Filecongviec'] as $item)
				{
					$t['ten_cu'] = $item['ten_cu'];
					$t['ten_moi'] = $item['ten_moi'];
					//pr(WWW_ROOT . $new . $item['ten_moi']);die();
					if(copy(WWW_ROOT . $old . $item['ten_moi'],  WWW_ROOT . $new . $item['ten_moi']))
						unlink(WWW_ROOT . $old . $item['ten_moi']);
					$this->loadModel('Filecongviec');
					if (!$this->Filecongviec->save($t)) 
					{
						break;
					}
				}
			}
			///
			if(empty($data)
					|| !array_key_exists($this->request->data['Congviec']['mucdo_hoanthanh'], $this->Congviec->progress))
				die('Lỗi.');
			$this->request->data['Congviec']['ngay_capnhat'] = date('Y-m-d H:i:s');
			//pr($this->request->data);die();
			if($this->Congviec->save($this->request->data))
			{
				$this->Session->setFlash('Đã cập nhật mức độ hoàn thành công việc.', 'flash_success');
				$this->loadModel('CongviecNhatky');
				
				$nhatky = array(
					'id'	=>	NULL,
					'ghi_chu'	=>	$this->request->data['Congviec']['ghi_chu'],
					'ngay_nhap'	=>	date('Y-m-d H:i:s'),
					'congviec_id'	=>	$this->request->data['Congviec']['id'],
					'capnhat_mucdo'	=>  $this->request->data['Congviec']['mucdo_hoanthanh'],
					'nguoi_capnhat'	=>	$this->Auth->user('nhanvien_id')
				);
				//pr($nhatky); die();
				$this->CongviecNhatky->save($nhatky);
				
			}else
				$this->Session->setFlash('Đã phát lỗi khi cập nhật công việc.', 'flash_error');
			//$this->redirect($this->referer());
			//$this->redirect('/congviec/view/' . $this->request->data['Congviec']['id']);
			$this->redirect('/congviec/duocgiao/#'.$this->request->data['Congviec']['refer']);
		}
		
		$progress = $this->Congviec->progress;
		$this->data = $data;
 		$this->set(compact(array('progress','refer')));
		
	}
	
	public	function	update_process($id = null)
	{
		$this->loadModel('Chitietcongviec');
		$data = $this->Chitietcongviec->find('first', 
			array('conditions' => array('id' 			=> $id, 
										'nguoinhan_id' 	=> $this->Auth->user('nhanvien_id')),
				  'recursive'	=>	-1));
		if(!empty($this->request->data))
		{
			if(empty($data)
					|| !array_key_exists($this->request->data['Chitietcongviec']['mucdo_hoanthanh'], $this->Chitietcongviec->progress))
				die('Lỗi.');
			$this->request->data['Chitietcongviec']['ngay_capnhat'] = date('Y-m-d H:i:s');
			if($this->Chitietcongviec->save($this->request->data))
			{
				$this->Session->setFlash('Đã cập nhật mức độ hoàn thành công việc.', 'flash_success');
				
			}else
				$this->Session->setFlash('Đã phát lỗi khi cập nhật mức độ hoàn thành công việc.', 'flash_error');
			$this->redirect($this->referer());
			//$this->redirect('/congviec/view/' . $data['Chitietcongviec']['congviec_id']);
		}
		
		$progress = $this->Chitietcongviec->progress;
		$this->data = $data;
		$this->set(compact(array('progress')));
		
	}
	
	public	function	edit_process($id = null)
	{
		if(!empty($this->request->data))
		{
			
			$this->request->data['Congviec']['ngay_batdau'] = $this->Bin->vn2sql($this->request->data['Congviec']['ngay_batdau']);
			$this->request->data['Congviec']['ngay_ketthuc'] = $this->Bin->vn2sql($this->request->data['Congviec']['ngay_ketthuc']);
			$this->request->data['Congviec']['giaoviec_tiep'] = isset($this->request->data['Congviec']['giaoviec_tiep']) ? 1 : 0;
			if($this->Congviec->save($this->request->data))
			{
				die(json_encode(array('success' => true)));
			}
			else
				die(json_encode(array('success' => false)));
				
		}else
		{
			$data = $this->Congviec->find('first', 
				array('conditions' => array(
										'Congviec.id'	=> $id, 
										'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'))
								));
		if(empty($data))								
			throw new InternalErrorException('Không tìm thấy dữ liệu.');
			$this->data = $data;
		}
	}
	
	public	function	add_process($congviec_id = null)
	{
		if(!empty($this->request->data))
		{
			//pr($this->request->data); die();
			$congviec = array(
				'ngay_batdau'	=>	$this->Bin->vn2sql($this->request->data['Congviec']['ngay_batdau']),
				'ngay_ketthuc'	=>	$this->Bin->vn2sql($this->request->data['Congviec']['ngay_ketthuc']),
				'giaoviec_tiep'	=> 	isset($this->request->data['Congviec']['giaoviec_tiep']) ? 1 : 0,
				'mucdo_hoanthanh'	=>	0,
				'nguoi_giaoviec_id'	=>	$this->Auth->user('nhanvien_id'),
				'ngay_giao'	=>	date('Y-m-d H:i:s'),
				'ten_congviec'	=>	$this->request->data['Congviec']['ten_congviec'],
				'noi_dung'		=>	$this->request->data['Congviec']['noi_dung'],
				'nguoinhan_id'	=>	$this->request->data['Congviec']['nguoinhan_id'],
				'parent_id'		=>	$this->request->data['Congviec']['parent_id'],
				'congviecchinh_id'	=>	$this->Congviec->field('congviecchinh_id', array('id' => $this->request->data['Congviec']['parent_id']))
			);
			if($this->Congviec->save($congviec))
			{
				$vanban_id = $this->Congviec->field('vanban_id', array('Congviec.id' => $this->request->data['Congviec']['parent_id']));
				if(!empty($vanban_id) && !$this->vanbangiaoviec($this->request->data['Congviec']['nguoinhan_id'], $vanban_id))
					die(json_encode(array('success' => false)));
				else	
					die(json_encode(array('success' => true)));
			}
			else
				die(json_encode(array('success' => false)));
				
		}else
		{
			$nv = $this->cboNhanvien('CongViec.khoitao');
			$this->set(compact('nv', 'congviec_id'));
		}
	}
	
	public	function	del_process()
	{
		if(!empty($this->request->data['id']))
		{
			$data = $this->Congviec->find('first', 
				array('conditions' => array(
										'Congviec.id'=> $this->request->data['id'], 
										'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id')),
					  'field'		=>	'id',
					  'recursive'	=>	-1
							));
			if(empty($data))								
				die(json_encode(array('success' => false, 'message' => 'Không tìm thấy dữ liệu')));
			if($this->Congviec->delete($this->request->data['id']))					
			{
				$this->Session->setFlash('Giao việc đã được xóa.', 'flash_success');
				die(json_encode(array('success' => true)));
			}
			else
				die(json_encode(array('success' => false, 'message' => 'Đã phát sinh lỗi khi xóa giao việc')));
		}else
			throw new InternalErrorException('Dữ kiện không hợp lệ.');
		
	}
	public	function	attachfile()
	{
		$this->loadModel('FileManager');
		
		$path = str_replace("/", DS, Configure::read('File.tmp'));
		$path = substr($path, 1, strlen($path)-1);
		
		$filename = $this->FileManager->upload($this->request->data, $path);
		if($filename !== false) 
		{
			App::uses('File', 'Utility');
			App::uses('CakeNumber', 'Utility');
			
			$file = new File(APP . WEBROOT_DIR . DS . $path . $filename);
			$file_info = $file->info();
			die(json_encode(array('success' => true, 'filename' => $filename, 'filesize' => CakeNumber::toReadableSize($file_info['filesize']))));
		}
		else
			die(json_encode(array('success' => false, 'message' => $this->FileManager->error)));
	}
	
	public	function	remove_attach()	// xem lại cái này
	{
		if(!$this->check_permission('CongViec.khoitao'))
			throw new InternalErrorException('Bạn không có quyền xóa file đính kèm. Vui lòng liên hệ quản trị để cấp quyền.');	
			
		if(!empty($this->request->data['key']))
		{
			$path = str_replace("/", DS, Configure::read('File.tmp'));
			$path = WWW_ROOT . substr($path, 1, strlen($path)-1);
			
			@unlink($path . $this->request->data['key']);	
			die(json_encode(array('success' => true, 'id' => $this->request->data['key'])));
		}
		die(json_encode(array('success' => false)));
	}
	
	private	function	vanbangiaoviec($nhanvien_id, $vanban_id = null)
	{
		if(is_null($vanban_id))
			return true;
		$this->loadModel('Nhanvanban');
		$n = $this->Nhanvanban->find('count', array('conditions' => array('vanban_id' => $vanban_id, 'nguoi_nhan_id' => $nhanvien_id), 'fields' => 'id'));
		//var_dump($n); die();
		if($n > 0)
			return true;
		$data['Nhanvanban'] = array(
			'id' => NULL, 
			'nguoi_nhan_id' => $nhanvien_id, 
			'vanban_id' => $vanban_id);
		$this->Nhanvanban->save($data);
		return true;
	}
	
	public	function	mobile_index()
	{
		$dagiao = true;
		$duocgiao = true;
		
		if(!$this->check_permission('CongViec.khoitao'))
			$dagiao = false;
		if(!$this->check_permission('CongViec.thuchien'))
			$duocgiao = false;
			
		$this->loadModel('Chitietcongviec');
		
		$duocgiao_chuahoanthanh = $this->Chitietcongviec->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'ngay_ketthuc <' => date('Y-m-d'))));
		$duocgiao_dangthuchien = $this->Chitietcongviec->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'ngay_ketthuc >=' => date('Y-m-d'))));
		$duocgiao_hoanthanh = $this->Chitietcongviec->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh' => 10)));
		$dagiao_chuahoanthanh = $this->Chitietcongviec->find('count', array('conditions' => array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'ngay_ketthuc <' => date('Y-m-d'))));
		$dagiao_dangthuchien = $this->Chitietcongviec->find('count', array('conditions' => array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'ngay_ketthuc >=' => date('Y-m-d'))));
		$dagiao_hoanthanh = $this->Chitietcongviec->find('count', array('conditions' => array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh' => 10)));
		$dagiao_khan = $this->Chitietcongviec->find('count', array('conditions' => array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'tinhchat_id' => 11)));
		$duocgiao_khan = $this->Chitietcongviec->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'tinhchat_id' => 11)));
		$this->set(compact('dagiao_hoanthanh', 'dagiao_dangthuchien', 'dagiao_chuahoanthanh', 'duocgiao_chuahoanthanh', 'duocgiao_dangthuchien', 'duocgiao_hoanthanh', 'dagiao', 'duocgiao', 'dagiao_khan','duocgiao_khan'));
	}
	
	
	public	function	mobile_dagiao($loai)
	{
		$page_title = '';
		
		$conds = array();
		$this->Congviec->recursive = 2;
		
		switch($loai)
		{
			case 'dangthuchien':
				$page_title = 'Công việc đang thực hiện';
				break;
				
			case 'dathuchien':
				$page_title = 'Công việc đã thực hiện';
				break;
				
			case 'tretiendo':
				$page_title = 'Công việc trễ tiến độ';
				break;
		}
		
		$this->set(compact('page_title', 'loai'));
	}
	
	public	function	mobile_dagiao_ajax($loai)
	{
		$conds = array();
		$this->loadModel('Chitietcongviec');
		//$this->Congviec->recursive = 2;
		$this->paginate = array(
					'limit'	=>	10,
					'order'	=>	'Chitietcongviec.id DESC'
				);
		switch($loai)
		{
			case 'dangthuchien':
				$conds = array(
							'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
							'mucdo_hoanthanh <'	=>	10,
							'ngay_ketthuc >='	=>	date('Y-m-d')
						);
				break;
				
			case 'dathuchien':
				$conds = array(
							'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
							'mucdo_hoanthanh '	=>	10
						);
				break;
				
			case 'tretiendo':
				$conds = array(
							'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
							'mucdo_hoanthanh <'	=>	10,
							'ngay_ketthuc <'	=>	date('Y-m-d'));
				break;
		}
		$ds =  $this->paginate('Chitietcongviec', $conds);
		$this->set(compact('ds'));
		$this->render('mobile_dagiao_ajax');
	}
	
	
	public	function	mobile_view($congviec_id = null)
	{
		if(!$this->check_permission('HeThong.toanquyen')
			&& $this->Congviec->find('count', 
							array('conditions' => array('id' 				=> $congviec_id, 
														'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id')), 
								  'recursive' => -1)) <= 0
			&& $this->Congviec->Chitietcongviec->find('count', 
							array('conditions' => array('congviec_id' 		=> $congviec_id, 
														'nguoinhan_id' 		=> $this->Auth->user('nhanvien_id')), 
								  'recursive' => -1)) <= 0
			)
			throw new InternalErrorException();
		
		$this->set('title_for_layout', 'Xem chi tiết công việc');
		
		$this->Congviec->bindModel(
			array(
				'belongsTo' => array(
					'Nguoigiaoviec' => array('className' => 'Nhanvien', 'foreignKey' => 'nguoi_giaoviec_id', 'fields' => 'Nguoigiaoviec.full_name'),
					'NguoiChiutrachnhiem' => array('className' => 'Nhanvien', 'foreignKey' => 'nv_chiutrachnhiem', 'fields' => array('NguoiChiutrachnhiem.full_name')),
					'Vanban'	=>	array('fields' => array('trich_yeu', 'so_hieu', 'ngay_phathanh', 'noi_gui')))
				)
			, false);
		$detail = $this->Congviec->Chitietcongviec->generateTree($congviec_id);
		$this->Congviec->unbindModel(array('hasMany' => array('Chitietcongviec')), false);
		$this->Congviec->recursive = 1;
		$data = $this->Congviec->read(null, $congviec_id);
		
//		pr($detail);die();
		$this->set(compact('data', 'detail'));
		
		/*
		$this->loadModel('Nhanvien');
		$nv_chiutrachnhiem = $this->Nhanvien->find('first', array('conditions' => array('id' => $data['Congviec']['nv_chiutrachnhiem']), 'fields' => array('id', 'full_name'), 'recursive' => -1));
		
		$this->set(compact('tinhchat', 'nv_chiutrachnhiem'));
		*/
	}
	
	
	public	function	mobile_update_maintask($congviec_id = null)
	{
		$data = $this->Congviec->find('first', 
			array('conditions' => array('id' 				=> $congviec_id, 
										'nv_chiutrachnhiem' => $this->Auth->user('nhanvien_id')),
				  'recursive'	=>	-1));
		if(!empty($this->request->data))
		{
			$this->request->data['maintask-mucdohoanthanh'] = $this->request->data['maintask-mucdohoanthanh']/10;
			if(empty($data)
					|| !array_key_exists($this->request->data['maintask-mucdohoanthanh'], $this->Congviec->Chitietcongviec->progress))
				die('Lỗi.');
			$task['Congviec'] = array(
				'id'		=>	$congviec_id,
				'mucdo_hoanthanh'	=>	$this->request->data['maintask-mucdohoanthanh'],
				'ngay_capnhat'	=>	date('Y-m-d H:i:s')
			);
			if($this->Congviec->save($task))
			{
				die(json_encode(array('success' => true, 'message' => 'Cập nhật mức độ hoàn thành công việc thành công')));
				
			}
				
		}
		die(json_encode(array('success' => false, 'message' => 'Đã phát lỗi khi cập nhật công việc')));
		
	}
	
	
	public	function	mobile_duocgiao($loai)
	{
		$page_title = '';
		
		$conds = array();
		$this->Congviec->recursive = 2;
		
		switch($loai)
		{
			case 'dangthuchien':
				$page_title = 'Công việc đang thực hiện';
				break;
				
			case 'dathuchien':
				$page_title = 'Công việc đã thực hiện';
				break;
				
			case 'tretiendo':
				$page_title = 'Công việc trễ tiến độ';
				break;
		}
		
		$this->set(compact('page_title', 'loai'));
	}
	
	public	function	mobile_duocgiao_ajax($loai)
	{
		$conds = array();
		$this->loadModel('Chitietcongviec');
		//$this->Congviec->recursive = 2;
		$this->paginate = array(
					'limit'	=>	10,
					'order'	=>	'Chitietcongviec.id DESC'
				);
		switch($loai)
		{
			case 'dangthuchien':
				$conds = array(
							'nguoinhan_id' 		=> $this->Auth->user('nhanvien_id'),
							'mucdo_hoanthanh <'	=>	10,
							'ngay_ketthuc >='	=>	date('Y-m-d')
							);
				break;
				
			case 'dathuchien':
				$conds = array(
							'nguoinhan_id' 		=> $this->Auth->user('nhanvien_id'),
							'mucdo_hoanthanh '	=>	10
							);
				break;
				
			case 'tretiendo':
				$conds = array(
								'nguoinhan_id' 		=> $this->Auth->user('nhanvien_id'),
								'mucdo_hoanthanh <'	=>	10,
								'ngay_ketthuc <'	=>	date('Y-m-d')
							);
				break;
		}
		$ds =  $this->paginate('Chitietcongviec', $conds);
		$this->set(compact('ds'));
		$this->render('mobile_duocgiao_ajax');
	}
	
	public	function	mobile_update_task($task_id = null)
	{
		$data = $this->Congviec->Chitietcongviec->find('first', 
			array('conditions' => array('id' 			=> $task_id, 
										'nguoinhan_id' 	=> $this->Auth->user('nhanvien_id')),
				  'recursive'	=>	-1));
		if(!empty($this->request->data))
		{
			$this->request->data['task-mucdohoanthanh'] = $this->request->data['task-mucdohoanthanh']/10;
			if(empty($data)
					|| !array_key_exists($this->request->data['task-mucdohoanthanh'], $this->Congviec->Chitietcongviec->progress))
				die('Lỗi.');
			$task['Chitietcongviec'] = array(
					'id'			=>	$task_id,
					'mucdo_hoanthanh'	=>	$this->request->data['task-mucdohoanthanh'],
					'ngay_capnhat'	=>	date('Y-m-d H:i:s')
			);
			
			if($this->Congviec->Chitietcongviec->save($task))
			{
				die(json_encode(array('success' => true, 'message' => 'Cập nhật mức độ hoàn thành công việc thành công')));
			}
		}
		die(json_encode(array('success' => false, 'message' => 'Đã phát lỗi khi cập nhật công việc')));
	}
	
	
	public	function	comments($congviec_id)
	{
		$this->loadModel('CongviecComment');
		
		$comments = $this->CongviecComment->find('all', array('conditions' => array('congviec_id' => $congviec_id), 'order' => 'CongviecComment.id ASC'));
		if(empty($comments))
			$this->Session->setFlash('Hiện tại chưa có thảo luận nào về công việc này.', 'flash_attention');
		$this->set(compact('comments'));
	}
	
	public	function	add_comment()
	{
		$this->loadModel('CongviecComment');
		if(!empty($this->request->data))
		{
			$data = array(
				'congviec_id'	=>	$this->request->data['congviec_id'],
				'nguoibinhluan_id'	=>	$this->Auth->user('nhanvien_id'),
				'noi_dung'		=>	$this->request->data['noi_dung'],
				'ngay_binhluan'	=>	date('Y-m-d H:i:s', time())
			);
			
			if($this->CongviecComment->save($data))
				die(json_encode(array('success' => true)));
		}
		die(json_encode(array('success' => false)));
				
	}
	
	public	function	nhatky($congviec_id)
	{
		$this->loadModel('CongviecNhatky');
		
		$ds = $this->CongviecNhatky->find('all', array('conditions' => array('congviec_id' => $congviec_id), 'order' => 'CongviecNhatky.id ASC'));
		if(empty($ds))
			$this->Session->setFlash('Hiện tại công việc này chưa được cập nhật.', 'flash_attention');
		$this->set(compact('ds'));
	}
	
	public	function	luongcongviec($congviec_id)
	{
		$ds_congviec = $this->Congviec->generateTree($congviec_id);
		if(empty($ds_congviec))
			throw new InternalErrorException();
		$this->set(compact('ds_congviec'));
	}

	
	public	function	attachfile_baocao()
	{
		if(empty($this->request->data['Congviec']))
			die(json_encode(array('success' => false, 'message' => 'Lỗi khi upload file')));
		$path =  $this->request->data['Congviec']['file']['name'];
		//pr($path);die();
		$ten_cu =  $path;
		$output = str_replace("/", DS, Configure::read('File.tmp'));
		//$ten_moi = md5(time());
		$ten_moi = md5(time() + rand());
		//$output = WWW_ROOT . substr($output, 1, strlen($output)-1) . $ten_moi. '.' . $ext;
		$output = WWW_ROOT . substr($output, 1, strlen($output)-1) . $ten_moi;
		//pr($output);die();
		if(move_uploaded_file($this->request->data['Congviec']['file']['tmp_name'], $output))
			//die(json_encode(array('success' => true, 'ten_cu' => $ten_cu, 'ten_moi' => $ten_moi . '.' . $ext )));
			die(json_encode(array('success' => true,'path' => $path, 'ten_cu' => $ten_cu, 'ten_moi' => $ten_moi )));
		else
			die(json_encode(array('success' => false, 'message' => 'Lỗi')));
	}

	public	function	remove_attach_baocao()	// xem lại cái này
	{
		//if(!$this->check_permission('VanBan.gui'))

			//throw new InternalErrorException('Bạn không được phép xóa văn bản này.');
		if(!empty($this->request->data['key']))
		{
			$path = str_replace("/", DS, Configure::read('File.tmp'));
			$path = WWW_ROOT . substr($path, 1, strlen($path)-1);
			@unlink($path . $this->request->data['key']);	
			die(json_encode(array('success' => true, 'id' => $this->request->data['key'])));
		}
		die(json_encode(array('success' => false)));
	}
	
	function	files_att($id = null) 
	{
		$this->Congviec->bindModel(array(
			'hasOne'	=>	array('Filecongviec' => array('foreignKey' => 'congviec_id')
							),
		), false);
		$this->loadModel('Filecongviec');
		$congviec = $this->Filecongviec->find('first', array('conditions' => array('Filecongviec.id' => $id)));
		//pr($congviec);die();
		$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
		$path = substr($path, 1, strlen($path)-1);
		$path = WWW_ROOT . $path;
		$file_moi = $congviec['Filecongviec']['ten_moi'];
		$file_cu = $congviec['Filecongviec']['ten_cu'];
		$file_contents = file_get_contents($path.$file_moi, true);
		$this->layout = null;
		Configure::write('debug',0);
		header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'. $file_cu .'"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path.$file_moi));
		ob_clean();
		flush();
		readfile($path.$file_moi);
		exit;

	}

	
	public	function	mark_read()
	{
		$this->layout = null;
		$f = false;
		//pr($this->request->data);die();
		if(!empty($this->request->data))
		{
			$this->loadModel('CongviecNhatky');
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			//pr($ids);die();
			foreach($ids as $k=>$v)
			{
			///////////////
				$this->loadModel('CongviecNhatky');

				//add vao NhatkyCongviec
				$nhatky = array(
					'id'	=>	NULL,
					'ghi_chu'	=>	'Cập nhật hoàn thành công việc theo chức năng đánh dấu(vì những công việc không liên quan)',
					'ngay_nhap'	=>	date('Y-m-d H:i:s'),
					'congviec_id'	=>	$v,
					'capnhat_mucdo'	=>  10,
					'nguoi_capnhat'	=>	$this->Auth->user('nhanvien_id')
				);
				$this->CongviecNhatky->save($nhatky);

				//update muc do hoan thanh
				$this->Congviec->updateAll(array('mucdo_hoanthanh' => 10), array('Congviec.id' => $v, 'nguoinhan_id' => $this->Auth->user('nhanvien_id')));
			//////////////
				$success++;
			}//end foreach
			if($success > 0)
				$f = true;
		
		}
		if($f)
			$this->Session->setFlash('Đã đánh dấu cập nhật thành công ' . $success . ' mục.', 'flash_success');
		else
			$this->Session->setFlash('Đã phát sinh lỗi khi đánh dấu cập nhật công việc.', 'flash_error');
		$this->redirect('/congviec/duocgiao/opt:' . $this->passedArgs['type']);
	}

}