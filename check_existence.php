<?php

function get_data() {
	// 適当に作った名字ランキングのデータ
	$url = 'https://script.google.com/macros/s/AKfycbwIOXwjaVClxuUQVTTg1bWCWw8J5R6IQypEPj4J2DMO_D0UUCA/exec';

	$res = array();
	$options = array( 
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_HEADER         => false,
	    CURLOPT_FOLLOWLOCATION => true,
	    CURLOPT_AUTOREFERER    => true,
	    CURLOPT_CONNECTTIMEOUT => 120, 
	    CURLOPT_TIMEOUT        => 120, 
	    CURLOPT_MAXREDIRS      => 10,  
	); 
	$ch = curl_init( $url ); 
	curl_setopt_array( $ch, $options ); 
	$data = curl_exec( $ch ); 
	curl_close( $ch ); 

	return json_decode($data, true);;
}

function check_exist1($list, $name) {
	foreach ($list as $value) {
		if ($value['name'] === $name) {
			return true;
		}
	}
	return false;
}

function check_exist2($list, $name) {
	$name_list = array_column($list, 'name');
	return in_array($name, $name_list, true);
}

function test() {
	$data = get_data();
	$test_word1 = '佐藤';//リストに最初に存在する名前
	// $test_word2 = '加藤';//リストの最後に存在する名前
	$test_word2 = '杉本';//リストの最後に存在する名前
	$test_word3 = '小鳥遊';//リストに存在しない名前
	$test_count = 10000;
	echo count($data) . "件のデータで{$test_count}回テスト\n";
	$start = microtime(true);
	for ($i=0; $i < $test_count; $i++) { 
		check_exist1($data, $test_word1);
	}
	$end = microtime(true);
	echo "foreachでリストの最初に存在する場合:\t\t\t";
	echo $end - $start . "\n";

	$start = microtime(true);
	for ($i=0; $i < $test_count; $i++) { 
		check_exist1($data, $test_word2);
	}
	$end = microtime(true);
	echo "foreachでリストの最後に存在する場合:\t\t\t";
	echo $end - $start . "\n";

	$start = microtime(true);
	for ($i=0; $i < $test_count; $i++) { 
		check_exist1($data, $test_word3);
	}
	$end = microtime(true);
	echo "foreachで存在しない場合:\t\t\t\t";
	echo $end - $start . "\n";

	$start = microtime(true);
	for ($i=0; $i < $test_count; $i++) { 
		check_exist2($data, $test_word1);
	}
	$end = microtime(true);
	echo "array_column+in_arrayでリストの最初に存在する場合:\t";
	echo $end - $start . "\n";

	$start = microtime(true);
	for ($i=0; $i < $test_count; $i++) { 
		check_exist2($data, $test_word2);
	}
	$end = microtime(true);
	echo "array_column+in_arrayでリストの最後に存在する場合:\t";
	echo $end - $start . "\n";

	$start = microtime(true);
	for ($i=0; $i < $test_count; $i++) { 
		check_exist2($data, $test_word3);
	}
	$end = microtime(true);
	echo "array_column+in_arrayで存在しない場合:\t\t\t";
	echo $end - $start . "\n";
}

test();