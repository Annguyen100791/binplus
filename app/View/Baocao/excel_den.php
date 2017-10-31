<?php
	//input the export file name
	$this->Xls->setHeader('sotheodoi_'.date('Y_m_d'));
	
	$this->Xls->addXmlHeader();
	$this->Xls->addXmlStyle();
	$this->Xls->setWorkSheetName('Văn bản đến');
	
	//1st row for columns name
	$this->Xls->openRow(4);
	$this->Xls->writeStringHeader('STT');
	$this->Xls->writeStringHeader('Số văn bản đến');
	$this->Xls->writeStringHeader('Ngày văn bản đến');
	$this->Xls->writeStringHeader('Số hiệu VB');
	$this->Xls->writeStringHeader('Ngày phát hành');
	$this->Xls->writeStringHeader('Nơi phát hành');
	$this->Xls->writeStringHeader('Trích yếu');
	$this->Xls->closeRow();
	
	//rows for data
	
	$i = 0;
	foreach ($ds as $item):
		$i++;
		$this->Xls->openRow();
		$this->Xls->writeStringContent($i);
		$this->Xls->writeStringContent($item['Vanban']['so_hieu_den']);
		$this->Xls->writeStringContent($this->Time->format("d-m-Y", $item['Vanban']['ngay_nhan']));
		$this->Xls->writeStringContent($item['Vanban']['so_hieu']);
		$this->Xls->writeStringContent($this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh']));
		$this->Xls->writeStringContent($item['Vanban']['noi_gui']);
		$this->Xls->writeStringContent($item['Vanban']['trich_yeu']);
		$this->Xls->closeRow();
	endforeach;
	
	$this->Xls->addXmlFooter();
	exit();
?>