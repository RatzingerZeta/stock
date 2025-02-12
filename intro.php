<?
//error_reporting(1);
$db = 'STOCK';
$language = 'en';


$mysqlConn2 = mysqli_connect('localhost', 'root', 'Lleesdlm', $db);
mysqli_set_charset($mysqlConn2, "utf8");


if ($submit != '' AND empty($TITOL)) /*or ($captcha !=0 && empty($code)))*/{
   $send_color ="red";
   $send_result .= ""._REQUIRED."";
   $send_error = 1;
   }
   
if (isset($_GET['PID'])) {
   $ID = $_GET['PID'];
}


  if($_POST[submit] == 'introduir'){
	  
  	$TITOL = addslashes($_POST[TITOL]);
  	$ANY = $_POST[ANY];
  	$DIRECCIO = addslashes($_POST[DIRECCIO]);
  	$INTERPRETS = addslashes($_POST[INTERPRETS]);
  	$NACIONALITAT = $_POST[NACIONALITAT];
  	$GENERE = $_POST[GENERE];
  	$SINOPSI = addslashes($_POST[SINOPSI]);
  	$OBSERVACIONS = addslashes($_POST[OBSERVACIONS]);
  	$VAL = $_POST[VAL];
  	$IDIOMA = $_POST[IDIOMA];
  	$DUAL = $_POST[DUALX];
  	$SUB = $_POST[SUB];
  	$MUSICA = addslashes($_POST[MUSICA]);
  	$FOTOGRAFIA = addslashes($_POST[FOTOGRAFIA]);
  	$STOCK = $_POST[STOCK];	
	
  	$sql = "SELECT TITOL FROM CINEMA WHERE TITOL LIKE '$TITOL'"; 
  	$resIntro = mysqli_query($mysqlConn2, $sql) or die (mysqli_error($mysqlConn2));
  	if (mysqli_num_rows($resIntro) < 1) { //comproba que la pel·lícula no hi sigui amb el mateix títol
	  

  			if ($DUAL != 'yes' ) {
  				$DUAL = 'no';
  			}
  			if ($STOCK != 'yes') {
  				$STOCK = 'no';
  			}
  		$sql ="insert into CINEMA (TITOL, ANY, DIRECCIO, INTERPRETS, NACIONALITAT, GENERE, SINOPSI, OBSERVACIONS, VAL, IDIOMA, DUALX, SUB, MUSICA, FOTOGRAFIA, STOCK)";
		$sql.=	"values('$TITOL','$ANY','$DIRECCIO','$INTERPRETS','$NACIONALITAT','$GENERE','$SINOPSI','$OBSERVACIONS','$VAL','0',
				'$DUAL','0','$MUSICA','$FOTOGRAFIA','$STOCK')";
  		
		mysqli_query($mysqlConn2, $sql) or die (mysqli_error($mysqlConn2));
  		//$nouID= mysql_query("select max(ID) from CINEMA");
  		$nouID = mysqli_insert_id($mysqlConn2);
  		
		$missatge = '<div id="resultat">s\'ha introdu&iuml;t ('.$nouID.') <font color="#CCCCCC">'.stripslashes($_POST["TITOL"]).'</font> exitosament...<br>';
  		$missatge .= '<a href="http://192.168.1.18/stock/?PID='.$nouID.'">anar a la nova fitxa</a> de '.stripslashes($_POST["TITOL"]).'</div>';

	} else {
  	
	  	$missatge = '<div id="resultat"> ja s\'ha introdu&iuml;t <font color="#CCCCCC">'.stripslashes($TITOL).'</font>...</div>';
  	  	$segonaop = true;
  	}
  	//}
  //}
} elseif ($_POST[submit] == 'editar') {
	if ($DUAL != 'yes' ) {
		$DUAL = 'no';
	}
	if ($STOCK != 'yes') {
		$STOCK = 'no';
	}
	$sql = "update CINEMA SET TITOL='".addslashes($_POST[TITOL])."', ANY='$_POST[ANY]', DIRECCIO='".addslashes($_POST[DIRECCIO])."', INTERPRETS='".addslashes($_POST[INTERPRETS])."', NACIONALITAT='$_POST[NACIONALITAT]', GENERE='$_POST[GENERE]', SINOPSI='".addslashes($_POST[SINOPSI])."', OBSERVACIONS='".addslashes($_POST[OBSERVACIONS])."', VAL='$_POST[VAL]', IDIOMA='0', DUALX='$_POST[DUAL]', SUB='0', MUSICA='".addslashes($_POST[MUSICA])."', FOTOGRAFIA='".addslashes($_POST[FOTOGRAFIA])."', STOCK='$_POST[STOCK]'";
	$sql.=" where ID='$ID'";
	mysqli_query($mysqlConn2, $sql) or die (mysqli_error($mysqlConn2));
	$missatge = '<div id="resultat">s\'ha editat  ('.$ID.') <font color="#CCCCCC">'.stripslashes($_POST[TITOL]).'</font> exitosament...<br>';
	$missatge .= '<a href="http://192.168.0.12/stock/?PID='.$ID.'">tornar a la fitxa</a> de '.stripslashes($_POST[TITOL]).'</div>';
}

