<?php
require_once "excel/Classes/PHPExcel.php";


function read(){
	$inputFileName = 'files/modelo.xlsx';
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$excelObj = $objReader->load($inputFileName);
	$worksheet = $excelObj->getSheet(0);
	$lastRow = $worksheet->getHighestRow();
	$array = array();	
	$pd = 0;
	$periodo = $_REQUEST["periodo"];
	$suma = 0;
	for ($row = 1; $row <= $lastRow; $row++) {
		$pd++;
		$prod = $worksheet->getCell('C'.$row)->getValue();
		$venta = $worksheet->getCell('B'.$row)->getValue();
		$date = date($format = "Y-m-d", 
					PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('A'.$row)->getValue()));
					
					
		$suma += $venta;
					
		if($pd == $periodo || $lastRow == $row){
			array_push($array, [
				"fecha" => $date,
				"venta" => -$suma/$periodo,
				"proyeccion" => 10000
			]);		
			$pd = 0;
			$suma = 0;	
		} 
	}
	
	echo json_encode($array);
}

function proyectar(){
	$inputFileName = 'files/modelo.xlsx';
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$excelObj = $objReader->load($inputFileName);
	$worksheet = $excelObj->getSheet(0);
	$lastRow = $worksheet->getHighestRow();
	$array = array();
	
	$name1 = 'ax';
	$name2 = 'ay';
	$ejey = 'Eje y';
	$ejex = 'Eje x';
	$vector = array();
	$matriz = array();
	
	//Variables Modelo Econometrico
	
	$Sxy = 0;
	$Sx = 0;
	$Sy = 0;
	$Sx2 = 0;
	$n = 0;
	$x = 0;
	$y = 0;
	
	$a = 0;
	$b = 0;
	
	//Variables Grafica
	//Y = $a + $b * $Sx
	
	$xi = 0;
	$xf = 0;
	
	$yi = 0;
	$yf = 0;
	
	
	echo "<table class='table'><thead>";
	
	for ($row = 1; $row <= $lastRow; $row++) {
		$date = date($format = "Y-m-d", 
					PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('A'.$row)->getValue()));
		array_push($array, [
			"fecha" => $date,
			"venta" => $worksheet->getCell('B'.$row)->getValue()
		]);
		
			if($row == 1){
				$ejey = $worksheet->getCell('A'.$row)->getValue();
				$ejex = $worksheet->getCell('B'.$row)->getValue();
				echo "<tr><th>";
				
				echo $date;
				echo "</th><th>";
				echo $worksheet->getCell('B'.$row)->getValue();
				echo "</th><tr></thead><tbody>";
			}
			else{
				$vector[$name1] = $worksheet->getCell('B'.$row)->getValue();
				$vector[$name2] = $worksheet->getCell('A'.$row)->getValue();	
				array_push($matriz, $vector);
				 echo "<tr><td>";	
				 echo $worksheet->getCell('A'.$row)->getValue();
				 echo "</td><td>";
				 echo $worksheet->getCell('B'.$row)->getValue();
				 echo "</td><tr>";		
				 
				 
				 //Calculos para la formula
				 $y = $worksheet->getCell('A'.$row)->getValue();
				 $x = $worksheet->getCell('B'.$row)->getValue();
				 
				 $Sxy += ($x*$y);
				 $Sy +=  $y;
				 $Sx +=  $x;
				 $Sx2 +=  ($x*$x);
				 
				 
				 //Calculos para la grafica en X
				 
				 if($row == 2){
						$xi = $x;
						$xf = $x;
						}
				 
				 if($x < $xi){ $xi = $x;}
				 if($x > $xf){$xf = $x;}
				 
			}
			$n = $row - 1;
	}
	
	//Calculos finales Formula
	$b = (($Sxy * $n) - ($Sx * $Sy)) / (($Sx2 * $n) - ($Sx * $Sx));
	$a = ($Sy - ($b * $Sx))/$n;
	
	//Calculos Grafica en Y
	$yi = $a + $b * $xi;
	$yf = $a + $b * $xf;
}


read();


?>