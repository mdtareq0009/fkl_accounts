
<?php
require_once('../ini.php');
use accessories\accessoriescrud;
use accessories\workorderoperation;
$workorderOpt = new workorderoperation($db->con);
$accessoriesModel = new accessoriescrud($db->con);
$user = $auth->loggedUser();
$userName = $user['name'];

if(isset($_GET['topsheetno']))
$topsheetno = isset($_GET['topsheetno']) ? $_GET['topsheetno'] : '';
// $topsheetno = "'" . str_replace(",", "','", addslashes($topsheetno)) . "'";

$data=$accessoriesModel->getData("SELECT master.nid, master.vponumber, master.vblockorderinfo, master.vissue, s.vname as supplier
, REGEXP_REPLACE(LISTAGG(distinct goods.vname, ', ') WITHIN GROUP (ORDER BY items.nid ASC), '([^,]+)(, \\1)+', '\\1') AS itemname
, ITEMS.NTOTALGARMENTSQTY,ITEMS.VQTYUNIT, items.ntotalqty as itemqty, REGEXP_SUBSTR(items.vaddition, '\d+%') AS extra_percentage
, master.vordernumberorfklnumber, master.vtype, master.vpublisheduser, employee.empname AS createduser, master.vapproveduser
, approvedemployee.empname AS approveduser, master.vpublishedat, master.ncheckedstatus, master.vcheckeduser, checkedby.empname AS checkeduser
, REGEXP_REPLACE(LISTAGG(UPPER(orderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') 
AS orderinfostylename, REGEXP_REPLACE(LISTAGG(orderinfoks.nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1')
AS orderinfoksksid, orderinfo.vname AS orderinfobuyername, REGEXP_REPLACE(LISTAGG(UPPER(ksorderinfo.vstylename), ', ') 
WITHIN GROUP (ORDER BY  ksorderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS ksstylename
, REGEXP_REPLACE(LISTAGG(ksorderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY ksorderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1')
AS ksordernumber, ksorderinfo.vname AS ksbuyername FROM accessories_workordermaster master LEFT JOIN accessories_workorderitems items 
ON items.nworkordermasterid = master.nid LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.vordernumber = master.vordernumberorfklnumber
LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid 
AND (TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.dshipdate, 'dd-mm-yy')))
OR TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.duserdate, 'dd-mm-yy'))))
LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (TO_NUMBER(REGEXP_REPLACE (upper(vordernumberorfklnumber), '[A-Z]', 0))) 
LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode LEFT JOIN hr_employeeinfo@crypton employee 
ON employee.empid = master.vpublisheduser LEFT JOIN hr_employeeinfo@crypton approvedemployee ON approvedemployee.empid = master.vapproveduser 
LEFT JOIN hr_employeeinfo@crypton checkedby ON checkedby.empid = master.vcheckeduser LEFT JOIN accessories_goods goods
ON goods.nid = items.ngoodsid left join accessories_suppliers s on master.nsupllierid=s.nid WHERE master.vstatus = 'publish' 
AND master.nissuestatus = 1 AND master.ncheckedstatus = 1
AND master.nmerchandiserstatus=1
AND master.nmerchandisermanagerstatus=1
AND master.nmerchandisergmstatus=1
AND master.npurchasegmstatus=1 AND master.nauditstatus = 1 AND master.napprovedstatus = 1 AND master.ntopsheet=1 
and vtopsheetno='$topsheetno'
AND master.ndeletedstatus = 0 and to_date(master.vcreatedat,'dd-mm-yyyy') > to_date('26-08-2024','dd-mm-yyyy') 
GROUP BY master.nid, master.vponumber, master.vblockorderinfo, master.vissue
, master.vordernumberorfklnumber, master.vtype, master.vpublisheduser, employee.empname
, master.vpublishedat, master.ncheckedstatus, master.vcheckeduser, checkedby.empname
, master.vapproveduser, approvedemployee.empname, orderinfo.vname, ksorderinfo.vname, s.vname,items.ntotalqty
, ITEMS.NTOTALGARMENTSQTY,ITEMS.VQTYUNIT,items.vaddition ORDER BY master.nid DESC
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
        global $topsheetno;
        $date = date('d-M-Y');
        $this->SetFont('Times','b',5.5);
 
        //   $this->Cell( 10, 15, $this->Image('fakir-logo-design-web-design.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
          $this->Cell( 10, 15, $this->Image('fakir-knit.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
          $this->Ln(5);
          $this->MultiCell(0,4,"(AN ISO 9001 : 2008, SCR, BSCI, SEDEX, ORGANIC & OEKO TEX CERTIFIED COMPANY)", '', 'C');
          $this->SetDrawColor(245,131,34);
          $this->Line(0, $this->GetY(),  $this->GetPageWidth(), $this->GetY());
          $this->SetDrawColor(1,1,1);
          $this->SetFont('Times','b',8.5);
          $this->cell(60,10,'Topsheet No: '.$topsheetno,0,0,'L');
          $this->SetFont('Times','b',15);
          $this->cell(60,10,'Work Order Top Sheet',0,0,'C');
          $this->SetFont('Times','b',8.5);
          $this->cell(70,10,'Date: '.$date,0,1,'R');
          $this->Ln(3);
    }
    function CellWithLimit($width, $height, $text, $border, $ln, $align,$textNo)
    {
        // Limit the text to 12 characters, and append '...' if it's longer
        if (strlen($text) > $textNo) {
            $text = substr($text, 0, $textNo) . '...';
        }
        
        // Create the cell with the limited text
        $this->Cell($width, $height, $text, $border, $ln, $align);
    }

function topsheetTable($header, $data,$rowHeight)
{
    // Column widths
    $this->SetFont('Times','',7.5);
    $this->SetFillColor(222, 222, 222); 
    $this->SetLineWidth(.1);
    $w = array(10, 25, 20, 15, 10, 15,35,40,12,13);
    // $w = array(10, 15, 35, 28, 38, 35,15,15);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],11,$header[$i],1,0,'C',1);
    $this->Ln();
    foreach($data as $key=>$row)
    {
        if($row['VTYPE'] == 'Order number'):
            if(strtolower($row['VORDERNUMBERORFKLNUMBER']) == 'block' || strtolower($row['VORDERNUMBERORFKLNUMBER']) == 'store'):
                $explodeBlockData = explode("##", $row['VBLOCKORDERINFO']);
                $orderNumber = $row['VORDERNUMBERORFKLNUMBER'];
                $fklNo = isset($explodeBlockData[0]) ? $explodeBlockData[0] : '' ;
                $buyerName = isset($explodeBlockData[2]) ? $explodeBlockData[2] : '' ;
                $styleName = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
            else:
                $orderNumber = $row['VORDERNUMBERORFKLNUMBER'];
                $fklNo = $row['ORDERINFOKSKSID'];
                $buyerName = $row['ORDERINFOBUYERNAME'];
                $styleName = $row['ORDERINFOSTYLENAME'];
            endif;
        elseif($row['VTYPE'] == 'FKL number'):
            if(strtolower($row['VORDERNUMBERORFKLNUMBER']) == 'block' || strtolower($row['VORDERNUMBERORFKLNUMBER']) == 'store'):
                $explodeBlockData = explode("##", $row['VBLOCKORDERINFO']);
                $fklNo = $row['VORDERNUMBERORFKLNUMBER'];
                $orderNumber = isset($explodeBlockData[0]) ? $explodeBlockData[0] : '' ;
                $buyerName = isset($explodeBlockData[2]) ? $explodeBlockData[2] : '' ;
                $styleName = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
            else:
                $fklNo = $row['VORDERNUMBERORFKLNUMBER'];
                $orderNumber = $row['KSORDERNUMBER'];
                $buyerName = $row['KSBUYERNAME'];
                $styleName = $row['KSSTYLENAME'];
            endif;
        endif;

        $this->Cell($w[0],$rowHeight,$key+1,1,0,'C');
        // $this->Cell($w[1],$rowHeight,$buyerName,1,0,'C');
        $this->Cell($w[1],$rowHeight,htmlspecialchars_decode($buyerName,ENT_QUOTES),1,0,'C');
        $this->Cell($w[2],$rowHeight,$row['VPONUMBER'],1,0,'C');
        $this->CellWithLimit($w[3],$rowHeight,htmlspecialchars_decode($orderNumber,ENT_QUOTES),1,0,'C',10);
        $this->Cell($w[4],$rowHeight,htmlspecialchars_decode($fklNo,ENT_QUOTES),1,0,'C');
        $this->Cell($w[5],$rowHeight,$row['VPUBLISHEDAT'],1,0,'C');
        $this->Cell($w[6],$rowHeight,htmlspecialchars_decode($row['SUPPLIER'],ENT_QUOTES),1,0,'C');
        $this->Cell($w[7],$rowHeight,htmlspecialchars_decode($row['ITEMNAME'],ENT_QUOTES),1,0,'C');
        $this->Cell($w[8],$rowHeight,number_format($row['ITEMQTY']),1,0,'C');
        $this->Cell($w[9],$rowHeight,htmlspecialchars_decode($row['VQTYUNIT'],ENT_QUOTES),1,0,'C');
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
    
    $cellWidth = ($this->GetPageWidth() - 20 ) / 2;


    
    // $this->Cell($cellWidth, 10, $userName , 0, 0, 'L');
    $this->Ln(1);
    $this->Cell($cellWidth, 10, 'Checked By', 0, 0, 'L');
    // $this->SetX($signatureX2);
    
    $this->Cell($cellWidth+6, 10, 'Approved By', 0, 0, 'L');
    // $this->SetX($signatureX3);
    
    
    $this->SetFont('Times', 'I', 8);
    $this->AliasNbPages();
    $pageCount = $this->PageNo();
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,1,'R');
    $this->Ln(1);
    // $this->smartRow($this->tableFooter,true);
}

}


$pdf = new PDF();
// Column headings
$header = array('Sl', 'Buyer' ,'P.O. No.', 'Orderno','FKL No', 'W.O. Date', 'Supplier','Item','Qty','Unit');

$pdf->SetFont('Times','',8.5);

// $pdf->AddPage();
$pdf->AddPage();
// $pdf->Header();
$pdf->topsheetTable($header,$data,3.8);
// $pdf->Ln(5);

// $pdf->smartRow($tableFooter,true);

$pdf->Output('Workorder Top Sheet.pdf', 'I');
?>