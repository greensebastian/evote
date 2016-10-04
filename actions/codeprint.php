<?php

function decode_str($string){
	return iconv('UTF-8', 'windows-1252', $string);
}


if($evote->verifyUser($_SESSION["user"], 0)){
	require("../fpdf/fpdf.php");
	class PDF extends FPDF {
		function Footer(){
		    // Go to 1.5 cm from bottom
		    $this->SetY(-15);
		    // Select Arial italic 8
		    $this->SetFont('Arial','I',8);
		    // Print centered page number
		    $this->Cell(0,10,'Sida '.$this->PageNo(),0,0,'C');
		}
	}
	$pdf = new PDF();

	$pdf->AddPage();
	$pdf->setFont('Arial', 'B', 30);
	$pdf->MultiCell(190,10,'Koder ('.count($codes).' st)', 0, 'C');

	$pdf->SetFont('Courier','',16);

	$count = 0;
	$row = 1;
	$codes_per_row = 6;
	foreach($codes as $c){
		$count ++;
		$pdf->Cell(30,10,$c, 0, 'C');

		if($count % $codes_per_row == 0){
			$pdf->Ln();
			$row ++;
		}
	}
	$pdf->Output();
}else{
	echo "Fy! Så får du inte göra.";
}
?>