if (isset($_GET['PID'])) {
  	$query = "select * from CINEMA where ID='$ID'";
	$resIntro = mysqli_query($mysqlConn2, $query);

	while ($row = mysqli_fetch_array($resIntro))
	{
		$TITOL = stripslashes($row[TITOL]);
  		$ANY = $row[ANY];
  		$DIRECCIO = stripslashes($row[DIRECCIO]);
  		$INTERPRETS = stripslashes($row[INTERPRETS]);
  		$NACIONALITAT = $row[NACIONALITAT];
  		$GENERE = $row[GENERE];
  		$SINOPSI = stripslashes($row[SINOPSI]);
  		$OBSERVACIONS = stripslashes($row[OBSERVACIONS]);
  		$VAL = $row[VAL];
  		$IDIOMA = $row[IDIOMA];
  		$DUAL = $row[DUALX];
  		$SUB = $row[SUB];
  		$MUSICA = stripslashes($row[MUSICA]);
  		$FOTOGRAFIA = stripslashes($row[FOTOGRAFIA]);
  		$STOCK = $row[STOCK];
	}
}

function FormatDate($timestamp)
{
	$usertime = "1";
	$timeoffset = $usertime*3600;
	$timestamp = $timestamp+$timeoffset;
	$FormatDate = gmdate("d-m-Y",$timestamp);
	return $FormatDate;
}

function FormatTime($timestamp)
{
	$usertime = "1";
	$timeoffset = $usertime*3600;
	$timestamp = $timestamp+$timeoffset;
	$FormatTime = gmdate("H:i",$timestamp);
	return $FormatTime;
}

function recordfiltre($bule) {
	if (isset($_GET['lim'])) {
		$lim = $_GET['lim'];
	}
	if (isset($_GET['off'])) {$off = $_GET['off'];}
	if (isset($_GET['ord'])) {$ord = $_GET['ord'];}
	if (isset($_GET['ori'])) {$ori = $_GET['ori'];}
	if (isset($_GET['estoc'])) {$estoc = $_GET['estoc'];}	

if ($bule == true) {
	$conjun = '&';
} else {
	$conjun = '?';
}

	if (empty($off)) {
		$off = 0;
		$cad = $conjun.'off='.$off;
	} else {
		$cad = $conjun.'off='.$off;
	}

	if (empty($lim)) { $lim = 18;	} else { $cad .= '&lim='.$lim; }
	if (empty($ord)) { $ord = 'TITOL'; } else { $cad .= '&ord='.$ord; }
	if (empty($ori)) { $ori = 'asc'; } else { $cad .= '&ori='.$ori;	}
	if (!empty($estoc)) {
		$cad .= '&estoc='.$estoc;
	}

return $cad;
}

