
<?php
require_once('../ini.php');
use accessories\accessoriescrud;
use accessories\workorderoperation;
$workorderOpt = new workorderoperation($db->con);
$accessoriesModel = new accessoriescrud($db->con);
$user = $auth->loggedUser();
$userName = $user['name'];

if(isset($_GET['orderSearch']))
$orderSearch = isset($_GET['orderSearch']) ? $_GET['orderSearch'] : '';
$orderSearch = "'" . str_replace(",", "','", addslashes($orderSearch)) . "'";

$data=$accessoriesModel->getData("select distinct  m.vordernumberorfklnumber as orderno, m.vponumber,m.nid,s.vname as supplier,m.ntopsheet
,i.ntotalqty as itemqty,vtodate as to_date
,g.vname as goods,i.ntotalgarmentsqty as garmentsqty,i.vqtyunit as unit
,o.vname as buyername
,vtype
from accessories_workordermaster m 
left join accessories_suppliers s on m.nsupllierid = s.nid
left join accessories_workorderitems i on m.nid=i.nworkordermasterid
left join accessories_workorderitemdata d on i.nid=d.nworkorderitemsid
left join accessories_goods g on i.ngoodsid=g.nid
left join erp.mer_vw_orderinfo o on  m.vordernumberorfklnumber=o.vordernumber
left join erp.mer_ks_master k on o.norderid=k.nordercode
where m.vstatus='publish' 
and m.vordernumberorfklnumber in (
$orderSearch)
");
require_once('fpdf.php');
class PDF extends FPDF{
    public $tableFooter = array(
        'columnName'          => array('Kayempur, Fatullah, Narayanganj-1400, Bangladesh. Tel: +88-02-55110921-4, 09678005006-7. Fax: +88-02-9569852', 'E-mail : fklinfo@fakirgroup.com'),
        'columnWidth'         => array(149, 41),
        'columnBackground'    => array(array('r'=> 245, 'g'=>131, 'b' => 34), array('r'=> 43, 'g'=>43, 'b' => 43)),
        'textColour'          => array(array('r' => 255, 'g' => 255, 'b' => 255), array('r' => 255, 'g' => 255, 'b' => 255)),
        'columnFontSize'      => 8.1,
        'columnFontWeight'    => '',
        'columnHeight'        => 4.2, //set column padding
        'columnAlign'         => 'L', //options center, left, right
        'columnVerticalAlign' => 'middle' // middle, top, bottom
    );

    function Header()
    {
        $date = date('d-M-Y');
        $this->SetFont('Times','b',5.5);
 
          $this->Cell( 10, 15, $this->Image('fakir-logo-design-web-design.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
          $this->Ln(5);
          $this->MultiCell(0,4,"(AN ISO 9001 : 2008, SCR, BSCI, SEDEX, ORGANIC & OEKO TEX CERTIFIED COMPANY)", '', 'C');
          $this->SetDrawColor(245,131,34);
          $this->Line(0, $this->GetY(),  $this->GetPageWidth(), $this->GetY());
          $this->SetDrawColor(1,1,1);
          $this->SetFont('Times','b',15);
          $this->cell(0,10,'Accessories Work Order Top Sheet',0,0,'C');
          $this->SetFont('Times','b',8.5);
          $this->cell(0,10,'Date: '.$date,0,1,'R');
          $this->Ln(3);
    }

function topsheetTable($header, $data,$rowHeight)
{
    // Column widths
    $this->SetFont('Times','',8.5);
    $this->SetFillColor(222, 222, 222); 
    $this->SetLineWidth(.1);
    $w = array(10, 15, 35, 28, 38, 35,15,15);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    $this->Ln();
    foreach($data as $key=>$row)
    {
        $this->Cell($w[0],$rowHeight,$key+1,1);
        $this->Cell($w[1],$rowHeight,$row['ORDERNO'],1);
        $this->Cell($w[2],$rowHeight,htmlspecialchars_decode($row['BUYERNAME'],ENT_QUOTES),1);
        $this->Cell($w[3],$rowHeight,$row['VPONUMBER'],1);
        $this->Cell($w[4],$rowHeight,htmlspecialchars_decode($row['SUPPLIER'],ENT_QUOTES),1);
        $this->Cell($w[5],$rowHeight,htmlspecialchars_decode($row['GOODS'],ENT_QUOTES),1);
        $this->Cell($w[6],$rowHeight,number_format($row['ITEMQTY']),1);
        $this->Cell($w[7],$rowHeight,htmlspecialchars_decode($row['UNIT'],ENT_QUOTES),1);
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}


// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    
    $this->SetDrawColor(0); // Set line color (black)
    $this->SetLineWidth(0.5); // Set line width
    
    // Define the positions for the three signature placeholders
    $signatureX1 = 20;
    $signatureX2 = 70;
    $signatureX3 = 120;
    $signatureY = $this->GetY() - 8; // Adjust the Y position as needed
    
    $cellWidth = ($this->GetPageWidth() - 20 ) / 4;


    
    // $this->Cell($cellWidth, 10, $userName , 0, 0, 'L');
    $this->Ln(1);
    $this->Cell($cellWidth, 10, 'Prepared By', 0, 0, 'L');
    $this->SetX($signatureX2);
    
    $this->Cell($cellWidth+6, 10, 'Approved By', 0, 0, 'L');
    $this->SetX($signatureX3);
    
    
    $this->SetFont('Times', 'I', 8);
    $pageCount = $this->PageNo();
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/'.$pageCount,0,1,'R');
    $this->Ln(1);
    $this->smartRow($this->tableFooter,true);
}

}


$pdf = new PDF();
// Column headings
$header = array('Sl','Orderno', 'Buyer', 'P.O. No.', 'Supplier','Item','Qty','Unit');

$pdf->SetFont('Times','',8.5);

// $pdf->AddPage();
$pdf->AddPage();
// $pdf->Header();
$pdf->topsheetTable($header,$data,3.8);
// $pdf->Ln(5);

// $pdf->smartRow($tableFooter,true);

$pdf->Output('Workorder Top Sheet.pdf', 'I');
?>