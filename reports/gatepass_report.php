
<?php
require_once('../ini.php');
use accessories\accessoriescrud;
use accessories\workorderoperation;
$workorderOpt = new workorderoperation($db->con);
$accessoriesModel = new accessoriescrud($db->con);
// $user = $auth->loggedUser();
// $userName = $user['name'];
// exit;
$gp_master_id = isset($_GET['gp_master_id']) ? $_GET['gp_master_id'] : 0;


$masterData = $accessoriesModel->getData("select m.id,to_recipient,m.gp_no,gp_date,m.userid,total_qty,address,received.bempsign as receivedsign
,FROM_SOURCE,returnable
,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS USER_NAME
,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.HEAD_OF_DEPARTMENT_BY) AS departmentalhead
,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.INVENTORY_APPROVED_BY) AS inventory
,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.SECURITY_PASS_BY) AS security
,(SELECT designation FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS DESIGNATION
from gatepass_masterdata m 
LEFT JOIN erppic.hrm_employeepicture received ON m.userid = received.vemployeeid
where m.id=$gp_master_id");

// $masterData = $accessoriesModel->getData("select m.id,to_recipient,m.gp_no,gp_date,m.userid,total_qty,address,LISTAGG(orderinformation,',') within group(order by i.id asc) as orderinfo,FROM_SOURCE,returnable
// ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS USER_NAME
// ,(SELECT designation FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS DESIGNATION
// from gatepass_itemdata i left join gatepass_masterdata m on m.id=i.gp_master_id --and (m.DELETE_STATUS=0 or m.delete_status=null)
// where m.id=$gp_master_id
// group by m.gp_no,to_recipient,gp_date,m.userid,total_qty,address,m.id,FROM_SOURCE,returnable");

$data=$accessoriesModel->getData("select i.*,(SELECT MRD_TRIM_UNIT_NAME FROM INV.MRD_TRIM_UNIT MTU WHERE MTU.MRD_TRIM_UNIT_ID= i.unit) AS unit_name
from gatepass_itemdata i where gp_master_id=$gp_master_id
");

require_once('fpdf.php');
class PDF extends FPDF{

    // ====================================

    public function Header(){
        global $masterData;
        $this->SetFont('Arial','b',5.5);
        $this->Cell( 10, 15, $this->Image('fakir-logo-design-web-design.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
        $this->Ln(5);
        $this->MultiCell(0,4,"(AN ISO 9001 : 2008, SCR, BSCI, SEDEX, ORGANIC & OEKO TEX CERTIFIED COMPANY)", '', 'C');
        $this->SetDrawColor(245,131,34);
        $this->Line(0, $this->GetY(),  $this->GetPageWidth(), $this->GetY());
        $this->Ln(1);
        if($this->PageNo() == 1){
              $this->SetFont('Times','b',10);
              $this->SetDrawColor(1,1,1);
              $this->SetFillColor(54, 52, 52);
            //   $this->SetTextColor(255, 255, 255);
              $this->SetX($this->GetPageWidth()/2 - 15);
              $this->Cell(30, 5, 'Gate Pass', 1, 1, 'C' );
            //   $this->Cell(30, 5, 'Gate Pass', 1, 1, 'C', true );
              $this->Ln(2);
                 $this->SetFont('Times','',9);
              $this->SetTextColor(0, 0, 0);
             
                  $tableHeader = array(
                        'columnName'          => array('Date', $masterData[0]['GP_DATE'], '', 'From', $masterData[0]['FROM_SOURCE']),
                        'columnWidth'         => array(18, 64, 2, 22, 58),
                        'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
                        'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
                        'columnFontSize'      => 8.5,
                        'columnFontWeight'    => array('b', '', '','b', ''),
                        'columnAlign'         => 'L', //options center, left, right
                        'columnVerticalAlign' => 'middle' // middle, top, bottom
                  );
                  $this->smartRow($tableHeader, true);
                  // $this->Ln();
                  $tableHeader = array(
                        'columnName'          => array('To', htmlspecialchars_decode($masterData[0]['TO_RECIPIENT'], ENT_QUOTES), '', 'Gate Pass No.', $masterData[0]['GP_NO']),
                        'columnWidth'         => array(18, 65, 1, 22, 52),
                        'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
                        'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
                       'columnFontSize'      => 8.5,
                        'columnFontWeight'    => array('b', '', '','b', ''),
                        'columnAlign'         => 'L', //options center, left, right
                        'columnVerticalAlign' => 'middle' // middle, top, bottom
                  );
                  $this->smartRow($tableHeader, true);
                  //$this->Ln();
                     $tableHeader = array(
                        'columnName'          => array('Address', strlen($masterData[0]['ADDRESS']) > 44 ? substr($masterData[0]['ADDRESS'],0,23)."..." : $masterData[0]['ADDRESS'], '','Total Qty',$masterData[0]['TOTAL_QTY'] .' '),
                        // 'columnName'          => array('Address', strlen($masterData[0]['ADDRESS']) > 24 ? substr($masterData[0]['ADDRESS'],0,23)."..." : $masterData[0]['ADDRESS'], '','Total Qty',$masterData[0]['TOTAL_QTY'] .' '),
                        'columnWidth'         => array(18, 65, 1, 22, 52),
                        'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
                        'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
                        'columnFontSize'      => 8.5,
                        'columnFontWeight'    => array('b', '', '','b', ''),
                        'columnAlign'         => 'L', //options center, left, right
                        'columnVerticalAlign' => 'middle' // middle, top, bottom
                  );
                  $this->smartRow($tableHeader);
                  $this->Ln();
                  $tableHeader = array(
                        // 'columnName'          => array('Name', strlen($masterData[0]['USER_NAME']) > 30 ? substr($masterData[0]['USER_NAME'],0,29)."..." : $masterData[0]['USER_NAME'], '','Designation', $masterData[0]['DESIGNATION']),
                        'columnName'          => array('Name', strlen($masterData[0]['USER_NAME']) > 30 ? substr($masterData[0]['USER_NAME'],0,29)."..." : $masterData[0]['USER_NAME'], '','Return Date', isset($masterData[0]['RETURN_DATE'])?$masterData[0]['RETURN_DATE']:'Not Returnable'),
                        'columnWidth'         => array(18, 65, 1, 22, 52),
                        'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
                        'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
                        'columnFontSize'      => 8.5,
                        'columnFontWeight'    => array('b', '', '','b', ''),
                        'columnAlign'         => 'L', //options center, left, right
                        'columnVerticalAlign' => 'middle' // middle, top, bottom
                  );
                  $this->smartRow($tableHeader);
                  $this->Ln();
                  // $tableHeader = array(
                  //       'columnName'          => array('Returnable', strlen($masterData[0]['RETURNABLE']) > 30 ? substr($masterData[0]['RETURNABLE'],0,29)."..." : $masterData[0]['RETURNABLE']),
                  //       'columnWidth'         => array(18, 65, 1, 22, 52),
                  //       'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
                  //       'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
                  //       'columnFontSize'      => 8.5,
                  //       'columnFontWeight'    => array('b', '', '','b', ''),
                  //       'columnAlign'         => 'L', //options center, left, right
                  //       'columnVerticalAlign' => 'middle' // middle, top, bottom
                  // );
                  // $this->smartRow($tableHeader);
                  // $this->Ln();

                  $this->Ln(-39);
            //   endif;
              $this->SetX($this->GetPageWidth() - 50.5);
              $this->SetFont('Times','b',8);
              $this->Cell(34, 6.5, '', 0, 1, 'L', false );
            //   $this->Cell(34, 6.5, 'Original Copy : ', 0, 1, 'L', false );
              $this->Ln(-4.9);
              $this->SetX($this->GetPageWidth() - 28.5);
              $this->SetFont('Times','',8);
                 $this->Cell(20, 3.5, 'INV/C/3/001', 0, 1, 'L', false );
              $this->SetX($this->GetPageWidth() - 28.5);
              $this->Cell(34, 3.5, 'ISSUE # 01', 0, 1, 'L', false );
              $this->SetX($this->GetPageWidth() - 28.5);
              $this->Cell(34, 3.5, 'PAGE: 01', 0, 1, 'L', false );
              $this->Ln(3.6);
              $this->SetTextColor(0, 0, 0);
              $this->SetX($this->GetPageWidth() -59);
              $this->SetDrawColor(54, 52, 52);
              $this->SetFont('Times','',8.5);

        }
            //print_r(count($this->page));
  }



  public function Footer() {
    global $masterData;
    global $createdUserName;
    global $orderInfo;
    global $orderNumber;
    global $fklNumber;
    global $AOP;
    $cellWidth = ($this->GetPageWidth() - 20 ) / 5;
    $arrayMrdSignature1 = array('dashed' => array('Prepared By', '---------------', 'Inventory', 'Head of Department', 'Approved By'), 'cellwidth' => array($cellWidth-8, $cellWidth-8, $cellWidth+6, $cellWidth-1, $cellWidth+19, $cellWidth, $cellWidth-5), 'signDesignation' => array($masterData[0]['USER_NAME'], 'Received By', $masterData[0]['INVENTORY'], $masterData[0]['DEPARTMENTALHEAD'], $masterData[0]['SECURITY']));
    $arrayMrdSignature2 = array('dashed' => array('Prepared By', '---------------', '-----------------------------', '-------------------', '-------------------'), 'cellwidth' => array($cellWidth-8, $cellWidth-8, $cellWidth+6, $cellWidth-1, $cellWidth, $cellWidth+19, $cellWidth-5), 'signDesignation' => array($masterData[0]['USER_NAME'], 'Received By', $masterData[0]['INVENTORY'], $masterData[0]['DEPARTMENTALHEAD'], $masterData[0]['SECURITY']));
    $purchase = 'ITEMNAME'== 'AOP' ? $arrayMrdSignature2 : $arrayMrdSignature1; 
    if(!isset($this->footerset[$this->page])) {
          $this->SetY(-20);       
          $this->Ln(1);
              $tableFooter = array(
                    'columnName'          => $purchase['dashed'],
                    'columnWidth'         => $purchase['cellwidth'],
                    'columnFontSize'      => 8,
                    'columnFontWeight'    => '',
                    'columnHeight'        => 3, //set column padding
                    'columnAlign'         => 'C', //options center, left, right
                    'columnVerticalAlign' => 'middle' // middle, top, bottom
              );
              $this->smartRow($tableFooter, true);
              //$this->Ln(2);
              
              $tableFooter = array(
                    'columnName'          => $purchase['signDesignation'],
                    'columnWidth'         => $purchase['cellwidth'],
                    'columnFontSize'      => 8,
                    'columnFontWeight'    => '',
                    'columnHeight'        => 3, //set column padding
                    'columnAlign'         => 'C', //options center, left, right
                    'columnVerticalAlign' => 'top' // middle, top, bottom
              );
              $this->smartRow($tableFooter);
        //   endif;

          $this->Ln(4.4);
          $this->SetFont('Times','',7);
          $this->Cell($this->GetPageWidth() - 15,9,'Page '.$this->PageNo().'/{nb}',0,0,'R');
          $this->Ln(1);
          $this->SetTextColor(226, 226, 226);
          $this->SetFont('Arial','',18);
          $this->TextWithDirection(6, $this->GetPageHeight() - 10, 'www.fakirknit.com', 'U');
          $this->SetTextColor(0, 0, 0);
          $this->SetFont('Times','BU',9);
          $this->Write(7, 'Registered Office');
          $this->Ln(5);
          $this->SetX(0);
          $this->SetFont('Times','',7);
          $tableFooter = array(
                'columnName'          => array('Kayempur, Fatullah, Narayanganj, Bangladesh. Tel: +88-02-55110921-4, 09678005006-7. Fax: +88-02-9569852', 'E-mail : fklinfo@fakirgroup.com'),
                'columnWidth'         => array(169, 41),
                'columnBackground'    => array(array('r'=> 245, 'g'=>131, 'b' => 34), array('r'=> 43, 'g'=>43, 'b' => 43)),
                'textColour'          => array(array('r' => 255, 'g' => 255, 'b' => 255), array('r' => 255, 'g' => 255, 'b' => 255)),
                'columnFontSize'      => 8.1,
                'columnFontWeight'    => '',
                'columnHeight'        => 5.5, //set column padding
                'columnAlign'         => 'L', //options center, left, right
                'columnVerticalAlign' => 'middle' // middle, top, bottom
          );
          $this->smartRow($tableFooter);
          $this->Ln(0);        
          $this->footerset[$this->page] = true;
    }
}

    // ====================================

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

function gatePassTable($header, $data,$rowHeight)
{
    // Column widths
    global $masterData;
    $this->SetY($this->GetY() + 24);
    $this->SetFont('Times','',8.5);
    $this->SetFillColor(222, 222, 222); 
    $this->SetLineWidth(.1);
    $w = array(10, 25, 80, 18, 10, 65);
//     $w = array(10, 45, 60, 18, 10, 45);

    $this->SetLineWidth(.1);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],6,$header[$i],1,0,'C',1);
    $this->Ln();
    $totalCount =0;
    $unit = '';
    foreach($data as $key=>$row)
    {
      $totalCount += $row['QTY'];
      $unit = $row['UNIT_NAME'];
        $this->Cell($w[0],$rowHeight,$key+1,1);
        $this->Cell($w[1],$rowHeight,htmlspecialchars_decode($row['ORDERINFORMATION'],ENT_QUOTES),1);
        $this->Cell($w[2],$rowHeight,htmlspecialchars_decode($row['DESCRIPTION'],ENT_QUOTES),1);
        $this->Cell($w[3],$rowHeight,$row['QTY'],1);
        $this->Cell($w[4],$rowHeight,htmlspecialchars_decode($row['UNIT_NAME'],ENT_QUOTES),1);
        // ========================================
        // ========================================
        $this->Cell($w[5],$rowHeight,htmlspecialchars_decode($row['REMARKS'],ENT_QUOTES),1);
        $this->Ln();
    }
    // $this->Cell(4,htmlspecialchars_decode($masterData[0]['TOTAL_QTY'],ENT_QUOTES),1);
//     ============for last line================
$this->SetFillColor(228,228,228); // RGB values for #666

// Create cells for each column in the row
$this->Cell($w[0], $rowHeight, '', 1, 0, 'L', 1); // Empty cell for the first column
$this->Cell($w[1], $rowHeight, '', 1, 0, 'L', 1); // Empty cell for the second column
$this->Cell($w[2], $rowHeight, 'Total Qty', 1, 0, 'L', 1); // Cell with content for the third column
$this->Cell($w[3], $rowHeight, $totalCount, 1, 0, 'L', 1); // Cell with content for the fourth column
$this->Cell($w[4], $rowHeight, $unit, 1, 0, 'L', 1); // Cell with content for the fifth column
$this->Cell($w[5], $rowHeight, '', 1, 1, 'L', 1); // Empty cell for the last column and move to the next line
//     ============for last line================
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}



}


$pdf = new PDF();
// $pdf = new PDF('L', 'mm', 'A4');
// $pdf = new FPDF('L', 'mm', 'A4');
// Column headings
$header = array('Sl','Order Information', 'Description', 'Qty', 'Unit','Remark\'s');

$pdf->SetFont('Times','',8.5);

// $pdf->AddPage();
$pdf->AddPage();
$pdf->AliasNbPages();
// $pdf->Header();
$pdf->gatePassTable($header,$data,3.8);

$pdf->Output('Gatepass.pdf', 'I');
?>