<?php
class trendandforecastmodel extends CI_Model {
	function __construct(){
		
	}
	/**
	 * 趋势值
	 * @param unknown_type $date
	 */
	function getTrendaValur($data){
		
	}
	/**
	 * 预测值
	 * @param unknown_type $date
	 */
	function getPredictiveValur($data){
// 		print_r($data);
		$returnarray = array();
		$arrkeys = array();
		$arrkey1 = array();
		foreach($data as $key =>$var){
			array_push($arrkey1, $key);
			if(is_array($var)){
				$k = array();
				foreach($var as $key=>$var){
					array_push($k, $key);
				}
				array_push($arrkeys,$k);
			}
			
		}
		
// 		print_r($arrkeys);
		for($i = 0;$i<count($arrkeys);$i++){
			if($i>=count($arrkeys)-5){
				break;
			}
			for($j = 0;$j<count($arrkeys[$i]);$j++){
				$v = $arrkey1[$i];
				$v2 = $arrkeys[$i][$j];
// 				print_r($data["$v"]["$v2"]);
				$va5 = $v+5;
				if(!is_numeric($data["$v"]["$v2"])){
					$returnarray["$v"]["$v2"]=$data["$va5"]["$v2"];
				}else{
					$va1=$v+1;
					$va2 = $v+2;
					$va3 = $v+3;
					$va4 = $v+4;
					
					if($va4>=count($data)){
						break;
					}
					$avg = ($data["$v"]["$v2"]+$data["$va1"]["$v2"]+$data["$va2"]["$v2"]+$data["$va3"]["$v2"]+$data["$va4"]["$v2"])/5;
					$returnarray["$v"]["$v2"]=$avg;
				}
			}
		}
// 		print_r($returnarray);
		
		return $returnarray;
	}
	
	function geteventtrenddata($data){
		$length = count($data);
// 		echo $length;
		for($i=$length-1;$i>=5;$i--){
			$sumcount=0;
			$sumuserper = 0;
			$smsessionper = 0;
			for($j=0;$j<5;$j++){
			$sumcount=$sumcount+$data[$i-$j]['count'];
			$sumuserper = $sumuserper+$data[$i-$j]['userper'];
			$smsessionper = $smsessionper+$data[$i-$j]['sessionper'];
			}
			$data[$i]['count']=$sumcount/5;
			$data[$i]['userper']=$sumuserper/5;
			$data[$i]['sessionper']=$smsessionper/5;
		}
     $result = array();
     for($s=0;$s<count($data)-5;$s++){
     	$result[$s]=$data[$s+5];
     }
//      print_r($result);
//      $ss=array_shift($data);
//      echo count($data);
		return $result;
		
	}
	
}