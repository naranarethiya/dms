<?php

function generateOTP() {
	$password=random_string("numeric",4);
    return $password;
}

function token() {
	$md5=md5(uniqid(rand(), true));
	return substr($md5,2,8);
}

/*
*	add minute in time
*/
function add_time($min) {
	$now = time();
	$add_time = $now + ($min * 60);
	$end_time = date('Y-m-d H:i:s', $add_time);
	return $end_time;
}

function checkIfInArrayString($array, $searchingFor) {
	$i=0;
    foreach ($array as $key=>$element) {
        if (strpos($element, $searchingFor) !== false) {
            return array('index'=>$i,'value'=>$key);
        }
		$i++;
    }
    return false;
}

function dateformat($date,$format=false) {
	if(!$format) {
		$format="d-m-Y";
	}
	return date($format,strtotime($date));
}

function calc_age($date) {
  if($date=='0000-00-00') {
    return false;
  }
  $old_date=date_create($date);
  $cur_date=date_create(date('Y-m-d'));
  $age = $old_date->diff($cur_date)->y;
  return $age;
}
/*
* You can pass column and value pair to apply filter on your active records
* You can also pass limit clouse by naming key as upper case LIMIT
* You can alos pass for order_by by naming  key as upper case ORDER_BY and order by array contain key as column and value as asc or desc
* 
* Ex. $filter=array(
*        "column1" =>'value1',
*        "column2 <" =>'value2',
*        "column3 >"=>'value3',
*        "LIMIT'=>'0,30',
*        "ORDER_BY"=>
*          array(
*            'column1'=>'asc',
*            'column2'=>'desc'
*          ),
*        "WHERE"=>"column1 in (2,5,7) or column2='5'"
*      );
*
*/

function set_message($message,$type="error") {
  $CI =& get_instance();
  if($type=="error") {
    $add=$CI->session->userdata('error');
    $set_message=$add."<li>".$message."</li>";
    $CI->session->set_userdata('error',$set_message);
  }
  else {
    $add=$CI->session->userdata('success');
    $set_message=$add."<li>".$message."</li>";
    $CI->session->set_userdata('success',$set_message);
  }
  //dsm($CI->session->all_userdata());die;
}

function apply_filter($filter) {
  $CI=& get_instance();
  if(is_array($filter)) {
      foreach($filter as $key => $val) {

        /* limit */
        if($key==='LIMIT') {
            if(is_array($val)) {
                $CI->db->limit($val[0],$val[1]);
            }
            else {
              $CI->db->limit($val);
            }
        }

        /* for more complex where 
            ex:name='Joe' AND status='boss' OR status='active'
        */
        else if($key==='WHERE') {
          $CI->db->where($val,null,FALSE);
        }
        else if($key==='WHERE_IN') {
           foreach($val as $column => $value) {
              $CI->db->where_in($column,$value);
            }
          
        }
        else if($key==='HAVING') {
          if(is_array($val)) {
            foreach($val as $col=>$value) {
              $CI->db->having($col,$value);
            }
          }
          else {
            $CI->db->having($val,null,FALSE);
          }
        }

        /* order by */
        elseif($key=='ORDER_BY') {
          foreach($val as $col => $order) {
            $CI->db->order_by($col,$order);
          }
        }

        /* simple key=>value where condtions */
        else {
          $CI->db->where($key,$val);  
        }

      }
    }
}


function get_thumb($image,$thumb) {
  if($image=='') {
    return "";
  }
  $url_array=explode('/',$image);
  $path_array=pathinfo($image);
  $last=count($url_array);
  $url_array[$last]=$path_array['filename'].'_thumb.'.$path_array['extension'];
  $url_array[$last-1]=$thumb;
  $thumb_url=implode('/',$url_array);
  return $thumb_url;
}

function parent_child_array($array,$parent_col) {
  $return = array();
  foreach($array as $key=>$row) {
     if (!isset($return[$row[$parent_col]])) {
        $return[$row[$parent_col]] =$row;
        $return[$row[$parent_col]]['child'] =array();
     }
     else {
        $return[$row[$parent_col]]['child'][] =$row;
     }
  }
  return $return;
}


function redirect_back() {
  if(isset($_SERVER['HTTP_REFERER'])) {
    $url=$_SERVER['HTTP_REFERER'];  
  }
  else {
      $url=base_url();
  }
  redirect($url);
}

