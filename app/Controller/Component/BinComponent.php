<?php
/**
 * BIN Component
 *
 * PHP version 5
 *
 * @category Component
 * @package  BIN
 * @version  1.0
 * @author   Nguyen Dinh Thanh <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.ptc.com.vn
 */
class BinComponent extends Component {

/**
 * Other components used by this component
 *
 * @var array
 * @access public
 */
	public $components = array(
		'Session',
	);

/**
 * controller
 *
 * @var Controller
 */
	protected $controller = null;

/**
 * Startup
 *
 * @param object $controller instance of controller
 * @return void
 */
	public function startup(Controller $controller) {
		$this->controller =& $controller;

	}

/**
 * convert VN datetime format to SQL datetime.
 *
 * Example: DD-MM-YYYY -> YYYY-MM-DD
 *
 * Array
 * (
 *     [main] => Array
 *         (
 *             [option1] => value
 *         )
 * )
 *
 * @param string $datetime
 * @param string $separator
 * @return string
 */	
 	public	function	vn2sql($datetime, $separator = '-')
	{
		//$datetime = str_replace($separator, '-', $datetime);
		//$datetime = DateTime::createFromFormat('d-m-Y', $datetime);
		$arr = explode($separator, $datetime);
		if(count($arr) != 3) 
			return false;
		return $arr[2] . $separator . $arr[1] . $separator . $arr[0];
		//return $datetime->format('Y-m-d');
	}
	
	
	public	function	sql2vn($datetime, $separator = '-')
	{
		$arr = explode($separator, $datetime);
		if(count($arr) != 3) 
			return false;
		return $arr[2] . $separator . $arr[1] . $separator . $arr[0];
		//return $datetime->format('Y-m-d');
	}
	
	public	function	rndString($length = 8)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		$str = '';

		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
	
		return $str;
	}

/**
 * Parses bb-code like string.
 *
 * Example: string containing [menu:main option1="value"] will return an array like
 *
 * Array
 * (
 *     [main] => Array
 *         (
 *             [option1] => value
 *         )
 * )
 *
 * @param string $exp
 * @param string $text
 * @param array  $options
 * @return array
 */
	public function parseString($exp, $text, $options = array()) {
		$_options = array(
			'convertOptionsToArray' => false,
		);
		$options = array_merge($_options, $options);

		$output = array();
		preg_match_all('/\[(' . $exp . '):([A-Za-z0-9_\-]*)(.*?)\]/i', $text, $tagMatches);
		for ($i = 0, $ii = count($tagMatches[1]); $i < $ii; $i++) {
			$regex = '/(\S+)=[\'"]?((?:.(?![\'"]?\s+(?:\S+)=|[>\'"]))+.)[\'"]?/i';
			preg_match_all($regex, $tagMatches[3][$i], $attributes);
			$alias = $tagMatches[2][$i];
			$aliasOptions = array();
			for ($j = 0, $jj = count($attributes[0]); $j < $jj; $j++) {
				$aliasOptions[$attributes[1][$j]] = $attributes[2][$j];
			}
			if ($options['convertOptionsToArray']) {
				foreach ($aliasOptions as $optionKey => $optionValue) {
					if (!is_array($optionValue) && strpos($optionValue, ':') !== false) {
						$aliasOptions[$optionKey] = $this->stringToArray($optionValue);
					}
				}
			}
			$output[$alias] = $aliasOptions;
		}
		return $output;
	}
	
	public	function	hidetimelabel( $filename )
	{
		$pos = strpos($filename, '_');
		
		if($pos < 0)	return $filename;
		
		return substr( $filename, $pos + 1,  strlen( $filename ) - $pos - 1 );
	}
	
	public	static function	slug($string)
	{
		$viet = array(
			"à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
			"ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề"
			,"ế","ệ","ể","ễ",
			"ì","í","ị","ỉ","ĩ",
			"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
			,"ờ","ớ","ợ","ở","ỡ",
			"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
			"ỳ","ý","ỵ","ỷ","ỹ",
			"đ",
			"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
			,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
			"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
			"Ì","Í","Ị","Ỉ","Ĩ",
			"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
			,"Ờ","Ớ","Ợ","Ở","Ỡ",
			"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
			"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
			"Đ"," ");
			
			$ascii = array(
			"a","a","a","a","a","a","a","a","a","a","a"
			,"a","a","a","a","a","a",
			"e","e","e","e","e","e","e","e","e","e","e",
			"i","i","i","i","i",
			"o","o","o","o","o","o","o","o","o","o","o","o"
			,"o","o","o","o","o",
			"u","u","u","u","u","u","u","u","u","u","u",
			"y","y","y","y","y",
			"d",
			"A","A","A","A","A","A","A","A","A","A","A","A"
			,"A","A","A","A","A",
			"E","E","E","E","E","E","E","E","E","E","E",
			"I","I","I","I","I",
			"O","O","O","O","O","O","O","O","O","O","O","O"
			,"O","O","O","O","O",
			"U","U","U","U","U","U","U","U","U","U","U",
			"Y","Y","Y","Y","Y",
			"D","-");
		
		$string = strtolower(str_replace($viet, $ascii, $string));
		$string = strtolower($string);
		$string = preg_replace('/[^a-z0-9_]/i', '_', $string);
		$string = preg_replace('/' . preg_quote('_') . '[' . preg_quote('_') . ']*/', '_', $string);

		if (strlen($string) > 64)
		{
			$string = substr($string, 0, 64);
		}

		$string = preg_replace('/' . preg_quote('_') . '$/', '', $string);
		$string = preg_replace('/^' . preg_quote('_') . '/', '', $string);

		return $string;
	}
	
	public	function	check($permission, $owner_id)
	{
		$ds = $this->Session->read('Auth.User.quyen');
		if(array_key_exists('HeThong.toanquyen', $ds))
			return true;
			
		if(!array_key_exists($permission, $ds))
			return false;
			
		if($ds[$permission] == 0)	// toàn đơn vị
			return true;
			
		if($ds[$permission] == 1)	// phòng đang công tác
		{
			App::import('Nhanvien', 'Model');
			$nhanvien = new Nhanvien();
			$phong_id = $nhanvien->field('phong_id', array('id' => $owner_id));
			return ($phong_id == $this->Session->read('Auth.User.phong_id'));
		}
		if($ds[$permission] == 2)	// cá nhân
		{
			return $owner_id == $this->Session->read('Auth.User.nhanvien_id');
		}
	}

}
