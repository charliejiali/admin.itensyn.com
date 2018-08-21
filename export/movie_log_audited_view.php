<?php 
include("../function.php");
include("../include/Input.class.php");
include("../include/Program.class.php");
include("../include/PHPExcel.php");

$db=db_connect(); 

$input_id=$_GET["id"];
$status=$_GET["status"]; 
  
$data=Input::get_programs($input_id,false,false,$status);
$status=Program::get_status();
$results=$data["data"];

if($results){
	$objPHPExcel = new PHPExcel(); 
	$objPHPExcel->setActiveSheetIndex(0);

	$sql="select * from media_field_cn_list";
	$fields=$db->get_results($sql,ARRAY_A);

	$row=1;
	$col=0;
	$field_array=array();
	foreach($fields as $field){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field["name"]);
		$field_array[$col]=$field["field"];
		$col++;
	}

	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "状态");
	$field_array[$col]="status";

	$row++;
	foreach($results as $result){
		$col=0;
		foreach($field_array as $f){
			if($f=="status"){
				$value=$status[$result["status"]];
			}else{
				$value=$result[$f];
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
			$col++; 
		}
		$row++;
	}

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.date("YmdHis").'.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
}
exit();