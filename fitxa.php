<?
	$query = "select * from CINEMA where ID='$IDP'";
	$resultant = mysqli_query($mysqlConn,$query);
	$title = "select TITOL from CINEMA where ID='$IDP'";
	$title = mysqli_query($mysqlConn,$title);
	$title = mysqli_fetch_array($title);

$rec = recordfiltre(false);
if ($_GET['img']) {
	img();
}

logo(stripslashes($title[TITOL]),$css='fitxa',$rec, false);

echo '<div id="cont"><div id="edit"><a href="intro.php?PID='.$IDP.'&rec='.recordfiltre(true).'">editar</a> <img src="img/pencil.png" width="18" height="18" style="vertical-align:middle;" alt="editar" /></div>';
	while ($row = mysqli_fetch_array($resultant))
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

	$VALt = "select VAL from VAL WHERE ID='$VAL'";
	$VALt = mysqli_query($mysqlConn, $VALt);
	$VALt = mysqli_fetch_array($VALt);
	$VAL = 11 - $VAL;
	if (preg_match('/(.*)\((.*)\)/', $TITOL)) {
		$TITOLor = preg_replace('/(.*)\((.*)\)/', '\2', $TITOL);
		$TITOL = preg_replace('/(.*)\((.*)\)/', '\1', $TITOL);
		echo '<div id="lini"><font class="titol">'.$TITOL.'</font><br>';
		if (!empty($TITOLor)) {
			echo '<p><div id="titolori">t&iacute;tol original <font class="titolori">'.$TITOLor.'</font></div>';
		}
		echo '<div id="valium"><b>'.$VAL.'</b> <font color="#CCC">[ </font><div id="val">'.$VALt[0].'</div><font color="#CCC"> ]</font></div></div><br>';
	} else {
		echo '<div id="lini"><font class="titol">'.$TITOL.'</font><br>';
		echo '<div id="valiumfix"><b>'.$VAL.'</b> <font color="#CCC">[ </font><div id="val">'.$VALt[0].'</div><font color="#CCC"> ]</font></div></div><br>';
	}
	if (!empty($DIRECCIO) and !preg_match('/,/', $DIRECCIO)) {
		$coindir = coincidencies($IDP, $DIRECCIO, true, true);
		echo 'direcci&oacute; <font id="actors"><a href="?director='.$DIRECCIO.'">'.$DIRECCIO.'</a>'.$coindir.'</font>';
	} elseif (preg_match('/,/', $DIRECCIO)) {
		$DIRECCIO = explode(',', $DIRECCIO);
		$DIRNUM = count($DIRECCIO);
		 echo 'direcci&oacute; ';
		while (list($key, $val) = each($DIRECCIO))
		{
			$coindir = coincidencies($IDP, $val, true, true);
			if (preg_match('/\((.*)\)/', $val)) {
				$detalls = preg_replace('/(.*)\((.*)\)/',' (<font class="cat">\2</font>)',$val);
				$val = preg_replace('/(.*)\((.*)\)/','\1',$val);
			}
			echo '<font id="actors"><a href="?director='.$val.'">'.$val.'</a>'.$coindir.$detalls.'</font>';
			unset($detalls);
		$i++;
			if ($DIRNUM > $i) {
			echo '<font id="coma">,</font>';
			}
		} // while
		unset($i);
	} else {
		echo 'direcci&oacute; <font class="titolblau">&mdash;</font>';
	}
		echo '<div id="img">';
		if ($thumbnum > 1) {
			echo '<a href="?img='.$imglink.recordfiltre(true).'">';
			$tanca = '</a>';
		}
		echo '<img src="'.$thumbnail.'" width="90" height="90" alt="STOCK" />'.$tanca;
	if ($thumbnum > 1) {
		if ($thumbnum > 3) {
			$plural = 's';
		}
		echo '<br><font style="font-size:12pt;font-weight:bold;color: #63819c;">'.($thumbnum-1).'</font> imatge'.$plural;
	} else {
		echo '<br><font style="font-size:12pt;font-weight:bold;color: #63819c;">sense</font> imatges';
	}

	echo'			</div>';
	echo '<p>any <font class="titolor">';

	echo any($ANY);

	echo '</font><br>';

	$NACIONALITAT = "select NACIONALITAT from NACIONALITAT WHERE ID='$NACIONALITAT'";
	$NACIONALITAT = mysqli_query($mysqlConn, $NACIONALITAT);
	$NACIONALITAT= mysqli_fetch_array($NACIONALITAT);
	echo 'nacionalitat <font class="titolor">'.$NACIONALITAT[0].'</font><br>';

	$GENERE = "select GENERE from GENERES WHERE ID='$GENERE'";
	$GENERE = mysqli_query($mysqlConn, $GENERE);
	$GENERE= mysqli_fetch_array($GENERE);
	echo 'g&egrave;nere <font class="titolor">'.$GENERE[0].'</font><br>';

	echo '<p>int&egrave;rprets ';
	if (!empty($INTERPRETS)) {
		$INTERPRETS = explode(',', $INTERPRETS);
		$INTNUM = count($INTERPRETS);

	while (list(, $val) = each($INTERPRETS))
	{
		$coin = coincidencies($IDP, $val, false, false, false, true);
		if ($INTNUM > $i+1) {
		$comes = '<font id="coma">,</font>';
		} else {
		$comes = '';
		}
		if (preg_match('/(.*)\((.*)\)/', $val)) {
			$personatge = preg_replace('/(.*)\((.*)\)/', '\1'.$coin.' <font class="titolcur">\2</font>', $val);
			$actor = preg_replace('/(.*)\((.*)\)/', '\1', $val);
			echo '<font id="actors"><a href="?actor='.$actor.'&rec='.recordfiltre(true).'">'.$personatge.'</a></font>'.$comes;
		} else {
			echo '<font id="actors"><a href="?actor='.$val.'&rec='.recordfiltre(true).'">'.$val.'</a>'.$coin.'</font>'.$comes;
		}
		$i++;
	} // while
	unset($i);
	} else { // no hi ha intèrprets
		echo '<font class="titolblau">&mdash;</font>';
	}

	echo '<p>m&uacute;sica ';
	if (!empty($MUSICA)) {
			if (preg_match('/,/', $MUSICA)) {
				$MUSICA = explode(',', $MUSICA);
				$musicnum = count($MUSICA);
				while (list($key, $val) = each($MUSICA)) {
					$ii++;
					destripmusic($IDP, $val, $ii, $musicnum);
					unset($conducted);
				} //while
			}  else { // if coma
			destripmusic($IDP, $MUSICA);
			}
	} else {
		echo '<font class="titolblau">&mdash;</font><br>';
	}

