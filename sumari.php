<?

if ($_GET['actor']) {
	$actor = urldecode($_GET['actor']);
	$val = urldecode($_GET['actor']);
	$selop = "INTERPRETS";
	$opact = true;
	$detall = 'actuat a';
} elseif ($_GET['director']) {
	$actor = urldecode($_GET['director']);
	$val = urldecode($_GET['director']);
	$selop = "DIRECCIO";
	$detall = 'dirigit';
	$detall2 = '</font>';
	$opdir = true;
} elseif ($_GET['musica']) {
	$actor = urldecode($_GET['musica']);
	$val = urldecode($_GET['musica']);
	$selop = "MUSICA";
	$detall = 'composat per a';
	$detall2 = '</font>';
} elseif ($_GET['fotografia']) {
	$actor = urldecode($_GET['fotografia']);
	$val = urldecode($_GET['fotografia']);
	$selop = "FOTOGRAFIA";
	$detall = 'dirigit la fotografia a';
	$detall2 = '</font>';
}

$coinci = "select ID, TITOL, ANY, $selop from CINEMA order by ANY asc";
//$rec = recordfiltre(false);
if ($_GET['imgsumari']) {
	img(true);
}
logo($actor,$css='fitxa',$rec);

	$val = trim($val);
	$val = strtolower($val);

	$coinci = mysqli_query($mysqlConn, $coinci);
	while ($row = mysqli_fetch_array($coinci))
	{
		if ($opact == true)
		{
			$person[$i] = preg_replace('/(.*?)\((.*?)\)/','\1',$row['INTERPRETS']);
			$person[$i] = stripslashes($person[$i]);
			$person[$i] = trim($person[$i]);
			$person[$i] = explode(',', strtolower($person[$i]));
		} else {
			if (preg_match('/\((.*)\)/', $row[$selop])) {
				$row[$selop] = preg_replace('/(.*)\((.*)\)/','\1',$row[$selop]);
			}
			$person[$i] = trim($row[$selop]);
			$person[$i] = stripslashes($person[$i]);
			$person[$i] = split( '[,]', strtolower($person[$i]));
		}
		$titol[$i] = array($row['ID'] => $person[$i]);
		$i++;
	}



		while (list ($clau, $valor) = each($titol)) {

				while (list ($clau2, $valium) = each($valor)) {

				 	while (list (, $valium2) = each($valium)) {

					 $valium2 = trim($valium2);

						if ($valium2 == $val) {
							$peli = "select TITOL, ANY from CINEMA where ID=$clau2";
							$peli = mysqli_query($mysqlConn, $peli);
							$peli = mysqli_fetch_array($peli);
							$coins++;
								if ($_GET['actor']) {
//								$detall2 = '<font color="#DDD"><b>'.count($valium).'</b></font></font> actors';
								}
								if (preg_match('/(.*?)\((.*?)\)/', $peli[0])) {
									if (strlen($peli[0]) > 45) {
										$titolor = '<font class="titolor">...</font>';
									} else {
										$titolor = preg_replace('/(.*?)\((.*?)\)/', '<font class="titolor"> (</font><font class="titolcur">\2</font><font class="titolor">) </font>', stripslashes($peli[0]));
									}
									$TITOL = preg_replace('/(.*)\((.*)\)/', '\1', stripslashes($peli[0]));
								} else {
									$TITOL = stripslashes($peli[0]);
									$titolor = '';
								}

							$descrip[] = '<p><div id="sumari">
												<div id="sumaria"><font color="#63819c">(</font>'.$clau2.'<font color="#63819c">)</font></div>
												 <a href="?PID='.$clau2.'&rec='.recordfiltre(true).'">
												'.trim($TITOL).'</a> '.$titolor.' &nbsp;<font color="#63819c">[</font><font color="#999">'.any($peli[1]).'</font><font color="#63819c">]</font> '.$detall2.'</font></div>';
						}
					} // últim while
				} //segon while
		} //primer while

		$imgactor = trim($actor);
		$imgactor = str_replace(" ", "", $imgactor);

			if ($dh = opendir(IMGDIR)) {
				while (($file = readdir($dh)) !== false) {
					if (strstr($file, $imgactor)) {
						$numus++;
						list( $IID,$nextnum,$act ) = split( '[-]', $file);
						$act = str_replace('.png','',$act);
//						$act = '-'.$act;
						$imgnext[] = $file;
						if ($numus == 1) {
							$thumbnail = THUMBDIR.$IID.'-'.$nextnum.'-'.$act.'.png';
							$thumblink = $IID.'-'.$nextnum.'-'.$act;
							if (!file_exists($thumbnail)) {
							$useAutoThumbnails = true;
							$cacheThumbnails = true;
							$thumbnailtemp = IMGDIR.$IID.'-'.$nextnum.'-'.$act.'.png';
							$thumcrea = thumb($thumbnailtemp);
							}
						}
					}
				} //while
				closedir($dh);
			}
	$thumbnum =	count($imgnext);
	if ($thumbnum < 1) {
		$thumbnail	=	'img/img.png';
	}

	if ($coins > 0) {
		if ($coins > 1) {
			$pluri = 'les';
		} else {
			$pluri = 'la';
		}

	echo '<div id="protagonista">'.$actor.'<br>
				<font id="sinopsi" style="color:#999;"><b>></b></font><font id="subti">ha '.$detall.' <font id="sumari"><b>'.$coins.'</b></font> pel&middot;l&iacute;cu'.$pluri.'</font>
				</div>';

			echo '<div id="imgsum">';
			if ($thumbnum > 1) {
				echo '<a href="?imgsumari='.$thumblink.recordfiltre(true).'">';
				$tanca = '</a>';
			}
			echo '<img src="'.$thumbnail.'" width="90" height="90" alt="STOCK" />'.$tanca;
		if ($thumbnum > 0) {
			if ($thumbnum > 1) {
				$plural = 's';
			}
			echo '<br><font style="font-size:12pt;font-weight:bold;color: #63819c;">'.($thumbnum).'</font> imatge'.$plural;
		} else {
			echo '<br><font style="font-size:12pt;font-weight:bold;color: #63819c;">sense</font> imatges';
		}

		echo'			</div>';

		while (list (, $resultant) = each($descrip)) {
			echo $resultant;
		}
	} else {
		echo '<div id="resultat"><font color="#999">'.$actor.'</font> no ha donat resultats...</div>';
	}

	?>
	</div>
	<?
	tempstancar('sumari');
	$ara = tempsara('sumari');
	peu();
	?>
