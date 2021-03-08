<?php 

require('../fpdf/fpdf.php'); 
session_start();
if(isset($_SESSION['admin']) && $_SESSION['admin']==true) {
  require_once "../functions/database_functions.php";
  $conn = db_connect();
  $query = "SELECT * from expense";
  $result = mysqli_query($conn, $query);
  //print_r($result);
  $date="yet to post";
  $sumquery= "SELECT SUM(expense) as expense FROM expense";
  $total = mysqli_query($conn, $sumquery);
  $sum=mysqli_fetch_assoc($total);
  //echo $sum['expense'];
  //$date=$array['date'];
// New object created and constructor invoked 
$pdf = new FPDF(); 

// Add new pages. By default no pages available. 
$pdf->AddPage(); 
	
// Set font format and font-size 
$pdf->SetFont('Times', 'B', 20); 

// Framed rectangular area 
$pdf->Cell(71 ,10,'',0,0);
$pdf->Cell(59 ,5,'Invoice',0,0);
$pdf->Cell(59 ,10,'',0,1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(71 ,5,'From : '.$date,0,0);


$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,'To : '.$date,0,1);
$pdf->Ln();


$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(10 ,6,'Sr No.',1,0,'C');
$pdf->Cell(50 ,6,'Name',1,0,'C');
$pdf->Cell(90 ,6,'Purpose',1,0,'C');
$pdf->Cell(30 ,6,'Amount',1,0,'C');
$pdf->Ln();
/*end of line*/
/*Heading Of the table end*/
$i=1;
$pdf->SetFont('Arial','',10);
    while($array = mysqli_fetch_assoc($result)) {

		$pdf->Cell(10 ,6,$i,1,0);
		$pdf->Cell(50 ,6,$array['name'],1,0);
		$pdf->Cell(90 ,6,$array['note'],1,0,'L');
		$pdf->Cell(30 ,6,$array['expense'],1,0,'R');
		$pdf->Ln();
		
		$i++;
		
	}
		

$pdf->Cell(125 ,6,'',0,0);
$pdf->Cell(25 ,6,'Subtotal',0,0);
$pdf->Cell(30 ,6,$sum['expense'],1,1,'R');

$pdf->Output(); 
}
?> 