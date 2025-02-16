<?php
ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);

if(isset($_GET['master_id']))
$masterId = $_GET['master_id'];


$queryResult = $accessoriesModel->getData("SELECT 
MPM_SL,MPM_EUSER,MPM_NO,
TO_CHAR(MPM_EDATE,'dd-MM-yyyy') AS MPM_EDATE,MPM_ETIME,MPM_NO,TO_DATE(MPM_CDATE,'dd-MM-yyyy') AS MPM_CDATE,
MPM_BUYER_ID,MRD_BUYER_STYLE_ID,MRD_BUYER_SEASON_ID,MRD_BUYER_DEPT_ID,MPM_PACK_TYPE,MPM_PACK_NUMBER,MPM_ORDER_QTY,MPF_TOTAL_FAB_PRICE,
MPT_TOTAL_TRIM_PRICE,MPO_TOTAL_PRICE,MPC_TOTAL_PROFIT,MPC_TOTAL_EXCES,MPC_TOTAL_CM,MPM_TOTAL_FABRIC_PRICE,MPM_TOTAL_TRIM_PRICE,MPM_TOTAL_OTHER_PRICE,
MPM_TOTAL_MATERIAL_PRICE,MPM_TOTAL_CM_PRICE,MPM_TOTAL_CB_PRICE,MPM_PROFIT_PRICE,MPM_FOB_PRICE,MPM_UNIT_PRICE,MPM_TOTAL_PRICE,MPM_REMARKS,MPM_PRICE_DEFF,MPM_OFFER_PRICE,
(SELECT vempname FROM erp.hrm_vw_employeeinfo U WHERE U.vemployeeid = MPM.MPM_EUSER) AS USER_NAME,(SELECT MRD_BUYER_NAME FROM inv.MRD_BUYER MB WHERE MB.MRD_BUYER_ID = MPM.MPM_BUYER_ID ) AS BUYER_NAME,
(SELECT MRD_BUYER_STYLE_NAME FROM inv.MRD_BUYER_STYLE MBS WHERE MBS.MRD_BUYER_STYLE_ID = MPM.MRD_BUYER_STYLE_ID) AS BUYER_STYLE,
(SELECT MRD_BUYER_SEASON_NAME FROM inv.MRD_BUYER_SEASON MS WHERE MS.MRD_BUYER_SEASON_ID = MPM.MRD_BUYER_SEASON_ID) AS BUYER_SEASON,
(SELECT MD.MRD_BUYER_DEPT_NAME FROM inv.MRD_BUYER_DEPT MD WHERE MD.MRD_BUYER_DEPT_ID = MPM.MRD_BUYER_DEPT_ID) AS BUYER_DEPT,
(SELECT mip.mrd_item_pack_name FROM inv.mrd_item_pack mip WHERE mip.mrd_item_pack_id = MPM.MPM_PACK_TYPE) AS pack_type,
(select listagg((SELECT mf.mrd_fab_name  FROM inv.mrd_fab mf WHERE mf.mrd_fab_id = mpf.mrd_fab_id),',') 
within group(order by mrd_fab_id) AS fab_name from inv.mrd_precosting_fabric mpf where mpf.mpm_no='$masterId') as fab_name,(select round(sum(mpf_cadcon),5) as cadcon FROM inv.mrd_precosting_fabric cad where cad.mpm_no=130) AS cadcon FROM inv.
MRD_PRECOSTING_MASTER  MPM where mpm.mpm_no='$masterId'");
// $queryResult = $accessoriesModel->getData("SELECT 
// MPM_SL,MPM_EUSER,MPM_NO,
// TO_CHAR(MPM_EDATE,'dd-MM-yyyy') AS MPM_EDATE,MPM_ETIME,MPM_NO,TO_DATE(MPM_CDATE,'dd-MM-yyyy') AS MPM_CDATE,
// MPM_BUYER_ID,MRD_BUYER_STYLE_ID,MRD_BUYER_SEASON_ID,MRD_BUYER_DEPT_ID,MPM_PACK_TYPE,MPM_PACK_NUMBER,MPM_ORDER_QTY,MPF_TOTAL_FAB_PRICE,
// MPT_TOTAL_TRIM_PRICE,MPO_TOTAL_PRICE,MPC_TOTAL_PROFIT,MPC_TOTAL_EXCES,MPC_TOTAL_CM,MPM_TOTAL_FABRIC_PRICE,MPM_TOTAL_TRIM_PRICE,MPM_TOTAL_OTHER_PRICE,
// MPM_TOTAL_MATERIAL_PRICE,MPM_TOTAL_CM_PRICE,MPM_TOTAL_CB_PRICE,MPM_PROFIT_PRICE,MPM_FOB_PRICE,MPM_UNIT_PRICE,MPM_TOTAL_PRICE,MPM_REMARKS,MPM_PRICE_DEFF,MPM_OFFER_PRICE,
// (SELECT vempname FROM erp.hrm_vw_employeeinfo U WHERE U.vemployeeid = MPM.MPM_EUSER) AS USER_NAME,(SELECT MRD_BUYER_NAME FROM inv.MRD_BUYER MB WHERE MB.MRD_BUYER_ID = MPM.MPM_BUYER_ID ) AS BUYER_NAME,
// (SELECT MRD_BUYER_STYLE_NAME FROM inv.MRD_BUYER_STYLE MBS WHERE MBS.MRD_BUYER_STYLE_ID = MPM.MRD_BUYER_STYLE_ID) AS BUYER_STYLE,
// (SELECT MRD_BUYER_SEASON_NAME FROM inv.MRD_BUYER_SEASON MS WHERE MS.MRD_BUYER_SEASON_ID = MPM.MRD_BUYER_SEASON_ID) AS BUYER_SEASON,
// (SELECT MD.MRD_BUYER_DEPT_NAME FROM inv.MRD_BUYER_DEPT MD WHERE MD.MRD_BUYER_DEPT_ID = MPM.MRD_BUYER_DEPT_ID) AS BUYER_DEPT,
// (SELECT mip.mrd_item_pack_name FROM inv.mrd_item_pack mip WHERE mip.mrd_item_pack_id = MPM.MPM_PACK_TYPE) AS pack_type
// FROM inv.
// MRD_PRECOSTING_MASTER  MPM where  mpm_no='$masterId'");

// echo '<pre>';
// print_r($queryResult);
// exit;
?>
<!DOCTYPE html>
<html lang="en">
<style>
	caption{
		background-color: cornflowerblue;
	}	
</style>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./vendor/metro4/css/metro-all.min.css">
	<!-- <link rel="stylesheet" href="metro-all.min.css"> -->
	<!-- <link rel="stylesheet" href="vendor/metro4/css/metro-all.min.css"> -->
	<!-- <link rel="stylesheet" href="css/print.min.css"> -->
	<title>Report</title>


	<?php //require_once './inc/head.php'; 
	?>
<style>
	/* @media screen {
  div.divFooter {
    display: none;
  }
} */
@media print {
  div#fotter {
	margin-top: 50px;
	text-align: right;
    position: fixed;
    bottom: 0;
  }

}

