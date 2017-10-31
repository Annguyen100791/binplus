<?php
/**
 * Quyen
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Quyen extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Quyen';
	
	public $tablePrefix = 'sys_';
	
	public	$prefix = array("HeThong"	=>	"Hệ thống",
							"VanBan"	=>	"Văn bản",
							"CongViec"	=>	"Công việc",
							"LichCongTac"=>	"Lịch công tác",
							"NhanSu"	=>	"Nhân sự",
							"TinNhan"	=>	"Tin nhắn",
							"ThongBao"	=>	"Thông báo",
							"BaoCao"	=>	"Báo cáo");
	
}
?>