if (!isset($_GET['PID'])) {
	$TITOL = '';
	$ANY = 1899;
	$DIRECCIO = '';
	$INTERPRETS = '';
	$NACIONALITAT = 0;
	$GENERE = 0;
	$SINOPSI = '';
	$OBSERVACIONS = '';
	$VAL = 0;
	$IDIOMA = 0;
	$DUAL = '';
	$SUB = 0;
	$MUSICA = '';
	$FOTOGRAFIA = '';
	$STOCK = '';
}
?>
<html>
<head>
<title> STOCK --> by COMIENDOENTOKYO </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="form/core.js"></script>
<link media="screen" type="text/css" href="form/formcheck/theme/grey/formcheck.css" rel="stylesheet">
<style type="text/css">
body {	margin: 0 auto; text-align: center;
	font-family: helvetica; font-size: 10.8px;color : #C9C9C9;background: #181818; padding:30px 20px 20px 20px;}
A { CURSOR: default;  }
a:link {color: #666666; text-decoration : none;}
a:visited {color: #454545; text-decoration : none;}
a:hover {color: #666; text-decoration : none;CURSOR: default;}
.supersel{font-family: impact; color: #c3c666;}
.supersel a{color: #FFFFFF;}
.supersel a:visited{color: #FFFFFF;}
.supersel a:hover{color: #FFFFFF;}
#cont {padding: 18px; font-weight: normal; width: 36%; border: 1px dashed #333; margin: 18 auto;
	text-align: left; vertical-align: text-bottom; border-radius: 3px; display: table; position: relative; font-size: 12px;
}
input, textarea, select, checkbox {font-family: HELVETICA; font-size: 10.8px; color: #c39645; background-color: #121212; border-color: #333333; border-style: dashed; border-width: 0.9px; border-radius: 5px; padding: 1.8px; margin: 3px; vertical-align: middle; outline-style: none; resize: none;
}
.to {font-family: HELVETICA; font-size: 10.8px; color: #c39645; background-color: #121212; border-color: #666666; border-style: dashed; border-width: 0.9px; border-radius: 5px; padding: 1.8px; margin: 3px;
	vertical-align: middle; outline-style: none;}
#tos {
float: right; vertical-align: text-bottom; padding: 0px;
}
#tospar {
text-align: left;
}
#tosl {
text-align: left; margin-left: 27px;
}
#captcha {
display: inline-block;
padding-top: 33px;
text-align: left;
}
#submit {font-family: HELVETICA; font-size: 12px; color: #181818; font-weight: bold; background-color: #c39645; border-color: #333333; border-style: none; border-width: 0.9px;
	border-radius: 5px; vertical-align: middle;
}
#desfer {font-family: HELVETICA; font-size: 12px; color: #c39645; font-weight: bold; background-color: #181818; border-color: #333333; border: 1px dashed #666666;
	border-radius: 5px; vertical-align: middle;
}
#buttons{
display: table; float: left; margin-top: 18px;
}
.fosquet{color: #818181; font-weight: normal; font-variant: small-caps; font-size: 9pt;}
#logo{
	 margin-bottom: 0px; margin-top: -18px; text-align: left; font-size: 10.8pt; font-weight: bold; color: #63819c; vertical-align: text-top;
}
#logotext{
	 text-align: left;  color: #666;  display: inline-table; font: bold 2.1em/1.8em "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif;margin-top: -9.9pt;vertical-align: top;
}
#sublogo{
	 text-align: left;  color: #666;  display: inline-table; font: normal 0.36em/0.18em  Helvetica, Arial, Geneva, sans-serif;margin-top: -8.1pt; margin-left: 45px;
}
#esquerra {float: left; width: 18%; font-family: impact; color: #c3c666;}
#dreta {float: right; width: 18%; font-family: impact; color: #c3c666; text-align: right;}
#miniselgris{font-family: helvetica; color: #999999; font-size: 8.1px; font-variant: small-caps; text-align: right;}
#miniselverd{font-family: helvetica; color: #c3c666; font-size: 8.1px; font-variant: small-caps;}
#resultat {
font-size: 12pt; color: #c39645; font-weight: bold; text-align: center; margin: 180 auto;
}
</style>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<script type="text/javascript" src="./form/formcheck/lang/en.js"></script>
<script language="javascript" type="text/javascript" src="./form/formcheck.js"></script>
<script type="text/javascript">
	window.addEvent('domready', function(){
	postcomment = new FormCheck('cinema', {
display : {
errorsLocation : 1,
indicateErrors : 2,
keepFocusOnError : 1,
closeTipsButton : 1,
showErrors : 0
		}
	})
});
</script>
</head>
<body>
<?
$rec = recordfiltre(true);

if (!isset($ID)){
	$ID = 0;
}

if ($ID == 0 && isset($_GET['rec'])) {
	$enlla = 'http://192.168.1.18/stock/'.$_GET['rec'];
} else {
	$enlla = 'http://192.168.1.18/stock/?PID='.$ID.$rec;
}
 ?>
<div id="logo">
	<a href="<?=$enlla?>"><img src="img/stock.png" width="27" height="27" style="vertical-align: text-bottom;" alt="STOCK" /> <div id="logotext">ST<font color="#63819c">O</font>CK<br>
	<div id="sublogo">du <font style="color:#63819c;font-weight: bold;">CIN&Eacute;MA</font></div></a>
</div>
<?


if (isset($missatge)){
		echo $missatge;
	} else {
		$missatge = '';
	}
	