#pageFooter:after {
    counter-increment: page;
	content: counter(page);
}
</style>
</head>

<body>
	<!-- Page A4 -->
	<div class="ml-4 mr-4" id="content">
		<!-- Titel -->
		<div class="divFooter">
			<div class="text-center">
				
			<!-- <img src="FKL_LOGO.png" class="" width="160px" height="60px" style="line-height: 0px;"> -->
				
				<img src="./reports/fkl-logo-new.png" class="" width="160px" height="60px" style="line-height: 0px;">
				<p style="line-height: 0px;font-size:8px">AN ISO 9001 : 2008, SCR, BSCI, SEDEX, ORGANIC & OEKO TEX CERTIFIED COMPANY</p>
				<hr style="line-height: 0px;" color="red" size="10">
				<h4 class="text-center" style="line-height: 0px;">COST SHEET</h4>
			</div>
		</div>
		<div class="">
		<div class="float-left m-0">
			<b>	
				<?php
					
				?>
			<table class="table row-border cell-border table-border subcompact">
				<tr>
					<td style="background-color: dedede;">BUYER NAME</td>
					<!-- <td style="background-color: dedede;">:</td> -->
					<td><?php echo $queryResult[0]['BUYER_NAME']; ?></td>
				</tr>
				<tr>
					<td style="background-color: dedede;">DEPARTMENT</td>
					<!-- <td style="background-color: dedede;">:</td> -->
					<td width="350PX"><?php echo $queryResult[0]['BUYER_DEPT']; ?></td>
				</tr>
				<tr>
					<td style="background-color: dedede;">SEASON</td>
					<!-- <td>:</td> -->
					<td><?php echo $queryResult[0]['BUYER_SEASON']; ?></td>
				</tr>
				<tr>
					<td style="background-color: dedede;">STYLE NAME</td>
					<!-- <td>:</td> -->
					<td><?php echo $queryResult[0]['BUYER_STYLE']; ?></td>
				</tr>
				<tr style="vertical-align: top;">
					<td style="background-color: dedede;">FABRICATION</td>
					<!-- <td>:</td> -->
					<td><?php echo $queryResult[0]['FAB_NAME']; ?></td>
				</tr>
			</table>
			</b>
		</div>

	<div class="float-right">
		<b>
		<table class="table table-border row-border cell-border subcompact">
			<tr>
				<td style="background-color: dedede;">TRACKING ID</td>
				<!-- <td>:</td> -->
				<td><?php echo $queryResult[0]['MPM_NO']; ?></td>
			</tr>
			<tr>
				<td style="background-color: dedede;">DATE</td>
				<!-- <td>:</td> -->
				<td><?php echo $queryResult[0]['MPM_CDATE']; ?></td>
			</tr>
			<tr>
				<td style="background-color: dedede;">PRICE/PCS</td>
				<!-- <td>:</td> -->
				<td><?php echo round($queryResult[0]['MPM_UNIT_PRICE'],5); ?></td>
			</tr>
				<tr>
				<td style="background-color: dedede;">OFFER PRICE/PCS</td>
				<!-- <td>:</td> -->
				<td><?php echo round($queryResult[0]['MPM_OFFER_PRICE'],5); ?></td>
			</tr>
			</tr>
				<tr>
				<td style="background-color: dedede;">PRICE/PCS COMPARISION</td>
				<!-- <td>:</td> -->
				<td><?php echo round($queryResult[0]['MPM_PRICE_DEFF'],5); ?></td>
			</tr>
			<tr>
				<td style="background-color: dedede;">CONSUMPTION KG/DZN</td>
				<!-- <td>:</td> -->
				<td><?php echo $queryResult[0]['CADCON']; ?></td>
			</tr>
			<tr style="vertical-align: top;">
				<td style="background-color: dedede;">ORDER QTY <br> (PCS/PACK)</td>
				<!-- <td>:</td> -->
				<td><?php echo $queryResult[0]['MPM_ORDER_QTY']; ?></td>
			</tr>
		</table>
		</b>
	</div>
	</div>
	<!-- top sec clz-->
	<!--fab details -->
	<!-- <div class="container1"> -->
		<!-- <div class="relative-div"> -->
			<!-- fab table -->

			<table class="table row-border cell-border table-border subcompact">
				<caption align="center"><b>BULK CONSUPTION</b></caption>
				<thead>
					<th class="text-center">SL</th>
					<th class="text-center">Item</th>
					<th class="text-center" style="min-width: 300px;">Fabrication</th>
					<th class="text-center">Color</th>
					<th class="text-center">Yarn Count</th>
					<th class="text-center">GSM</th>
					<th class="text-center">CAD Con</th>
					<th class="text-center">Ratio</th>
					<th class="text-center">Greige Fabric<br>(Kg)</th>
					<th class="text-center">Yarn Price<br>(Kg)</th>
					<th class="text-center">Knit Price<br>(Kg)</th>
					<th class="text-center">Dying Price<br>(Kg)</th>
					<th class="text-center">Fabric Cost<br>(Kg)</th>
					<th class="text-center">AOP/YD Price<br>(Kg)</th>
					<th class="text-center">AOP/YD Cost<br>(Kg)</th>
					<th class="text-center">Total Cost</th>
				</thead>
				<tbody>
					<?php 
						$fabTable = $accessoriesModel->getData("SELECT MPF_SL,MPF.MPM_NO, MRD_ITEM_ID,(SELECT MRD_ITEM_NAME FROM INV.MRD_ITEM MI WHERE MI.MRD_ITEM_ID = MPF.MRD_ITEM_ID) AS MRD_ITEM_NAME,MRD_FAB_ID,(SELECT MRD_FAB_NAME FROM INV.MRD_FAB MF WHERE MF.MRD_FAB_ID= MPF.MRD_FAB_ID) AS MRD_FAB_NAME,MRD_COLOR_ID,(SELECT MRD_COLOR_NAME FROM INV.MRD_COLOR MC WHERE MC.MRD_COLOR_ID= MPF.MRD_COLOR_ID) AS MRD_COLOR_NAME,MRD_YARN_COUNT_ID,(SELECT MRD_YARN_COUNT_NAME FROM INV.MRD_YARN_COUNT MYC WHERE MYC.MRD_YARN_COUNT_ID= MPF.MRD_YARN_COUNT_ID) AS MRD_YARN_COUNT_NAME,MPF_GSM,MPF_CADCON,MPF_RATIO,MPF_GREIGE_FABRIC,MPF_YARN_PRICE,MPF_KNIT_PRICE,MPF_DYEING_PRICE,MPF_FAB_COST,MPF_AOP_YD_PRICE,MPF_AOP_COST,MPF_FAB_PRICE FROM INV.MRD_PRECOSTING_FABRIC MPF WHERE MPF.MPM_NO='$masterId' order by MPF_SL asc");
					// echo '<pre>';
					// print_r($fabTable);
					// exit;
					$f = 0;
					foreach( $fabTable as $fab){
						$f++;
					// 	echo '<pre>';
					// print_r($fab);
					
					?>
					<tr>
						<td><?php echo $f; ?></td>
						<td><?php echo $fab['MRD_ITEM_NAME'] ?></td>
						<td><?php echo $fab['MRD_FAB_NAME'] ?></td>
						<td><?php echo $fab['MRD_COLOR_NAME'] ?></td>
						<td><?php echo $fab['MRD_YARN_COUNT_NAME'] ?></td>
						<td><?php echo $fab['MPF_GSM'] ?></td>
						<td><?php echo round($fab['MPF_CADCON'],5) ?></td>
						<td><?php echo round($fab['MPF_RATIO'],5) ?></td>
						<td><b><?php echo round($fab['MPF_GREIGE_FABRIC'],5) ?></b></td>
						<td><?php echo round($fab['MPF_YARN_PRICE'],5) ?></td>
						<td><?php echo round($fab['MPF_KNIT_PRICE'],5) ?></td>
						<td><?php echo round($fab['MPF_DYEING_PRICE'],5) ?></td>
						<td><b><?php echo round($fab['MPF_FAB_COST'],5) ?></b></td>
						<td><?php echo round($fab['MPF_AOP_YD_PRICE'],5) ?></td>
						<td><b><?php echo round($fab['MPF_AOP_COST'],5) ?></b></td>
						<td><b><?php echo round($fab['MPF_FAB_PRICE'],5) ?></b></td>
					</tr>
					<?php } ?>
					</tbody>
			</table>
			<table align="right" class="table row-border cell-border subcompact">
				<tr align="right">
					<td align="right" style="background-color: dedede;"><b>Total Fabric Price</b> : </td>
					<td align="right">
						<div id="mpf_total_fab_price" name="mpf_total_fab_price"><b><?php echo round($queryResult[0]['MPF_TOTAL_FAB_PRICE'],5); ?></b></div>
					</td>
				</tr>
			</table>
		<!-- </div> -->
	<!-- </div> -->
	<!--fab table close -->
	<!-- trim table-->
	<br>
	<!-- <div class="cell-md-12 cell-sm-12"> -->
		<div class="row">
			<div class="float-left" style="width: 320px;">
				<table class="table row-border cell-border table-border subcompact">
					<caption align="center"><b>TRIM COST/DZN</b></caption>
					<thead>
						<th class="text-center">SL</th>
						<th class="text-center">Accesories Name</th>
						<th class="text-center">Unit</th>
						<th class="text-center">Price</th>
					</thead>
					<tbody>

					<?php 
						$accssoriesTrim = $accessoriesModel->getData("SELECT MPT_SL,MPM_NO,MRD_TRIM_ID,(SELECT MRD_TRIM_NAME FROM INV.MRD_TRIM MT WHERE MT.MRD_TRIM_ID= MPT.MRD_TRIM_ID) AS MRD_TRIM_NAME,MRD_TRIM_UNIT_ID,(SELECT MRD_TRIM_UNIT_NAME FROM INV.MRD_TRIM_UNIT MTU WHERE MTU.MRD_TRIM_UNIT_ID= MPT.MRD_TRIM_UNIT_ID) AS MRD_TRIM_UNIT_NAME,MPT_TRIM_PRICE	FROM INV.MRD_PRECOSTING_TRIM MPT WHERE MPM_NO ='$masterId'");
						// echo '<pre>';
						// print_r($accssoriesTrim);
						// exit;
						$t =0;
						foreach($accssoriesTrim as $acTrim){
							$t++;
					?>
						<tr>
							<td><?php echo $t ?></td>
							<td><?php echo $acTrim['MRD_TRIM_NAME'] ?></td>
							<td><?php echo $acTrim['MRD_TRIM_UNIT_NAME'] ?></td>
							<td><?php echo round($acTrim['MPT_TRIM_PRICE'],5) ?></td>
						</tr>
						<?php } ?>
					</tbody>	
				</table>
				<table align="right" class="table subcompact row-border cell-border">
					<tr align="right">
						<td align="right" style="background-color: dedede;"><b>Total Trim Price : </b></td>
						<td align="right">
							<div id="mpf_total_fab_price0" name="mpf_total_fab_price"><b><?php echo round($queryResult[0]['MPT_TOTAL_TRIM_PRICE'],5); ?></b></div>
						</td>
					</tr>
				</table>
			</div>
			
			<!--other details-->
			<div class="float-left" style="width: 300px; margin-left:20px;">
					<table class="table row-border cell-border table-border subcompact">
						<caption align="center"><b>OTHER COST/DZN</b></caption>
						<thead>
							<th class="text-center">SL</th>
							<th class="text-center">Other Cost</th>
							<th class="text-center">Unit</th>
							<th class="text-center">Price</th>
						</thead>
						<tbody>
						<?php 
						$ocTable = $accessoriesModel->getData("SELECT MPO_SL,MPM_NO,MRD_OTHER_COST_ID,(SELECT MRD_OTHER_COST_NAME FROM inv.MRD_OTHER_COST MOC WHERE MOC.MRD_OTHER_COST_ID= MPO.MRD_OTHER_COST_ID) AS MRD_OTHER_COST_NAME,MRD_TRIM_UNIT_ID,(SELECT MRD_TRIM_UNIT_NAME FROM inv.MRD_TRIM_UNIT MTU WHERE MTU.MRD_TRIM_UNIT_ID= MPO.MRD_TRIM_UNIT_ID) AS MRD_TRIM_UNIT_NAME,MPO_OTHER_PRICE FROM inv.MRD_PRECOSTING_OTHER MPO WHERE MPM_NO='$masterId'");
						// echo '<pre>';
						// print_r($accssoriesTrim);
						// exit;
						$o =0;
						foreach($ocTable as $oc){
							$o++;
					?>
							<tr>
								<td><?php echo $o ?></td>
								<td><?php echo $oc['MRD_OTHER_COST_NAME'] ?></td>
								<td><?php echo $oc['MRD_TRIM_UNIT_NAME'] ?></td>
								<td><?php echo round($oc['MPO_OTHER_PRICE'],5) ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<table align="right" class="table row-border cell-border subcompact">
						<tr align="right">
							<td align="right" style="background-color: dedede;"><b>Total Other Price :</b></td>
							<td align="right">
								<div name="mpo_total_price[]" id="mpo_total_price0"><b><?php echo round($queryResult[0]['MPO_TOTAL_PRICE'],5); ?></b></div>
							</td>
						</tr>
					</table>
				</div>
			
			<div class="float-right mt-5" style="width: 260px;margin-left:20px;">
				<table>
					<!-- <img src="FKL_LOGO.png" class="mt-5" width="280px" height="300px" style="line-height: 0px;"> -->
					<?php
				$pic = $accessoriesModel->getData("select * from inv.MRD_PRECOSTING_PIC where mpm_no='$masterId'");
				if($pic != 'Table is empty...'){
					// print_r($pic);
					// exit;
				$picture = $pic[0]['MPP_PICTURE']->load();
				$type = $pic[0]['MPP_PICTRUE_TYPE'];
			    // $picture = "<img src='data:".$type.";base64,".base64_encode($picture)."' class='avatar' style='width:100%;'>";
				?>
				<img src="data:<?php echo $type ?>;base64,<?php echo base64_encode($picture) ?>" class="" width="220px" height="180px" style="line-height: 0px;">
				<?php }
				else{
				?>
					<img src="./reports/no_image.jpg" alt="No Image" class="mt-5" width="260px" height="220px" style="line-height: 0px;">
				<?php } ?>
				</table>
			</div>
		</div>
		<br>
	<!-- </div> -->
		<!-- <div class="cell-md-6"> -->
		<!-- <div class="flot-right"> -->
		<!-- </div> -->
	<!--trim details clz -->
	
	
	<!--CM details-->
	<div class="row">
	<div class="float-left">
			<table class="table row-border cell-border table-border subcompact">
			<caption align="center"><b>CM CONSUMPTION</b></caption>
				<thead>
					<th class="text-center">SL</th>
					<th class="text-center">Item</th>
					<th class="text-center">SMV</th>
					<th class="text-center">EFF (%)</th>
					<th class="text-center">CPM</th>
					<th class="text-center">PROFIT (%)</th>
					<th class="text-center">Excess Accesories</th>
					<th class="text-center">CM</th>
				</thead>
				<b>
				<tbody>
				<?php 
						$cmTable = $accessoriesModel->getData("select 
						MPC_SL,MPM_NO,MRD_ITEM_ID,(SELECT MRD_ITEM_NAME FROM inv.MRD_ITEM MI WHERE MI.MRD_ITEM_ID = MPC.MRD_ITEM_ID) AS MRD_ITEM_NAME,MPC_SMV,MPC_EFF,MPC_CPM,MPC_PROFIT,MPC_EXCESS_ACC,MPC_CM
					from inv.MRD_PRECOSTING_CM MPC
					WHERE MPM_NO='$masterId'");
					// echo '<pre>';
					// print_r($fabTable);
					// exit;
					$c = 0;
					foreach( $cmTable as $cm){
						$c++;
					// 	echo '<pre>';
					// print_r($fab);
					?>
					<tr>
						<td><?php echo $c; ?></td>
						<td><?php echo $cm['MRD_ITEM_NAME'] ?></td>
						<td><?php echo round($cm['MPC_SMV'],5) ?></td>
						<td><?php echo round($cm['MPC_EFF'],5) ?></td>
						<td><?php echo round($cm['MPC_CPM'],5) ?></td>
						<td><b><?php echo round($cm['MPC_PROFIT'],5) ?></b></td>
						<td><?php echo round($cm['MPC_EXCESS_ACC'],5) ?></td>
						<td><b><?php echo round($cm['MPC_CM'],5) ?></b></td>
					</tr>
					<?php } ?>
				</tbody>
				</b>
			</table>

			<!-- table CM Total -->
			<table width="100%" border="0" class="table row-border table-border row-border cell-border subcompact">
				<tr>
					<td style="background-color: dedede;"><b>Total Profit : </b></td>
					<!-- <td>:</td> -->
					<td><strong><?php echo round($queryResult[0]['MPC_TOTAL_PROFIT'],5); ?></strong></td>
					<td style="background-color: dedede;"><b>Total Excess Acc :</b></td>
					<td><strong><?php echo round($queryResult[0]['MPC_TOTAL_EXCES'],5); ?></strong></td>
					<td style="background-color: dedede;"><b>Total CM Price :</b></td>
					<td><strong><?php echo round($queryResult[0]['MPC_TOTAL_CM'],5); ?></strong></td>
				</tr>
			</table>
		</div>
			
		</div>
	
	<!--CM details clz-->
	<!--grand total details -->
	<div >
		<table class="table row-border cell-border table-border subcompact">
		<caption style="background-color: BLUT;"><b>COST SUMMARY</b></caption>
			<thead>
				<td width="10%" style="color: white;text-align:center;"><b>Trim Price</td>
				<td width="10%" style="color: white;text-align:center;"><b>Fabric Price</td>
				<td width="10%" style="color: white;text-align:center;"><b>Other Price </td>
				<td width="10%" style="color: white;text-align:center;"><b>Material Price </td>
				<td width="10%" style="color: white;text-align:center;"><b>CM/Dzn </td>
				<td width="10%" style="color: white;text-align:center;"><b>Can Be </td>
				<td width="10%" style="color: white;text-align:center;"><b>Profit </td>
				<td width="10%" style="color: white;text-align:center;"><b>Fob/Dzn </td>
				<td width="10%" style="color: white;text-align:center;"><b>Unit Price </td>
				<td width="10%" style="color: white;text-align:center;"><b>Total Price </td>
			</thead>
			<tbody>
				<tr style="font-weight: 800;">
					<td><?php echo round($queryResult[0]['MPM_TOTAL_FABRIC_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_TOTAL_TRIM_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_TOTAL_OTHER_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_TOTAL_MATERIAL_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_TOTAL_CM_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_TOTAL_CB_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_PROFIT_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_FOB_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_UNIT_PRICE'],5); ?></td>
					<td><?php echo round($queryResult[0]['MPM_TOTAL_PRICE'],5); ?></td>
				</tr>
			</tbody>
		</table>
		<!-- Remarks -->
	
	</div>
	<div class="float-left">
				<table class="table row-border subcompact">
					<tr>
						<td><strong><u>Remark's : </u></strong></td>
						<td><?php echo $queryResult[0]['MPM_REMARKS'] ?></td>
					</tr>
				</table>
			</div>
	<!--grand total details clz-->
	<BR>
	<BR>
	<BR>
	<BR>
	<BR>
	<BR>
	<BR>
	<BR>
	<BR>

	<!--signature footer-->
	<footer >	
		<div id="fotter">
			<div>
			<table>
				<tr align="center">
					<td width="1%">	<u><?php echo $queryResult[0]['USER_NAME']; ?></u></td>
					<td width="1%"><b>______________________</b></td>
					<td width="1%"><b>__________________</b></td>
					<td width="1%"><b>______________________________</b></td>
				</tr>
				<tr align="center">
					<td width="1%"><b>Merchandiser</b></td>
					<td width="1%"><b>Manager</b></td>
					<td width="1%"><b>Internal Audit</b></td>
					<td width="1%"><b>GM <br>(Merchandising & Marketing)</b></td>
				</tr>
			</table>
			<div id="pageFooter">Page </div>
			</div>
		</div>
	</footer>	
	<!--signature footer clz-->
	
	
</div>
	<!-- Page A4 clz-->
	<?php //require_once './inc/footer.php'; 
	?>