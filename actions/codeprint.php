<?php
session_start();
if($_SESSION["user"]=="admin"){
	ini_set('display_errors', 1);
	require("../fpdf/fpdf.php");
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Courier','B',16);
	
	$title = "E-vote - PERSONLIGA KODER";
	$pdf->Cell(190,10,$title);
	$pdf->Ln();
	
	/*
		TODO
		Listan $codes ska få sina värden från koderna i databasen
	*/
	$pdf->SetFont('Courier','',15);
	$codes[0] = "0"; 
	for($i = 0; $i<300; $i++){
		$codes[$i] = "17xxCx";
	}
	
	$out = "";
	$lenght = 0;
	$breaklenght = 6;
	$line = "";
	for($i=0;$i<(6+3)*$breaklenght;$i++)
		$line .= "-";
	
	foreach($codes as $c){
		if($lenght >= $breaklenght){
			$out .= "|\n";
			$out .= $line."\n";
			$lenght = 0; 
		}
		$out .= "| ".$c." ";
		$lenght++;
	}
	$out .= "|";
	
	$pdf->Multicell(190,10,$out);
	$pdf->Output();
}else{
	echo "Fy! Så får du inte göra.";
}
?>
