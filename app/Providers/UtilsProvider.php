<?php


namespace App\Providers;


class UtilsProvider {


	// pretty print arrays and vars
	public function pp($a) {
		if ($a) {
			if (is_array($a)) {
				echo "<hr><pre>",print_r($a,TRUE),"</pre><hr>";
			} else {
				echo "<hr><pre>{$a}</pre><hr>";
			}
		}
	}

	// remove nested directories
	public function recursiveRemoveDirectory($directory) {
		foreach (glob("{$directory}/*") as $file) {
			if (is_dir($file)) {
				$this->recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		@rmdir($directory);
	}

	// check UTF8 string
	public function isUTF8($str) {
		return (bool)preg_match('//u',$str);
	}

	// XLS compatible string
	public function xlsVal($v,$find=array(),$replace=array()) {
		$val = ($this->isUTF8($v))? $v :utf8_encode(stripslashes($v));
		// quotes and tab char forces values to be displayed as text in MS Excel
		$val='"'.str_replace($find,$replace,$val)."\"\t";
		return $val;
	}

	// convert filename to human readable form
	public function hrname($str,$uppercase=FALSE) {
		$out=str_replace(array('-','_'),' ',strtolower($str));
		return ($uppercase)?strtoupper($out):ucwords($out);
	}

	// convert string for sorting
	public function sortname($str) {
		return str_replace(array(' ','_','-','&'),'',strtolower($str));
	}

	//convert string to filename
	public function filename($str) {
		$out=htmlspecialchars_decode($str);
		$out=str_replace(array(' ',':',';','&','/','\\'),'-',strtolower($out));
		return mb_ereg_replace("([-]{2,})",'-',$out);
	}

	// convert h:m:s to h:m
	public function fixDbTime($t) {
		if ($t) {
			$a=explode(':',$t);
			return "{$a[0]}:{$a[1]}";
		} else {
			return '';
		}
	}

	// convert db table names to class names
	public function fixDbName($str) {
		$out=str_replace('_','-',strtolower($str));
		return $out;
	}

	// create teaser text, number of words from string
	public function teaser($txt,$num=50) {
		$txt=strip_tags(stripslashes($txt),"");
		$words=explode(" ",$txt);
		if (count($words)>$num) {
			$teaser=array_slice($words,0,$num);
			$txt=implode(" ",$teaser)."...";
		}
		return $txt;
	}

	// create teaser text, number of chars from string
	public function teaserChars($txt,$num=100) {
		$txt=strip_tags(stripslashes($txt),"");
		return (strlen($txt)>$num)?substr($txt,0,$num)."...":$txt;
	}

	// add leading zero
	public function lz($n) {
		return ($n<10)?"0{$n}":$n;
	}

	// create unique sorted array
	public function unique($array) {
		$unique=array_intersect_key($array,array_unique(array_map('serialize',$array)));
		$vals=array();
		foreach ($unique as $key=>$row) {
			$vals[$key]=$row['val'];
		}
		array_multisort($vals,SORT_ASC,$unique);
		return $unique;
	}

	// multidimensional in_array public function
	public function in_array_r($needle,$haystack,$strict=FALSE) {
		foreach ($haystack as $item) {
			if (($strict?$item===$needle:$item==$needle) ||
				(is_array($item) && in_array_r($needle,$item,$strict))) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function diff($a,$b) {
		return array_keys(array_diff_assoc($a,$b));
	}

	// extract POST
	public function postvars() {
		foreach ($_POST as $k=>$v) {
			$$k=(!get_magic_quotes_gpc())?addslashes($v):$v;
		}
	}

	//extract GET
	public function getvars() {
		foreach ($_GET as $k=>$v) {
			$$k=(!get_magic_quotes_gpc())?addslashes($v):$v;
		}
	}

	// date formatting
	public function dateUkToSql($ukdate) {
		return implode('-',array_reverse(explode('/',$ukdate)));
	}

	// pretty file sizes
	public function formatBytes($size,$precision=2) {
		$base=log($size,1024);
		$suffixes=array('','K','M','G','T');
		return round(pow(1024,$base-floor($base)),$precision).' '.$suffixes[floor($base)];
	}

	// form selectors ==================
	public function radio($a,$b) {
		if (is_array($a)) {
			echo (in_array($b,$a))?' checked="checked"':'';
		} else {
			echo ($a==$b)?' checked="checked"':'';
		}
	}

	public function select($a,$b) {
		echo ($a==$b)?' selected="selected"':'';
	}

	public function checkbox($a,$b) {
		if (is_array($b)) {
			echo (in_array($a,$b))?' checked="checked"':'';
		} else {
			echo ($a==$b)?' checked="checked"':'';
		}
	}

	public function selectmulti($a,$b) {
		if (is_array($b)) {
			echo (in_array($a,$b))?' selected="selected"':'';
		} else {
			echo ($a==$b)?' selected="selected"':'';
		}
	}

	// text formatting public functions ==================
	public function nf($num) {
		return (is_numeric($num))?number_format($num,2):NULL;
	}

	public function _gbp($num) {
		return (is_numeric($num))?'£'.number_format($num,2):NULL;
	}

	public function _br($str) {
		return (trim($str)!='')?$str.'<br />':'';
	}

	public function _nl($str) {
		return (trim($str)!='')?$str."\n":'';
	}

	public function _sp($str) {
		return (trim($str)!='')?$str.'&nbsp;':'';
	}

	public function _cm($str,$prefix=FALSE) {
		if ($prefix) {
			return (trim($str)!='')?', '.$str:'';
		} else {
			return (trim($str)!='')?$str.', ':'';
		}
	}

	public function yn($v,$full=FALSE) {
		$t=($full)?'Yes':'Y';
		$f=($full)?'No':'N';
		return ($v)?$t:$f;
	}

	public function _par($str) {
		return (trim($str)!='')?"({$str})":'';
	}

	public function _d($d,$today=FALSE) {
		if (!$d || empty($d) || $d==NULL || $d=='0000-00-00' || $d=='') {
			return ($today)?date('Y-m-d'):'';
		}
		return $d;
	}

	public function ordinal($number) {
		$ends=array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number%100)>=11) && (($number%100)<=13)) {
			return $number.'th';
		} else {
			return $number.$ends[$number%10];
		}
	}

	public function startsWith($haystack,$needle) {
		$length=strlen($needle);
		return (substr($haystack,0,$length)===$needle);
	}

	public function endsWith($haystack,$needle) {
		$length=strlen($needle);
		return ($length==0)?TRUE:substr($haystack,-$length)===$needle;
	}

	public function trimLast($str) {
		return substr($str,0,strlen($str)-1);
	}

	public function checkUrl($str) {
		if ($str=='') {
			return $str;
		}
		if ($this->startsWith($str,'www')) {
			return 'http://'.$str;
		}
		if (!$this->startsWith($str,'http')) {
			return 'http://'.$str;
		}
		return $str;
	}

	public function hl($str) {
		if ($_GET['s'] && stripos($str,$_GET['s'])!==FALSE) {
			$needle=trim($_GET['s']);
			$str=preg_replace("/($needle)/i",'<span class="highlight">$1</span>',$str);
		}
		return $str;
	}

	// return client IP
	public function getIP() {
		$ip=NULL;
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	/*
	// builds array from DB ENUM values, ensuring non-zero indexing
	public function enumSelect($schema) {
		reset($schema); $key = key($schema);
		$opts = array(); $arr = array();
		if($schema[$key]['default']) { $opts[] = $schema[$key]['default']; }
		eval('$arr = '.str_replace('enum','array',$schema[$key]['type']).';');
		$merge = array_unique(array_merge($opts,$arr));
		array_unshift($merge,NULL); unset($merge[0]);
		return $merge;
	}
	// get a DB value
	public function getDBVal($field,$table,$id) {
		$res = _get('DB')->exec("SHOW KEYS FROM $table WHERE Key_name='PRIMARY';",NULL,0,false);
		$pk = $res[0]['Column_name'];
		if(is_array($field)){
			$fields = implode(',',$field);
			$sql = "SELECT $fields FROM $table WHERE $pk=?;";
			$r = _get('DB')->exec($sql,array(1=>intval($id)),0,false) or die(_get('DB')->log());
			return ($r) ? reset($r) : array();
		} else {
			$sql = "SELECT $field FROM $table WHERE $pk=?;";
			$r = _get('DB')->exec($sql,array(1=>intval($id)),0,false);
			return ($r) ? $r[0][$field] : '';
		}
	}
	// get a DB record
	public function getRow($table,$id) {
		$res = _get('DB')->exec("SHOW KEYS FROM {$table} WHERE Key_name='PRIMARY';",NULL,0,false);
		$pk = $res[0]['Column_name'];
		$dataObj = new \DB\SQL\Mapper(_get('DB'), $table);
		$dataObj->load(array("{$pk}=?",intval($id)));
		if($dataObj->dry()) {
			return array();
		} else {
			return $dataObj->cast();
		}
	}
	*/

	// duration in days
	public function getDays($sStartDate,$sEndDate) {
		// This public function works best with YYYY-MM-DD but other date formats will work thanks to strtotime().
		$aDays[]=$sStartDate;
		// Set a 'temp' variable, sCurrentDate, with the start date - before beginning the loop
		$sCurrentDate=$sStartDate;
		// While the current date is less than the end date
		while ($sCurrentDate<$sEndDate) {
			// Add a day to the current date
			$sCurrentDate=date("Y-m-d",strtotime("+1 day",strtotime($sCurrentDate)));
			// Add this new day to the aDays array
			$aDays[]=$sCurrentDate;
		}
		// Once the loop has finished, return the array of days.
		return $aDays;
	}

	// APP-SPECIFIC FUNCTIONS ===================================

	// get media document from admin server
	public function getDoc($pid,$type,$id) {
		$fn = UtilsProvider::filename("{$type}-{$id}");
		$path = env('ADMIN_SERVER')."media/properties/{$pid}/documents/{$fn}.*";
		$list = glob($path);
		if ($list) {
			return reset($list);
		} else {
			return NULL;
		}
	}

	public function getTenancyDoc($tid,$type,$id) {
		$fn = UtilsProvider::filename("{$type}-{$id}");
		$path = env('ADMIN_SERVER')."media/tenancies/{$tid}/documents/{$fn}.*";
		$list = glob($path);
		if ($list) {
			return reset($list);
		} else {
			return NULL;
		}
	}

	public function getInventory($id) {
		$path = env('ADMIN_SERVER')."media/tenancies/{$id}/inventory.*";
		$list = glob($path);
		if ($list) {
			return reset($list);
		} else {
			return NULL;
		}
	}

	public function telLink($tel) {
		$tel = str_replace(array(' ','-','.',')','('),'',$tel);
		if (substr($tel,0,1)=='+') {
			return "tel:$tel";
		} elseif (substr($tel,0,1)=='0') {
			$tel='+44'.substr($tel,1);
			return "tel:$tel";
		}
		return 'javascript://';
	}

	// alert JS options
	public static function toastr_options() {
		return '"closeButton": false,"debug": false,"newestOnTop": false,"progressBar": false,"positionClass": "toast-top-center","preventDuplicates": true,"onclick": null,"showDuration": "250","hideDuration": "250","timeOut": "2000","extendedTimeOut": "1000","showEasing": "swing","hideEasing": "linear","showMethod": "fadeIn","hideMethod": "fadeOut"';
	}

	public function contact_sort($unsorted) {
		$sorted = [];
		foreach ($unsorted as $foo) {
			$sorted[$foo['contact_name']]=$foo;
		}
		ksort($sorted);
		return $sorted;
	}
}