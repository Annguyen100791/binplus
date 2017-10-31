<?php
	//input the export file name
	//$this->Xls->setHeader('sotheodoi_'.date('Y_m_d'));
	
	$this->Xls->addXmlHeader();
	$this->Xls->addXmlStyle();
	$this->Xls->setWorkSheetName('Công việc đã giao');
	
	//1st row for columns name
	$this->Xls->openRow(4);
	$this->Xls->writeStringHeader('STT');
	$this->Xls->writeStringHeader('Tên công việc');
	$this->Xls->writeStringHeader('Người nhận việc');
	$this->Xls->writeStringHeader('Ngày bắt đầu');
	$this->Xls->writeStringHeader('Ngày hoàn thành');
	$this->Xls->writeStringHeader('Giao việc theo văn bản');
	$this->Xls->writeStringHeader('Mức độ hoàn thành');
	$this->Xls->closeRow();
	
	//rows for data
	
	$i = 0;
	foreach ($data as $item):
		$i++;
		$this->Xls->openRow();
		$this->Xls->writeStringContent($i);
		$this->Xls->writeStringContent($item['Congviec']['ten_congviec']);
		$this->Xls->writeStringContent($item['NguoiNhanviec']['full_name']);
		$this->Xls->writeStringContent($this->Time->format("d-m-Y", $item['Congviec']['ngay_batdau']));
		$this->Xls->writeStringContent($this->Time->format("d-m-Y", $item['Congviec']['ngay_ketthuc']));
		if(!empty($item['Congviec']['vanban_id']))
			$this->Xls->writeStringContent($item['Vanban']['so_hieu'] . ' - ' . $item['Vanban']['trich_yeu']);
		else
			$this->Xls->writeStringContent('');
		$this->Xls->writeStringContent($item['Congviec']['mucdo_hoanthanh']*10 . '%');
		$this->Xls->closeRow();
	endforeach;
	
	$this->Xls->addXmlFooter();
	exit();
?>