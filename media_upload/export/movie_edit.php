<?php 
include("../../function.php");
include("../include/Program.class.php");
include("../include/PHPExcel.php");

$user_id=$_GET["user_id"];
$db=db_connect();

$results=Program::get_programs(array(
    "user_id"=>$user_id,
    "status"=>0
));   

if($results){
	$objPHPExcel = new PHPExcel(); 
	$objPHPExcel->setActiveSheetIndex(0);

	$sql="select * from media_field_cn_list";
	$fields=$db->get_results($sql,ARRAY_A);

	$row=1;
	$col=0;
	$field_array=array();
	foreach($fields as $field){
		if($field["field"]=="play4"||$field["field"]=="play5"||$field["field"]=="play6"){continue;}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field["name"]);
		$field_array[$col]=$field["field"];
		$col++;
	}

	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "状态");
	// $field_array[$col]="status";

	$row++;

	$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);

	foreach($results as $result){
		$col=0;

		$unvalid=array();
		$program_id=$result["program_id"];
		$sql="select * from media_unvalid_field where program_id='{$program_id}' ";
		$fields=$db->get_results($sql,ARRAY_A);
		if($fields){
			foreach($fields as $f){
				$unvalid[]=$f["field"];
			}
		}

		foreach($field_array as $f){
			$column = PHPExcel_Cell::stringFromColumnIndex($col);
			$cell = $column.$row;
			if($f=="program_default_name"){ 	
				$objPHPExcel->getActiveSheet()->protectCells($cell, 'PHPExcel');
			}else{
				$objPHPExcel->getActiveSheet()->getStyle($cell)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);  
			}

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $result[$f]);

			if(in_array($f,$unvalid)){  
				// $column = PHPExcel_Cell::stringFromColumnIndex($col);
				// $cell = $column.$row; 

				$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray(
				    array(
				        'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => 'FFFF00')
				        )
				    )
				);
			}
			

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