if ($missatge == '' || isset($segonaop)) {

?>

	<div id="cont">
		<form id="cinema" action="<?$PHP_SELF?>" method="POST">
		<div id="tos">T&Iacute;TOL<input name="TITOL" id="TITOL" class="validate['required'] text-input" size="50" value="<?=$TITOL?>"></div><BR>
		<div id="tos">DIRECCI&Oacute; <input name="DIRECCIO" size="50" value="<?=$DIRECCIO?>"></div><BR>
		<div id="tos">INT&Egrave;RPRETS <textarea name="INTERPRETS" style="height: 36px; width: 270px;"  value="<?=$INTERPRETS?>"><?=$INTERPRETS?></textarea></div><BR>
		<div id="tos">ANY <input name="ANY" size="4" value="<?=$ANY?>">
		 NACIONALITAT <select id="NACIONALITAT" name="NACIONALITAT">
		<?
		if (!isset($ID) or $NACIONALITAT < 1) {
			$valnac = 0;
			$rezanac = 'escull...';
		} else {
			$valnac = $NACIONALITAT;
			$query = "select NACIONALITAT from NACIONALITAT WHERE ID='$NACIONALITAT'";
			$result = mysqli_query($mysqlConn2, $query);
			$row= mysqli_fetch_array($result);
			$rezanac = $row[0];
		}
		?>
			<option value="<?=$valnac?>" selected><?=$rezanac?>
		<?
			//mysqli_select_db($db) or die ("la base de dades $db no existeix");
				$query = "select * from NACIONALITAT order by NACIONALITAT";
				$result = mysqli_query($mysqlConn2, $query);

				  while ($row = mysqli_fetch_array($result))
				{
					  echo '<option value="'.$row['ID'].'">'.$row['NACIONALITAT'].'</option>';
					}

			echo '			</select></div>';
		?>
		<div id="tosl">G&Egrave;NERE <select id="GENERE" name="GENERE">
		<?
		if (!isset($ID) or $GENERE < 1) {
			$valgen = 0;
			$rezagen = 'escull...';
		} else {
			$valgen = $GENERE;
			$query = "select GENERE from GENERES WHERE ID='$GENERE'";
			$result = mysqli_query($mysqlConn2, $query);
			$row= mysqli_fetch_array($result);
			$rezagen = $row[0];
		}
			?>
			<option value="<?=$valgen?>" selected><?=$rezagen?>
		<?
			$query = "select * from GENERES order by GENERE";
			$result = mysqli_query($mysqlConn2, $query);

				  while ($row = mysqli_fetch_array($result))
				{
					  echo '<option value="'.$row[0].'">'.$row[1].'</option>';
					}

			echo '			</select></div>';

			?>
			<div id="tos">
			M&Uacute;SICA <input name="MUSICA" size="30" value="<?=$MUSICA?>"><br></div>
			<div id="tos">FOTOGRAF&Iacute;A <input name="FOTOGRAFIA" size="30" value="<?echo $FOTOGRAFIA?>"></div>
			<div id="tos"><div id="tospar">SINOPSI</div><textarea name="SINOPSI" style="height: 72px; width: 360px;" value="<?$SINOPSI?>"><?=$SINOPSI?></textarea></div><BR>
			<div id="tos"><div id="tospar">OBSERVACIONS</div><textarea name="OBSERVACIONS" style="height: 72px; width: 360px;" value="<?$OBSERVACIONS?>"><?=$OBSERVACIONS?></textarea></div><BR>
			<div id="tos">VALORACI&Oacute;<select id="VAL" name="VAL">
			<?
			if (!isset($ID) or $VAL < 1) {
				$valval = 0;
				$rezaval = 'escull...';
			} else {
				$valval = $VAL;
				$query = "select VAL from VAL WHERE ID='$VAL'";
				$result = mysqli_query($mysqlConn2, $query);
				$row= mysqli_fetch_array($result);
				$rezaval = $row[0];
			}
				?>
				<option value="<?=$valval?>" selected><?=$rezaval?>
			<?
				$query = "select * from VAL";
				$result = mysqli_query($mysqlConn2, $query);

					  while ($row = mysqli_fetch_array($result))
					{
						  echo '<option value="'.$row[0].'">'.html_entity_decode($row[1])	.'</option>';
						}

				echo '			</select><br>';
			//	dsp_crypt(0,1);
			if (!isset($ID) or $STOCK == 'yes')
			{
			$stockcheck = 'checked';
			} else {
			$stockcheck = '';
			}
			?>
			VIST<b>x</b>LUCÍA &nbsp;<input name="STOCK" value="yes" type="checkbox"<?=$stockcheck?>> <!--ARXIU <input type="file" name="ARXIU" id="ARXIU" value="<?$ARXIU?>">--></div><br>
			<!--<div id="tos"> IMATGE <input type="file" name="IMATGE" id="IMATGE" value="<?$IMATGE?>"></div>-->
			<?
			if ($ID < 1){
				$valuebot = 'introduir';
			} else {
				$valuebot = 'editar';
				echo '<input type="hidden" name="ID" value="'.$ID.'"/>';
			}
			?>
			<div id="buttons"><input type="submit" id="submit" class="validate['submit']" name="submit" value="<?=$valuebot?>">
			<input type="reset" id="desfer" value=" desfer " /></div>
			</form>
	<?
	}
	?>
</body>
</html>
