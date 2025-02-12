<?php
if (!empty($estoc) && $estoc != 'ambdues' && (!empty($q) || $q != ' ')) {
	if ($estoc == 'nostock') {
		$noosi = 'no';
	} elseif ($estoc == 'stock') {
		$noosi = 'yes';
	}
	$numtotQ = "select count(ID) from CINEMA where STOCK=\"$noosi\"";
} else {
	if (!empty($q) or $q != ' ') {
		$numtotQ = "select count(ID) from CINEMA where TITOL like '%$q%'";
	} else {
		$numtotQ = "select count(ID) from CINEMA";
	}
}
$numtot = mysqli_query($mysqlConn, $numtotQ);
list($numtot) = mysqli_fetch_array($numtot);

$rec = recordfiltre(false);
$rec2 = recordfiltre(true);
logo($titol='', $css='llista',$rec,$cont=false, $enlla=false, $num, $numtot);
filtre(true,true);

nav($off, $numtot, LIM, 1);
echo '<div id="cont">';

	if ($numtot > 0 || $q != ' ') {
			while ($row = mysqli_fetch_array($resultat, MYSQL_ASSOC))
				{
					$IDP = $row[ID];
					$TITOL = stripslashes($row[TITOL]);
					$ANY = $row[ANY];
					$DIRECCIO = stripslashes($row[DIRECCIO]);
					$INTERPRETS = stripslashes($row[INTERPRETS]);
					$NACIONALITAT = $row[NACIONALITAT];
					$GENERE = $row[GENERE];
			//	$SINOPSI = stripslashes($row[SINOPSI]);
			//	$OBSERVACIONS = stripslashes($row[OBSERVACIONS]);
					$VAL = $row[VAL];
					$IDIOMA = $row[IDIOMA];
					$DUAL = $row[DUALX];
					$SUB = $row[SUB];
			//	$MUSICA = stripslashes($row[MUSICA]);
			//	$FOTOGRAFIA = stripslashes($row[FOTOGRAFIA]);
				$STOCK = $row[STOCK];
				$ipel++;
					if (strlen($TITOL) > 66) {
						$TITOL = preg_replace('/(.*)\((.*)\)/', '\1<font class="titolor">...</font>', $TITOL);
					} else {
						$TITOL = preg_replace('/(.*)\((.*)\)/', '\1 <font class="titolor"> (</font><font class="titolcur">\2</font><font class="titolor">) </font>', $TITOL);
					}
			if ($STOCK == 'yes') {
			 $STOCK = '<img src="img/stockyes.png" width="14" height="14" style="vertical-align:top; padding-right:3px;" alt="en stock"/>';
			} else {
			 $STOCK = '<img src="img/stockno.png" width="14" height="14" style="vertical-align:top; padding-right:3px;" alt="en stock"/>';
			}

			if ($ipel < LIM && $off < (LIM*ceil($numtot/LIM)-LIM)) { // per acabar el llistat sense línea abaix
				echo '<div id="lini">';
			} else {
				if ($ipel < $numtot-(LIM*ceil($numtot/LIM)-LIM)) {
					echo '<div id="lini">';
				} else {
				echo '<div id="linif">';
				}
			}

			echo $STOCK.'<a href="?PID='.$IDP.'&rec='.recordfiltre(true).'" tabindex="'.($ipel+1).'"><font class="titol">'.$TITOL.'</font></a>';
			//echo ' <font color="#63819c">&mdash;</font>';
			echo '<font class="titolor" style="color:#63819c;"> [</font><font class="data">';
				echo any($ANY);
				echo '</font><font class="titolor" style="color:#63819c;">]</font>';

					if (!empty($DIRECCIO) and !preg_match('/,/', $DIRECCIO)) {
						$coindir = coincidencies($IDP, $DIRECCIO, true, false, false, true);
						echo ' <font class="titolor"><a href="?director='.$DIRECCIO.'">'.$DIRECCIO.'</a>'.$coindir.'</font> ';
		//				unset($coindir);
					} elseif (preg_match('/,/', $DIRECCIO)) {
						$DIRECCIO = explode(',', $DIRECCIO);
						$DIRNUM = count($DIRECCIO);
						while (list($key, $val) = each($DIRECCIO))
						{
						$coindir = coincidencies($IDP, $val, true, false,false,true);
							if (preg_match('/\((.*)\)/', $val)) {
								$val = preg_replace('/(.*)\((.*)\)/','\1',$val);
							}
							echo ' <font class="titolor"><a href="?director='.$val.'">'.$val.'</a>'.$coindir.'</font>';
						$i++;
							if ($DIRNUM > $i) {
							echo '<font id="coma">,</font>';
							} else {
							echo ' ';
							}
							unset($coindir);
						} // while
					}
					unset($coindir);
				if (!empty($INTERPRETS)){
					$INTERPRETS = explode(',', $INTERPRETS);
					$intnum = count($INTERPRETS);
					while (list($key, $val) = each($INTERPRETS))
					{
					if ($inum < 3) {
						if ($COINLL) {
							$coin = coincidencies($IDP, $val, false, false, false, false);
						}
					$personatge = preg_replace('/(.*)\((.*)\)/', '\1', $val);
					echo '<font class="blauet"><a href="?actor='.trim($personatge).$rec2.'">'.trim($personatge).$coin.'</font>';
						if (($intnum > 2 and $inum <= 1) or ($intnum > 1 and $inum < 1)) {
							echo '<font color="#CCC"> | </font>';
						} elseif (($inum == 1 and $intnum <= 2) or ($inum < 1 and $intnum == 1)) {
							echo '';
						} else {
							echo '<font color="#CCC">...</font>';
						}
					}
					$inum++;
					} //while interprets
					unset($inum);
					unset($intnum);
					unset($coin);
					unset($INTERPRETS);
					unset($personatge);
				}

			echo '</div><br>';
			echo "\n";
			} //while
		} else {
				echo	'<div id="nores">no hi ha res a mostrar buscant <font color="#CCC" style="font-weight: bold;">'.$q.'</font>, ho sento<font color="#CCC" style="font-weight: bold;">...</font></div>';
		}
	echo '</div>';
		if ($numtot > LIM) {
			nav($off, $numtot, LIM, 0);
		}
	tempstancar('llista');
	$ara = tempsara('llista');
	peu();
?>
