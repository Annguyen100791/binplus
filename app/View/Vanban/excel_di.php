<?php
	//input the export file name
	$this->Xls->setHeader('sotheodoi_'.date('Y_m_d'));
	
	$this->Xls->addXmlHeader();
	$this->Xls->addXmlStyle();
	$this->Xls->setWorkSheetName('Văn bản đi');
	
	//1st row for columns name
	$this->Xls->openRow(4);
	$this->Xls->writeStringHeader('STT');
	$this->Xls->writeStringHeader('Số hiệu văn bản');
	$this->Xls->writeStringHeader('Ngày phát hành');
	$this->Xls->writeStringHeader('Trích yếu');
	$this->Xls->writeStringHeader('Người ký');
	$this->Xls->writeStringHeader('Nơi nhận');
	$this->Xls->writeStringHeader('Nơi lưu bản gốc');
	$this->Xls->writeStringHeader('Ghi chú');
	
	$this->Xls->closeRow();
	
	//rows for data
	
	$i = 0;
	foreach ($ds as $item):
		$i++;
		$this->Xls->openRow();
		$this->Xls->writeStringContent($i);
		$this->Xls->writeStringContent($item['Vanban']['so_hieu']);
		$this->Xls->writeStringContent($this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh']));
		$this->Xls->writeStringContent($item['Vanban']['trich_yeu']);
		$this->Xls->writeStringContent($item['Vanban']['nguoi_ky']);
		$this->Xls->writeStringContent("");
		$this->Xls->writeStringContent($item['Vanban']['Noiluu']['ten_phong']);
		$this->Xls->writeStringContent("");
		$this->Xls->closeRow();
	endforeach;
	
	$this->Xls->addXmlFooter();
	exit();
?>