function dsm($var) {
  if(is_array($var) || is_object($var)) {
    echo "<pre>".print_r($var,true)."</pre>";
  }
  else {
    echo "<pre>".$var."</pre>";
  }
  $debug=debug_backtrace();
  echo "<pre>".$debug[0]['file'].", line :".$debug[0]['line']."</pre>";
}

function print_last_query() {
  $CI =& get_instance();
  dsm($CI->db->last_query());
}

function curl_send($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	curl_close($curl);
	return $curl_response;
}

function replaces($string,$array) {
	foreach($array as $key=>$val) {
		$string=str_replace('|*'.$key.'*|',$val,$string);
	}
	return $string;
}
/* calculate date difference in days */
function daydiff($date1,$date2) {
  $datetime1 = new DateTime($date1);
  $datetime2 = new DateTime($date2);
  $interval = $datetime2->diff($datetime1);
  return $interval->format('%a');
}

function monthdiff($date1,$date2) {
  $datetime1 = new DateTime($date1);
  $datetime2 = new DateTime($date2);
  $interval = $datetime2->diff($datetime1);
  return $interval->format('%m');
}
/*
* Create combobox from array
*/
function generate_combobox($name,$array,$key,$value,$selected=false,$other=false) {
  if(empty($array)) {
    $output = "<select name=\"{$name}\" ".$other.">";
    $output .= "<option value=\"\">SELECT</option>";    
    $output .= "</select>";
  }
  else{  
    $output = "<select name=\"{$name}\" ".$other.">";
    $output .= "<option value=\"\">SELECT</option>";
    $keys=array_column($array,$key);
  if(is_array($value)) {
    $args=array();
    $args[]="combine";
    foreach($value as $val) {
      $args[]=array_column($array,$val);
    }
    //$combined = array_map(function($a, $b) { return $a . ' , ' . $b; }, $array1, $array2));
    
    //$vals=array_column($array,$value);
    
    $vals=call_user_func_array('array_map',$args);
    //$vals=array_column($array,$value);
  }
  else {
    $vals=array_column($array,$value);
  }
    
    $new_array=array_combine($keys,$vals);

   foreach ($new_array as $key => $value) {
      if(is_array($selected)) {
        if (in_array($key,$selected)) {
          $output .= "<option value=\"{$key}\" selected>{$value}</option>";
        } else {
            $output .= "<option value=\"{$key}\">{$value}</option>";
        }
      }
      else {
        if ($selected != false && $selected == $key) {
          $output .= "<option value=\"{$key}\" selected>{$value}</option>";
        } else {
            $output .= "<option value=\"{$key}\">{$value}</option>";
        }
      }
    }

    $output .= "</select>";
  }
  return $output;
}

function combine() {
  $args=func_get_args();
  $return='';
  foreach($args as $arg) {
    $return.=$arg.',';
  }
  $return=rtrim($return,',');
   return $return;
}

function formatted_size($size_bytes) { /* {{{ */
	if ($size_bytes>1000000000) return number_format($size_bytes/1000000000,1,".","")." GBytes";
	else if ($size_bytes>1000000) return number_format($size_bytes/1000000,1,".","")." MBytes";
	else if ($size_bytes>1000) return number_format($size_bytes/1000,1,".","")." KBytes";
	return number_format($size_bytes,0,"","")." Bytes";
}

function dskspace($dir) { /* {{{ */
	$space = 0;
	if(is_file($dir)) {
		$space = filesize($dir);
	} elseif (is_dir($dir)) {
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false)
			if ($file != "." and $file != "..")
				$space += dskspace($dir."/".$file);
		closedir($dh);
	}
	return $space;
}

function httpRequest($url) {
    $pattern = "/http...([0-9a-zA-Z-.]*).([0-9]*).(.*)/";
    preg_match($pattern,$url,$args);
    $in = "";
    $fp = fsockopen($args[1],80, $errno, $errstr, 30);
    if (!$fp) {
       return("$errstr ($errno)");
    } else {
  $args[3] = "C".$args[3];
        $out = "GET /$args[3] HTTP/1.1\r\n";
        $out .= "Host: $args[1]:$args[2]\r\n";
        $out .= "User-agent: PARSHWA WEB SOLUTIONS\r\n";
        $out .= "Accept: */*\r\n";
        $out .= "Connection: Close\r\n\r\n";

        fwrite($fp, $out);
        while (!feof($fp)) {
           $in.=fgets($fp, 128);
        }
    }
    fclose($fp);
    return($in);
}

