<?
$db = 'STOCK';
$IDP =27;
$mConn = mysqli_connect('localhost','root','Lleesdlm', $db) or die ("impossible la connexió amb el servidor $host");
//mysqli_select_db($db) or die (erroni("la taula $db no existeix"));
?>
<html>
<head>
<title>debug STOCK</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css/fitxa.css">
</head>
<body>
<div id="logo">
	<a href="http://192.168.0.12/stock/">
	<img src="img/stock.png" width="27" height="27" style="vertical-align: text-bottom;" alt="STOCK" /> <div id="logotext">ST<font color="#63819c">O</font>CK<br>
	<div id="sublogo">du <font style="color:#63819c;font-weight: bold;">CIN&Eacute;MA</font></div>
	</div></a>
</div>
<div id="cont">
<?

	$TITOL = 'Las cumbre escarlata';
  	$sql = "SELECT TITOL FROM CINEMA WHERE TITOL LIKE '$TITOL'"; 
  	$resIntro = mysqli_query($mConn, $sql) or die (mysqli_error($mysqlConn2));
	$total = mysqli_num_rows($resIntro);
	echo $total;
  	//if (mysqli_num_rows($resIntro) < 2) { //comproba que la pel·lícula no hi sigui amb el mateix títol
	
	
	
//	$coinci = "select TITOL, INTERPRETS from CINEMA where ID!=$IDP";
	$coinci = "select ID, TITOL, INTERPRETS from CINEMA";
	$coinci = mysql_query($coinci);
	while ($row = mysql_fetch_array($coinci, MYSQL_ASSOC))
	{
		$person[$i] = preg_replace('/(.*?)\((.*?)\)/','\1',$row['INTERPRETS']);
		$person[$i] = stripslashes($person[$i]);
		$person[$i] = trim($person[$i]);
		$titol[$i] = array($row['ID'] => explode(',', strtolower($person[$i])));
//		$inter[$i] = explode(',', strtolower($person[$i]));
/*
		while (list (, $valor[$i]) = each($inter[$i])) {
			$inter2[$ii] = array($titol[$i] => array(trim($valor[$i])));
			$ii++;
		} */
		$i++;
	}
//	echo($i.'<br>');

	if (in_array($val,$valium)) {
		echo '<b>eureka</b>';
	}

	$coins=0;
	echo '<div class="titolor">'.count($titol).'</div><br>';
						echo '<div class="titolor">'.$val.'</div>';
		while (list ($clau, $valor) = each($titol)) {
//			echo count($valor).'<br>';

				while (list ($clau2, $valium) = each($valor)) {
					$peli = "select TITOL from CINEMA where ID=$clau2";
					$peli = mysql_query($peli);
					$peli= mysql_fetch_array($peli);
				 echo '<p><div class="titolor"><font color="#DDD">(</font>'.$clau2.'<font color="#DDD">)&nbsp;<b>'.$peli[TITOL].'&nbsp;'.count($valium).'</b></font> actors</div>';
						$tot = $tot+count($valium);
				 	while (list (, $valium2) = each($valium)) {
					 echo $valium2.'<br>';
					 $valium2 = trim($valium2);
													$coinss++;
						if ($valium2 == $val) {
							$peli = "select TITOL from CINEMA where ID=$clau2";
							$peli = mysql_query($peli);
							$peli= mysql_fetch_array($peli);
							$coins++;
//											 echo '<p><div class="titolor"><font color="#DDD">(</font>'.$clau2.'<font color="#DDD">)&nbsp;<b>'.count($valium).'</b></font> actors</div>';
							echo $peli[0];
						}
					//	echo'<br>';
					} // últim while
				} //segon while
		} //primer while

echo $tot.'-'.$coinss;

	?>
	</div>
	</body>
	</head>
	</html>
