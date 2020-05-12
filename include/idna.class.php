<?
class idna {
	var $prefix = "xn--";
	var $delim = "-";
	var $base = 36;
	var $tmin = 1;
	var $tmax = 26;
	var $skew = 38;
	var $damp = 700;
	var $initial_bias = 72;
	var $initial_n = 128;

	function idna()
	{
	}

	function unicode_hexncr($text)
	{
		$text = utf8_to_unicode($text);
		$return_array = array();

		foreach($text as $codepoint) {
			if($codepoint >= $this->initial_n)
				array_push($return_array, "&#x" . dechex($codepoint) . ";");
			else
				array_push($return_array, chr($codepoint));
		} 

		return($return_array);
	} 

	function _decode($text)
	{
		$n = $this->initial_n;
		$i = 0;
		$bias = $this->initial_bias;
		$output = array();

		if(substr($text, 0, strlen($this->prefix)) != $this->prefix)
			return($text);
		else
			$text = str_replace($this->prefix, "", $text);

		$this->delim_pos = strrpos($text, $this->delim);

		if($this->delim_pos !== false) {
			for($j = 0; $j < $this->delim_pos; $j++)
			array_push($output, $text[$j]);
			$text = substr($text, $this->delim_pos + 1);
		} 

		for(; strlen($text) > 0;) {
			$oldi = $i;
			$w = 1;

			for($k = $this->base;1; $k = $k + $this->base) {
				$digit = $this->decode_digit($text[0]);
				$text = substr($text, 1);
				$i = $i + $digit * $w;

				$t = 0;
				if($k <= $bias + $this->tmin)
					$t = $this->tmin;
				elseif($k >= $bias + $this->tmax)
					$t = $this->tmax;
				else
					$t = $k - $bias;

				if($digit < $t)
					break;

				$w = $w * ($this->base - $t);
			} 

			$bias = $this->adapt($i - $oldi, sizeof($output) + 1, $oldi == 0);
			$n = $n + floor($i / (sizeof($output) + 1));
			$i = $i % (sizeof($output) + 1);

			$tmp = $output;
			$output = array();

			$j = 0;
			for($j = 0; $j < $i; $j++)
			array_push($output, $tmp[$j]);
			array_push($output, $this->unicode_to_utf8($n));
			for($j = $j; $j < sizeof($tmp); $j++)
			array_push($output, $tmp[$j]);

			$i++;
		} 

		return(implode("",$output));
	} 
	/*
	*	将utf8格式的中文转换为punycode
	*/
	function encode($native)
	{
		$punycode = "";
		$native = trim($native);
		if(ereg("(.*)@(.*)", $native,$regs)) {
			$punycode=$this->u2p($regs[1])."@".$this->u2p($regs[2]);
		} else {
			$punycode = $this->u2p($native);
		} 
		return $punycode;
	} 

	function u2p($native)
	{
		$comma = "";
		$punycode = "";
		$native = trim($native);
		$native = ereg_replace("。",".", $native);
		if(ereg("\.$",$native))
			$suffix=".";
		else
			$suffix="";
		$arr = explode(".", $native);
		foreach($arr as $key => $val) {
			if($val == ""){
				continue;
			}
			$punycode .= $comma . $this->_encode($val);
			$comma = ".";
		} 
		return $punycode.$suffix;
	} 
	
	/*
	*	punycode 转换为utf8格式的中文
	*/
	function decode($native)
	{
		$punycode = "";
		$native = trim($native);
		if(ereg("(.*)@(.*)", $native,$regs)) {
			$punycode=$this->p2u($regs[1])."@".$this->p2u($regs[2]);
		} else {
			$punycode = $this->p2u($native);
		} 
		return $punycode;
	} 

	function p2u($native)
	{
		$comma = "";
		$punycode = "";
		$native = trim($native);
		$native = ereg_replace("。",".", $native);
		if(ereg("\.$",$native))
			$suffix=".";
		else
			$suffix="";
		$arr = explode(".", $native);
		foreach($arr as $key => $val) {
			if($val == "")
				continue;
			$punycode .= $comma . $this->_decode($val);
			$comma = ".";
		} 
		return $punycode.$suffix;
	} 

