<?php
//�������� �� ��
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("������ ����������� � ��");
$setnames = mysql_query( 'SET NAMES utf8' );
$idc = $_GET['idc'];
$sql="SELECT count(*) as cnt from `possession` where idc='$idc'";
$res = mysql_query($sql,$connect);
$row = mysql_fetch_array($res,MYSQL_ASSOC); 
$count = $row['cnt'];
$responce = new stdclass;
$responce->page = 1;
$responce->total = 1;
$responce->records = $count;
$i=0;

$SQL = "select idpr, attacked, occupancy_time from possession where idc='$idc'";
$result2 = mysql_query( $SQL,$connect );

while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) { 
	$status="";
	$idpr = $row["idpr"];
	$sql2 = "select prime_time, name, arena_name, revenue, type from province where id='$idpr'";
	$q2 = mysql_query($sql2,$connect);
	$row2 = mysql_fetch_array($q2,MYSQL_ASSOC);
	$responce->rows[$i]['id'] = $idpr;
	switch ($row2["type"]) {
		case "normal":
			$type = "<img src='images/province_type_normal.png'>";// alt='������� ���������' >";
			break;
		case "gold":
			$type = "<img src='images/province_type_gold.png'>"; //alt='�������� ���������' >";
			break;
		case "start":
			$type = "<img src='images/province_type_start.png'>";// alt='��������� ���������' >";
			break;
	}
	if ($row["attacked"]==1){
	$status="<img src='images/icons/attacked.png'>";
	}
	$name = $row2["name"];
	$name = "<a href='http://worldoftanks.ru/uc/clanwars/maps/?province=$idpr' target='_blank'>$name</a>";
	$responce->rows[$i]['cell']=array($type,$status,$name, $row2["arena_name"],date("H:i",$row2["prime_time"]),$row2["revenue"],$row["occupancy_time"]); //$clandays,$las_onl); 
	$i++; 
} 
//header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>