function destripfoto($IDP, $contingut, $num, $total) {

	if (preg_match('/,/', $contingut) and !preg_match('/\//', $contingut)) {
		if ($num > 0 and $num < $total+2) {
			 $coma = '<font id="coma">,</font> ';
		} else {
			$coma = '<br>';
		}
		$fotograf = preg_replace('/(.*),(.*)/','\1',$contingut);
		$cat = preg_replace('/(.*),(.*)/','<font class="cat">\2</font>',$contingut);
		$cat = preg_replace('/(.*)\.(.*)\.(.*)\./','<font class="cat">\1<font id="punt">.</font>\2<font id="punt">.</font>\3<font id="punt">.</font></font>',$cat);
		$coin = coincidencies($IDP, trim($fotograf), false, false, true, true);
		echo '<font id="actors"><a href="?fotografia='.$fotograf.'&rec='.recordfiltre(true).'">'.$fotograf.'</a>'.$coin.'<font id="coma">,</font> '.$cat.'</font>'.$coma;
	} else {
		if (preg_match('/\//', $contingut)) {
			$fotograf = preg_replace('/(.*),(.*)/','\1',$contingut);
			$cat = preg_replace('/(.*),(.*)\.(.*)\.(.*)\.\/(.*)\.(.*)\.(.*)\./','<font id="coma">,</font>
			<font class="cat">\2<font id="punt">.</font>\3<font id="punt">.</font>\4<font id="punt">.</font><font id="coma">,</font>
			<font class="cat">\5<font id="punt">.</font>\6<font id="punt">.</font>\7<font id="punt">.</font></font>',$contingut);
			$coma = '</font><br>';
		} else {
			$fotograf = $contingut;
			$coma = '<br>';
		}
		$coin = coincidencies($IDP, trim($fotograf), false, false, true, true);
		echo '<font id="actors"><a href="?fotografia='.$fotograf.'&rec='.recordfiltre(true).'">'.$fotograf.'</a>'.$coin.$cat.'</font>'.$coma;
	}
}

echo 'fotografia ';
	if (!empty($FOTOGRAFIA)) {
		if (preg_match('/(.*),(.*),/', $FOTOGRAFIA)) {
			if (preg_match('/(.*),(.*)\.,(.*),/', $FOTOGRAFIA)) {
				$fotograff = preg_replace('/(.*),(.*).,(.*),(.*)\./','\1,\2.-\3,\4.',$FOTOGRAFIA);
			} else {
				$fotograff = preg_replace('/(.*),(.*).,(.*)/','\1,\2.-\3',$FOTOGRAFIA);
			}

			$fotograff = explode('-', $fotograff);
			$fotnum = count($fotograff);
				while (list($key, $val) = each($fotograff)) {
					$iii++;
					destripfoto($IDP, $val, $iii, $fotonum);
					unset($conducted);
				} //while
		} elseif (preg_match('/(.*)\.(.*)\.(.*)\./', $FOTOGRAFIA)) {
			destripfoto($IDP, $FOTOGRAFIA);
		}else {
			$coin = coincidencies($IDP, trim($FOTOGRAFIA), false, false, true, true);
			echo '<font id="actors"><a href="?fotografia='.$FOTOGRAFIA.'&rec='.recordfiltre(true).'">'.$FOTOGRAFIA.'</a>'.$coin.'</font><br>';
		}
	} else {
		echo '<font class="titolblau">&mdash;</font><br>';
	}

	if (empty($SINOPSI)) {
		echo '<div id="sinopsi"><div id="legend">sinopsi<font color="#666">:</font></div>Per determinar...</div>';
	} else {
		echo '<div id="sinopsi"><div id="legend">sinopsi<font color="#666">:</font></div>'.nl2br($SINOPSI).'</div>';
	}

	if (empty($OBSERVACIONS)) {
		echo '<div id="obs"><div id="legend">observacions<font color="#666">:</font></div>Sense observacions...</div>';
	} else {
		echo '<div id="obs"><div id="legend">observacions<font color="#666">:</font></div>'.nl2br($OBSERVACIONS).'</div>';
	}
	?>
	<div id="linarx">
	dades de l'arxiu<br>
	<?
	if ($STOCK == 'yes') {
		echo '<div id="stock"><img src="img/stockyes.png" width="27" height="27" alt="en stock" style="vertical-align:bottom;" /> en stock</div>';
	} else {
		echo '<div id="stock"><img src="img/stockno.png" width="27" height="27" alt="no stock" style="vertical-align:bottom;"/> no est&agrave; en stock</div>';
	}

	echo '</div>';

	echo '</div>';

	}
	echo '</div>';
	tempstancar('fitxa');
	$ara = tempsara('fitxa');
	peu();
?>
