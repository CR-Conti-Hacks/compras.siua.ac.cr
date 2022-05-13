<?php
  header('Content-Type: charset=utf-8');
  date_default_timezone_set ('America/Costa_Rica');
  //$this->Cell(0,6,iconv('UTF-8', 'ISO-8859-2',"Capítulo $num : $label"),0,1,'L',true);

  $page_title = 'Lista de ventas';
  require_once('includes/load.php');
  include_once('fpdf182/fpdf.php');

  
class PDF extends FPDF
{
  // Cargar los datos
  function LoadData($file)
  {
      // Leer las líneas del fichero
      $lines = file($file);
      $data = array();
      foreach($lines as $line)
          $data[] = explode(';',trim($line));
      return $data;
  }

  // Tabla coloreada
  function FancyTable($header, $data)
  {
      // Colores, ancho de línea y fuente en negrita
      $this->SetFillColor(255,0,0);
      $this->SetTextColor(255);
      $this->SetDrawColor(128,0,0);
      $this->SetLineWidth(.3);
      $this->SetFont('','B');
      // Cabecera
      $w = array(40, 35, 45, 40);
      //for($i=0;$i<count($header);$i++)
        //  $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
      $this->Cell(5,7,$header[0],1,0,'C',true);
      $this->Cell(20,7,$header[1],1,0,'C',true);
      $this->Cell(30,7,$header[2],1,0,'C',true);
      $this->Cell(40,7,iconv('UTF-8', 'ISO-8859-2',$header[3]),1,0,'C',true);
      $this->Cell(60,7,iconv('UTF-8', 'ISO-8859-2',$header[4]),1,0,'C',true);
      $this->Cell(60,7,iconv('UTF-8', 'ISO-8859-2',$header[5]),1,0,'C',true);
      $this->Ln();
      // Restauración de colores y fuentes
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->SetFont('');
      // Datos
      $fill = false;
      foreach($data as $row)
      {
          $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
          $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
          $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
          $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
          $this->Ln();
          $fill = !$fill;
      }
      // Línea de cierre
      $this->Cell(array_sum($w),0,'','T');
  }
}

$pdf = new PDF();
// Títulos de las columnas
$header = array('#', 'Imagen', 'Nombre', 'Descripción','Caracteristicas','Justificación','Precio Dólares','Precio Colones','Cantidad','Total');
// Carga de datos
$data = $pdf->LoadData('paises.txt');
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L');
$pdf->FancyTable($header,$data);
$pdf->Output();

?>