function getMimeIcon($file_name) { /* {{{ */
    // for extension use LOWER CASE only
    $icons = array();
    $icons["txt"]  = "txt.png";
    $icons["text"] = "txt.png";
    $icons["doc"]  = "word.png";
    $icons["dot"]  = "word.png";
    $icons["docx"] = "word.png";
    $icons["dotx"] = "word.png";
    $icons["rtf"]  = "document.png";
    $icons["xls"]  = "excel.png";
    $icons["xlt"]  = "excel.png";
    $icons["xlsx"] = "excel.png";
    $icons["xltx"] = "excel.png";
    $icons["ppt"]  = "powerpoint.png";
    $icons["pot"]  = "powerpoint.png";
    $icons["pptx"] = "powerpoint.png";
    $icons["potx"] = "powerpoint.png";
    $icons["exe"]  = "binary.png";
    $icons["html"] = "html.png";
    $icons["htm"]  = "html.png";
    $icons["gif"]  = "image.png";
    $icons["jpg"]  = "image.png";
    $icons["jpeg"] = "image.png";
    $icons["bmp"]  = "image.png";
    $icons["png"]  = "image.png";
    $icons["tif"]  = "image.png";
    $icons["tiff"] = "image.png";
    $icons["log"]  = "log.png";
    $icons["midi"] = "midi.png";
    $icons["pdf"]  = "pdf.png";
    $icons["wav"]  = "sound.png";
    $icons["mp3"]  = "sound.png";
    $icons["c"]    = "source_c.png";
    $icons["cpp"]  = "source_cpp.png";
    $icons["h"]    = "source_h.png";
    $icons["java"] = "source_java.png";
    $icons["py"]   = "source_py.png";
    $icons["tar"]  = "tar.png";
    $icons["gz"]   = "gz.png";
    $icons["7z"]   = "gz.png";
    $icons["bz"]   = "gz.png";
    $icons["bz2"]  = "gz.png";
    $icons["tgz"]  = "gz.png";
    $icons["zip"]  = "gz.png";
    $icons["rar"]  = "gz.png";
    $icons["mpg"]  = "video.png";
    $icons["avi"]  = "video.png";
    $icons["tex"]  = "tex.png";
    $icons["ods"]  = "x-office-spreadsheet.png";
    $icons["ots"]  = "x-office-spreadsheet.png";
    $icons["sxc"]  = "x-office-spreadsheet.png";
    $icons["stc"]  = "x-office-spreadsheet.png";
    $icons["odt"]  = "x-office-document.png";
    $icons["ott"]  = "x-office-document.png";
    $icons["sxw"]  = "x-office-document.png";
    $icons["stw"]  = "x-office-document.png";
    $icons["odp"]  = "ooo_presentation.png";
    $icons["otp"]  = "ooo_presentation.png";
    $icons["sxi"]  = "ooo_presentation.png";
    $icons["sti"]  = "ooo_presentation.png";
    $icons["odg"]  = "ooo_drawing.png";
    $icons["otg"]  = "ooo_drawing.png";
    $icons["sxd"]  = "ooo_drawing.png";
    $icons["std"]  = "ooo_drawing.png";
    $icons["odf"]  = "ooo_formula.png";
    $icons["sxm"]  = "ooo_formula.png";
    $icons["smf"]  = "ooo_formula.png";
    $icons["mml"]  = "ooo_formula.png";

    $icons["default"] = "default.png";
    $icon_path=base_url().ICON_PATH;
    $ext = strtolower(substr($file_name, strrpos($file_name, '.') + 1));
    if (isset($icons[$ext])) {
      return $icon_path.$icons[$ext];
    }
    else {
      return $icon_path.$icons["default"];
    }
  }

function send_sms($mobile_no,$message) {
    $message=urlencode($message);
    $url=SMSAPP_API_SENDSMS."securekey=".SMSAPP_SECUREKEY."&token=".SMSAPP_TOKEN."&to=".$mobile_no."&message=".$message;
    $response=curl_send($url);
    if(strpos(strtolower($response),'sent') === false) {
      return false;
    }
    else {
      return true;
    }
  }

/*
* Convert Amount to word
*
**/
function convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return strtoupper($string);
}
/**
 * This file is part of the array_column library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2013 Ben Ramsey <http://benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

if (!function_exists('array_column')) {

    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {

            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }
}
