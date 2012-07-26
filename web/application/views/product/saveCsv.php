<?php
header ( "Content-Type: application/vnd.ms-excel" );
header ( "Content-Disposition: attachment; filename=ums_report.xls" );
header ( "Pragma: no-cache" );
header ( "Expires: 0" );

$result = $data;
$result = mb_convert_encoding($result,"GB2312","utf-8");

$sep = "\t";
//列名
$num_fileds = 0;
$fields = array ();
foreach ( $result->list_fields () as $field ) {
	echo $field . $sep;
	$num_fileds ++;
	array_push ( $fields, $field );
}
print ("\n") ;

foreach ( $result->result_array () as $row ) {
	$schema_insert = "";
//	echo $row ['email'];
	for($j = 0; $j < $num_fileds; $j ++) {
		$columname = $fields [$j];
		if (! isset ( $row [$columname] ))
			$schema_insert .= "NULL" . $sep;
		elseif ($row [$columname] != "")
			$schema_insert .= "$row[$columname]" . $sep;
		else
			$schema_insert .= "" . $sep;
	
	}
	$schema_insert = str_replace ( $sep . "$", "", $schema_insert );
	$schema_insert .= "\t";
	print (trim ( $schema_insert )) ;
	print "\n";
}