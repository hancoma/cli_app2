<?php

/**
* paid plan 설정
*	- 단위 : GB, USD
*	- 범위 : min_traffic 이상 max_traffic 미만 
**/

return array(
	"lv0" => array(
		 "code_name" => "FREE"
		,"min_traffic" => "0"
		,"max_traffic" => "4"
		,"price" => "0"
	)
	,"lv1" => array(
		 "code_name" => "CB10"
		,"min_traffic" => "4"
		,"max_traffic" => "10"
		,"price" => "29"
	)
	,"lv2" => array(
		 "code_name" => "CB40"
		,"min_traffic" => "10"
		,"max_traffic" => "40"
		,"price" => "69"
	)
	,"lv3" => array(
		 "code_name" => "CB100"
		,"min_traffic" => "40"
		,"max_traffic" => "100"
		,"price" => "149"
	)
	,"lv4" => array(
		 "code_name" => "CBEP"
		,"min_traffic" => "100"
		,"max_traffic" => "inf"
		,"price" => "inf"
	)
	,"jp_lv0" => array(
		 "code_name" => "FREE"
		,"min_traffic" => "0"
		,"max_traffic" => "4"
		,"price" => "0"
	)
	,"jp_lv1" => array(
		 "code_name" => "Business"
		,"min_traffic" => ""
		,"max_traffic" => "40"
		,"price" => ""
	)
	,"jp_lv2" => array(
		 "code_name" => "Economy"
		,"min_traffic" => ""
		,"max_traffic" => "50"
		,"price" => ""
	)
	,"jp_lv3" => array(
		 "code_name" => "Premium"
		,"min_traffic" => ""
		,"max_traffic" => "100"
		,"price" => ""
	)
	,"jp_lv4" => array(
		 "code_name" => "Economy+"
		,"min_traffic" => ""
		,"max_traffic" => "256"
		,"price" => ""
	)
	,"jp_lv5" => array(
		 "code_name" => "Economy+"
		,"min_traffic" => ""
		,"max_traffic" => "500"
		,"price" => ""
	)
);
