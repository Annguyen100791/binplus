<?php
	//input the export file name
	$this->Xls->setHeader('sotheodoi_'.date('Y_m_d'));
	
	$this->Xls->addXmlHeader();
	$this->Xls->addXmlStyle();
	$this->Xls->setWorkSheetName('Danh sách văn bản');
	
	//1st row for columns name
	$this->Xls->openRow(4);
	$this->Xls->writeStringHeader('STT');
	$this->Xls->writeStringHeader('Chiều');
	$this->Xls->writeStringHeader('Số hiệu văn bản');
	$this->Xls->writeStringHeader('Số văn bản đến');
	$this->Xls->writeStringHeader('Tính chất');
	$this->Xls->writeStringHeader('Ngày phát hành');
	$this->Xls->writeStringHeader('Nơi phát hành');
	$this->Xls->writeStringHeader('Trích yếu');
	$this->Xls->writeStringHeader('Người ký');
	$this->Xls->writeStringHeader('Nơi lưu bản gốc');
	$this->Xls->writeStringHeader('Ghi chú');
	
	$this->Xls->closeRow();
	
	//rows for data
	
	$i = 0;
	foreach ($ds as $item):
		$i++;
		$this->Xls->openRow();
		$this->Xls->writeStringContent($i);
		$this->Xls->writeStringContent($chieu_di[$item['Vanban']['chieu_di']]);
		$this->Xls->writeStringContent($item['Vanban']['so_hieu']);
		$this->Xls->writeStringContent($item['Vanban']['so_hieu_den']);
		$this->Xls->writeStringContent($item['Tinhchatvanban']['ten_tinhchat']);
		$this->Xls->writeStringContent($this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh']));
		$this->Xls->writeStringContent($item['Vanban']['noi_gui']);
		$this->Xls->writeStringContent($item['Vanban']['trich_yeu']);
		$this->Xls->writeStringContent($item['Vanban']['nguoi_ky']);
		$this->Xls->writeStringContent($item['Noiluu']['ten_phong']);
		$this->Xls->writeStringContent("");
		$this->Xls->closeRow();
	endforeach;
	
	$this->Xls->addXmlFooter();
	exit();
?>