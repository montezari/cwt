<?php

class TExcelTemplate{
	public $objPHPExcel;
	var $cellValues;
	
	function __construct($filename){
		$this->objPHPExcel = PHPExcel_IOFactory::load($filename);
	}

	public function getCellValues($force = false){
		if ( !is_null($this->cellValues) && $force === false ){
			return $this->cellValues;
		}
		$currentIndex = $this->objPHPExcel->getActiveSheetIndex();
		$this->objPHPExcel->setActiveSheetIndex(0);


		$sheet = $this->objPHPExcel->getActiveSheet();
		$highestColumn = $sheet->getHighestColumn(); 
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		$highestRow= $sheet->getHighestRow();

		$this->cellValues = array();
		for ( $i =0 ; $i < $highestColumnIndex; $i++ ){
			$column = PHPExcel_Cell::stringFromColumnIndex($i);
			for ( $j = 1; $j <= $highestRow; $j++ ){
				$this->cellValues[$column . $j] = $sheet->getCellByColumnAndRow($i, $j)->getValue();
			}
		}
		$this->objPHPExcel->setActiveSheetIndex($currentIndex);
		return $this->cellValues;
	}

	public function getCellByValue($search) {
		$nonPrintableChars = array("\n", "\r", "\t", "\s");
		$search = str_replace($nonPrintableChars, '', $search);
		foreach ( $this->getCellValues() as $cell => $value ){
			if ( strcasecmp(str_replace($nonPrintableChars, '', $value), $search) == 0  ){
				return $cell;
			}
		}
		return false;
	}

}



class TExcel{
   var $xls_column  = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
							"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
							"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
							"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
							"DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
							"EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ"
							);

  function TExcel(){
    
  }

  function setColumnAfter($colbase, $idx){
    $num = preg_replace("/[^0-9]/", '', $colbase);
    $col = str_replace($num,"#",$colbase);
	for($i=0;$i<strlen($col);$i++){
	  if($col[$i]!="#"){
		$tmp .= $col[$i];
	  }
	} 
	$num = $num+$idx;
	return $tmp.$num;
  }

  function getColumnId($colbase){
    $num = preg_replace("/[^0-9]/", '', $colbase);
    $col = str_replace($num,"#",$colbase);
	for($i=0;$i<strlen($col);$i++){
	  if($col[$i]!="#"){
		$tmp .= $col[$i];
	  }
	} 
	return $tmp;
  }

}

?>