	function _encode($text)
	{
		$text = $this->utf8_to_unicode($text);
		$codecount = 0;
		$basic_string = "";
		$extended_string = "";
		for ($i = 0; $i < sizeof($text); $i++) {
			if($text[$i] < $this->initial_n) {
				$basic_string .= chr($text[$i]);
				$codecount++;
			} 
		} 

		$n = $this->initial_n;
		$delta = 0;
		$bias = $this->initial_bias;
		$h = $codecount;
		while($h < sizeof($text)) {
			$m = 100000;
			for($j = 0; $j < sizeof($text); $j++) {
				if($text[$j] >= $n && $text[$j] <= $m) {
					$m = $text[$j];
				} 
			} 

			$delta = $delta + ($m - $n) * ($h + 1);
			$n = $m;
			for($j = 0; $j < sizeof($text); $j++) {
				$c = $text[$j];
				if($c < $n)
					$delta++;
				elseif($c == $n) {
					$q = $delta;
					for($k = $this->base;1;$k = $k + $this->base) {
						$t = 0;
						if($k <= $bias + $this->tmin)
							$t = $this->tmin;
						elseif($k >= $bias + $this->tmax)
							$t = $this->tmax;
						else
							$t = $k - $bias;
						if($q < $t)
							break;
						$extended_string .= $this->encode_digit($t + (($q - $t) % ($this->base - $t)));
						$q = floor(($q - $t) / ($this->base - $t));
					} 
					$extended_string .= $this->encode_digit($q);
					$bias = $this->adapt($delta, $h + 1, $h == $codecount);
					$delta = 0;
					$h++;
				} 
			} 
			$delta++;
			$n++;
		} 

		if(strlen($basic_string) > 0 && strlen($extended_string) < 1) {
			$encoded = $basic_string;
		} elseif(strlen($basic_string) > 0 && strlen($extended_string) > 0) {
			$encoded = $this->prefix . $basic_string . $this->delim . $extended_string;
		} elseif(strlen($basic_string) < 1 && strlen($extended_string) > 0) {
			$encoded = $this->prefix . $extended_string;
		} 

		return($encoded);
	} 

	function adapt($delta, $numpoints, $firsttime)
	{
		if($firsttime)
			$delta = floor($delta / $this->damp);
		else
			$delta = floor($delta / 2);
		$delta = $delta + floor($delta / $numpoints);
		$k = 0;
		while($delta > floor((($this->base - $this->tmin) * $this->tmax) / 2)) {
			$delta = floor($delta / ($this->base - $this->tmin));
			$k = $k + $this->base;
		} 

		return($k + (floor((($this->base - $this->tmin + 1) * $delta) / ($delta + $this->skew))));
	} 

	/**
	* Function encode_digit and decode_digit were adapted from punycode.c, part of GNU Libidn.
	* 
	* http://www.gnu.org/software/libidn/doxygen/punycode_8c-source.html
	*/
	function encode_digit($d)
	{
		return chr(($d + 22 + 75 * ($d < 26)));
	} 

	function decode_digit($cp)
	{
		$cp = ord($cp);
		return ($cp - 48 < 10) ? $cp - 22 : (($cp - 65 < 26) ? $cp - 65 : (($cp - 97 < 26) ? $cp - 97 : $this->base));
	} 

	/**
	* Copyright (C) 2002 Scott Reynen
	* 
	* Function utf8_to_unicode and unicode_to_utf8 was taken from an article titled "How to develop multilingual, Unicode 
	* applications with PHP" at the following URL:
	* 
	* http://www.randomchaos.com/document.php?source=php_and_unicode
	*/
	function unicode_to_utf8($unicode)
	{
		$utf8 = '';
		if ($unicode < 128) {
			$utf8 .= chr($unicode);
		} elseif ($unicode < 2048) {
			$utf8 .= chr(192 + (($unicode - ($unicode % 64)) / 64));
			$utf8 .= chr(128 + ($unicode % 64));
		} else {
			$utf8 .= chr(224 + (($unicode - ($unicode % 4096)) / 4096));
			$utf8 .= chr(128 + ((($unicode % 4096) - ($unicode % 64)) / 64));
			$utf8 .= chr(128 + ($unicode % 64));
		} 

		return $utf8;
	} 

	function utf8_to_unicode($str)
	{
		$unicode = array();
		$values = array();
		$lookingFor = 1;
		for ($i = 0; $i < strlen($str); $i++) {
			$thisValue = ord($str[ $i ]);
			if ($thisValue < 128)
				$unicode[] = $thisValue;
			else {
				if (count($values) == 0)
					$lookingFor = ($thisValue < 224) ? 2 : 3;
				$values[] = $thisValue;
				if (count($values) == $lookingFor) {
					$number = ($lookingFor == 3) ?
					(($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64):
					(($values[0] % 32) * 64) + ($values[1] % 64);
					$unicode[] = $number;
					$values = array();
					$lookingFor = 1;
				} 
			} 
		} 
		return $unicode;
	} 
} 

?>
