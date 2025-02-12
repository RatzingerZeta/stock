<?
error_reporting(1);
$db = 'STOCK';
$IDP = $_GET['PID'];
$COINLL = false;  // posar-lo a TRUE x mostrar coincidències d'actors al llistat (ralentitza com 7 cops més el procés)
const THUMBDIR	=	'img/magatzem/snifthumbs/';
const IMGDIR	=	'img/magatzem/';
const LIM = 18; // pel·lícules x pàgina


/**
 * imes - Determinarà els enllaços per la navegació d'imatge
 * @category Imatge
 * @author  comiendoentokyo <comiendoentokyo@gmail.com>
 */
function imes($menys=false, $inici=false, $final= false) {

	if ($_GET['imgsumari']) {
		$img = $_GET['imgsumari'];
		list( $IDsum,$numsum,$actorsum ) = split( '[-]', $_GET['imgsumari'] );
		$sumari = true;
	} else {
		$img = $_GET['img'];
	}

list( $IDP,$inum,$actor ) = split( '[-]', $img );

	if ($menys == true) {
		$inum =$inum-1;
	} else {
		$inum =$inum+1;
	}
	if ($inici == true) {
		$inum = 1;
	}
	if ($final > 1) {
		$inum = $final;
	}

	if (!file_exists(IMGDIR.$img.'png')) {
			$d = dir(IMGDIR);
			while (($file = $d -> read())) {
				list( $IDD,$numus,$actor ) = split( '[-]', $file );
				if (strstr($file, $IDP.'-'.$inum) && strlen($IDD) === strlen($IDP) && !$sumari) {
					list( ,$nextnum,$act ) = split( '[-]', $file);
					$act = preg_replace('/(.*)\.png/','-\1', $act);
					$imgnext = $IDP.'-'.$inum.$act;
				} elseif (strstr($file, $actorsum)) {

					list( $ID3,$nextnum,$act3 ) = split( '[-]', $file);

						$act3 = preg_replace('/(.*)\.png/','-\1', $actorsum);
						if ($ID3 == $IDsum && strlen($ID3) === strlen($IDsum) && !$final ) {
							$imgnext = $IDsum.'-'.$inum.'-'.$actorsum;
						} else {
							$imgnext = $ID3.'-'.$inum.'-'.$actorsum;
						}

				}
			} //while
		return $imgnext;
	}
}

/**
 * img - Crea la pàgina de navegació d'imatge
 * @category Imatge
 * @author  comiendoentokyo <comiendoentokyo@gmail.com>
 */
function img($sum = false) {

	if ($sum) {
		$imag = $_GET['imgsumari']	;
		$link = 'imgsumari';
	} else {
		$imag = $_GET['img']	;
		$link = 'img';
	}

list( $ID,$inum,$actor ) = split( '[-]', $imag );

if (!file_exists(IMGDIR.$imag.'png')) {
		$d = dir(IMGDIR);
		while (($file = $d -> read())) {
		list( $IDD,,$act ) = split( '[-]', $file );
			if (strstr($file, $ID.'-') && strlen($IDD) === strlen($ID) && !$sum) {
				$num++;
			} elseif ($sum && strstr($file, $actor)) {
				$num++;
			}
		} //while
}
	if ($num<1) {
		$num=1;
	}
$IDP = $ID;
list( ,$inum,$actor ) = split( '[-]', $imag);
if ($sum) {
	$fitxa = '?actor='.trim($actor);
} else {
	$fitxa = '?PID='.$IDP;
}
	if ($actor != '') {
		$actor = '-'.$actor;
	}
$imatge = IMGDIR.$IDP.'-'.$inum.$actor.'.png';

$countFiles = $num;
$key = $inum;
$dim = getimagesize($imatge);
$rec = recordfiltre(true);

logo($imatge,$css='fitxa',$rec, false, false,'','', $nologo=true);

echo	'<div id="full">';
echo '<map name="PrevNext">';
if ($key > 1 && $key < $countFiles)
{
	$coords = "0,0," . floor($dim[0] / 2) . "," . $dim[1];
	echo '<area id="acoor" shape="rect" coords="' . $coords . '" href="?'.$link.'='.imes(true).$rec.'" tabindex="2" title="previous">';
	$coords = ceil($dim[0] / 2) . ",0," . $dim[0] . "," . $dim[1];
	echo '<area id="acoor" shape="rect" coords="' . $coords . '" href="?'.$link.'='.imes().$rec.'" tabindex="1" title="next"></map>';
} elseif ($key == $countFiles && $num > 1) {
	$coords = "0,0," . floor($dim[0] / 2) . "," . $dim[1];
	echo '<area id="acoor" shape="rect" coords="' . $coords . '" href="?'.$link.'='.imes(true).$rec.'" tabindex="2" title="previous">';
	$coords = ceil($dim[0] / 2) . ",0," . $dim[0] . "," . $dim[1];
	echo '<area id="acoor" shape="rect" coords="' . $coords . '" href="?'.$link.'='.imes(false, true).$rec.'" tabindex="1" title="to the first"></map>';
} elseif ($key == 1 && $countFiles != 1) {
	$coords = "0,0," . floor($dim[0] / 2) . "," . $dim[1];
	echo '<area id="acoor" shape="rect" coords="' . $coords . '" href="?'.$link.'='.imes(false, true, $countFiles).$rec.'" tabindex="2" title="to the end">';
	$coords = ceil($dim[0] / 2) . ",0," . $dim[0] . "," . $dim[1];
	echo '<area shape="rect" coords="' . $coords . '" href="?'.$link.'='.imes().$rec.'" tabindex="1" title="next"></map>';
}  else{
	echo '';
}
echo '<div id="acoor"><img class="galleryView" usemap="#PrevNext" src="'.$imatge.'"></div>';

echo '<span class="clear" style="float:left;padding-top: 6px;color:#63819c;"><a href="'.$fitxa.$rec.'" tabindex="3">origen</a></span>';
echo '<div id="fullpos">';
if ($key > 1)
{
	echo '<span class="clear"><a style="vertical-align:bottom;" href="?'.$link.'='.imes(true).$rec.'" title="previous">< </a></span>';
}
echo '<font style="color:#CCC;">'.$inum. '</font> <span class="clear">/</span> <font style="color:#CCC;">'.$countFiles.'</font>';
if ($key < $countFiles)
{
echo '<span class="clear"><a style="vertical-align:bottom;" href="?'.$link.'='.imes().$rec.'" title="next"> ></a></span>';
}
echo	'</div></div></body></html>';
	die();
}

function filtre($condiciona, $selecciona) {
global $resultat, $num, $numtot, $off, $ord, $ori, $mysqlConn;

//$lim = $_GET['lim'];
$off = $_GET['off'];
$ord = $_GET['ord'];
$ori = $_GET['ori'];
$estoc = $_GET['estoc'];
$q = $_GET['q'];

	if ($selecciona == true) {
		$selec = "ID,TITOL,ANY,DIRECCIO,INTERPRETS,NACIONALITAT,GENERE,VAL,IDIOMA,DUALX,SUB,STOCK";
	} else {
		$selec = '*';
	}

	if (empty($off)) {
		$off = 0;
	}
	 if ($condiciona == true) {
		$cad = '?off='.$off;
	 }

//	if (empty($lim)) { $lim = 18;	} else { $cad .= '&lim='.$lim;}
	if (empty($ord)) { $ord = 'TITOL'; } else { $cad .= '&ord='.$ord;}
	if (empty($ori)) { $ori = 'asc'; } else { $cad .= '&ori='.$ori;	}
	if (!empty($q)) {
		$cad .= '&q='.$q;
	}
	if (!empty($estoc)) { $cad .= '&estoc='.$estoc; } else { $estoc = 'ambdues'; }

	if ($ord == 'TITOL') {
		$div = '<div id="seleccionat">alf</div>';
	} else {
		$div = '<div id="noseleccionat"><a href="'.$cad.'&ord=TITOL">alf</a></div>';
	}
	if ($ord == 'VAL') {
		$dival = '<div id="seleccionat">val</div>';
	} else {
		$dival = '<div id="noseleccionat"><a href="'.$cad.'&ord=VAL">val</a></div>';
	}
	if ($ord == 'ID') {
		$divid = '<div id="seleccionat">ult</div>';
	} else {
		$divid = '<div id="noseleccionat"><a href="'.$cad.'&ord=ID&ori=desc">ult</a></div>';
	}
	if ($ori == 'asc') {
		$divori = '<div id="seleccimg"><a href="'.$cad.'&ori=desc"><img src="img/asc.png" width="9" height="9" alt="asc" style="vertical-align: bottom;"/></a></div>';
	} else {
		$divori = '<div id="seleccimg"><a href="'.$cad.'&ori=asc"><img src="img/desc.png" width="9" height="9" alt="desc" style="vertical-align: text-top;"/></a></div>';
	}

	if ($condiciona == true) {
		if ($estoc != 'ambdues' && empty($q)) {
			if ($estoc == 'nostock') {
				$siono = 'no';
			} elseif ($estoc == 'stock') {
				$siono = 'yes';
			}
			$query = "select $selec from CINEMA where STOCK=\"$siono\" order by $ord $ori  limit $off , ".LIM;
		} else {
			if (!empty($q)) {
				$query = "select $selec from CINEMA where TITOL like '%$q%' order by $ord $ori limit $off , ".LIM;
			} else {
				$query = "select $selec from CINEMA order by $ord $ori limit $off , ".LIM;
			}
		}

		$resultat = mysqli_query($mysqlConn, $query);
		$num = mysqli_num_rows($resultat);
		echo '<div id="logot"> '.$num.'<font color="#999"> / </font>'.$numtot.' pel&middot;l&iacute;cules indexades</div>
		<div id="filtre">
		 <form method="get" action="'.recordfiltre(false).'">
		 			<input id="q" tabindex="1" name="q" size="45" onsubmit="this.form.submit();" value="'.$q.'" />
					<select id="estoc" name="estoc" onchange="this.form.submit();">
					<option value="0" selected>'.$estoc.'</option>
					<option value="ambdues">ambdues</option>
					<option value="stock">en stock</option>
					<option value="nostock">no stock</option></select>
					<noscript><input type="submit" value="anem" /></noscript>
					'.$div.'
					'.$dival.'
					'.$divid.'
					'.$divori.'
					</form>
					</div>';
	}	else {
		return $cad;
	}
}

function recordfiltre($bule) {
//$lim = $_GET['lim'];
$off = $_GET['o	ff'];
$ord = $_GET['ord'];
$ori = $_GET['ori'];
$estoc = $_GET['estoc'];
$q = $_GET['q'];

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

//	if (empty($lim)) { $lim = 18;	} else { $cad .= '&lim='.$lim; }
	if (empty($ord)) { $ord = 'TITOL'; } else { $cad .= '&ord='.$ord; }
	if (empty($ori)) { $ori = 'asc'; } else { $cad .= '&ori='.$ori;	}
	if (!empty($q)) {
		$cad .= '&q='.$q;
	}
	if (!empty($estoc)) {
		$cad .= '&estoc='.$estoc;
	}

return $cad;
}

/**
 * coincidencies -Busca coincidències d'actors, director (fotografia, música)
 * @category Interiorisme de consulta
 * @author  comiendoentokyo <comiendoentokyo@gmail.com>
 */
function coincidencies($ID, $actor=false, $director=false, $musica=false, $fotografia=false, $blau) {
//per si de cas
global $mysqlConn;
if ($blau == true) {
	$fcol = '<font id="par"> (</font>';
	$fcolfin = '<font id="par">)</font></font>';
	$fid = '<font id="petit">';
} else {
	$fcol = '<font id="par2"> (</font>';
	$fcolfin = '<font id="par2">)</font></font>';
	$fid = '<font id="petittronja">';
}

	if ($actor != ' ' && !empty($actor))
	{
	if ($director == true) {
		$coinci = "select DIRECCIO from CINEMA where ID!=$ID";
	}  elseif ($musica == true) {
		$coinci = "select MUSICA from CINEMA where ID!=$ID";
	} elseif ($fotografia == true) {
		$coinci = "select FOTOGRAFIA from CINEMA where ID!=$ID";
	} else {
		$coinci = "select INTERPRETS from CINEMA where ID!=$ID";
	}

	$coinci = mysqli_query($mysqlConn, $coinci);

			$actor = preg_replace('/(.*?)\((.*?)\)/','\1',strtolower($actor));
			$actor = trim($actor);

//			while ($row = mysqli_fetch_array($coinci))
			while ($row = mysqli_fetch_array($coinci))
			{
				$person[$i] = preg_replace('/(.*?)\((.*?)\)/','\1',$row[0]);
				$person[$i] = trim($person[$i]);
				$inter[$i] = explode(',', strtolower($person[$i]));

				while (list (, $valor[$i]) = each($inter[$i])) {
					$internou[$ii] = trim($valor[$i]);
					$ii++;
				}
				$i++;
			} // while per crear l'array de coincidències

	if (in_array($actor,$internou)) {
				$coins=0;
				while (list (, $val) = each($internou)) {
					if ($val == $actor) {
						$coins++;
					}
				} //while per trobar la coincidència
	}

		if ($coins > 0) {
			$coinreturn = $fid.$fcol.($coins+1).$fcolfin;
		} else {
			$coinreturn = '';
		}

	return $coinreturn.'';

	} else { //s'ha enviat en blanc
		return '';
	}
}

function readPicture($in){
	$fh = fopen($in, 'rb');
	$content = fread($fh, filesize($in));
	return $content;
	fclose($in);
}

function nav($off, $numtot, $limit, $pos) {

$cad = filtre(false, false);
	if ($numtot >  $limit) {
		if ($pos == 1) {
			$pos = 'nav';
		} else {
			$pos = 'navf';
		}
		echo '<div id="'.$pos.'">';
			$nav_bar = ' ';
			$pages_bar = ' ';
			if ($off > 0)
			    {
			    $previous_link = '?off='.($off-$limit).$cad;
			    $nav_bar .= '< <font id="navi"><a href="'.$previous_link.'">previ</a></font> ';
			    }
			$tot_pages = ceil($numtot / $limit);
			$actual_page = $off/$limit + 1;
			$page_inf = max(1,$actual_page - 4);
			$page_sup = min($tot_pages,max($actual_page+4,9));

			for ($page = $page_inf; $page <= $page_sup; $page++)
			  {
			  if ($page == $actual_page)
			       {
			       $nav_bar .= ' [<font id="navi">'.$page.'</font>] ';
			       $pages_bar .= ' '.$page.' ';
			       } else {
			       	$nav_bar .= '<a href="?off='.(($page-1)*$limit).$cad.'" > '.$page.'</a> ';
			       	$pages_bar .= '<a href="?off='.(($page-1)*$limit).$cad.'"> '.$page.'</a> ';
			       }
			}

			if ($off+$limit-1 < $numtot) {
			    $next_link = '?off='.($off+$limit).$cad;
			    $nav_bar .= ' <font id="navi"><a href="'.$next_link.'" > seg&uuml;ent</a></font> >';
			    }

		echo(''.$nav_bar);
		echo '</div>';
	} else {
		$query = $_GET['q'];
		if ($numtot > 0) {
			if ($numtot > 1) {
				$plural = 's';
			} else {
				$plural = '';
			}
			echo '<div id="navno"><font color="#CCC">'.$query.'</font> ha donat <font color="#CCC">'.$numtot.'</font> resultat'.$plural.'</div>';
		} else {
			echo '<div id="navno"><font color="#CCC">'.$query.'</font> no ha donat cap resultat</div>';
		}
	}
}

function thumb($thumbpel){
GLOBAL $thumbnailHeight, $cacheThumbnails;
$thumbnailCacheSubdir = "snifthumbs"; //posar-hi un punt al davant si el vull amagar...
$thumbnailsize = 180;
$file = urldecode($thumbpel);
$thumbFile = THUMBDIR.basename($file);
	if ($cacheThumbnails) {
		if (file_exists(THUMBDIR)) {
			if (!is_dir(THUMBDIR)) {
				$cacheThumbnails = false;
			}
		} else {
			if (@mkdir(THUMBDIR)) {
				chmod(THUMBDIR, 0777);
			} else {
				$cacheThumbnails = false;
			}
		}

if (!file_exists($thumbFile)) {
	$src=readPicture($file);
	$dim = getimagesize($file);
	$thumb = imagecreatetruecolor($thumbnailsize, $thumbnailsize);
	$srcWidth = $dim[0];
	$srcHeight = $dim[1];
	$prop = $srcWidth / $srcHeight;
	$thumbWidth = 	$thumbnailsize;
	$thumbHeight = 	$thumbnailsize / $prop;

	$src = imagecreatefromstring($src);

	if ($srcHeight<=$thumbnailsize || $srcWidth<=$thumbnailsize) {
		//readfile($file);
	} else {
		if ($thumbHeight < $thumbnailsize) {
			$thumbHeight = $thumbnailsize;
			$thumbWidth = $thumbnailsize * $prop;
		}
		imagecopyresized($thumb, $src, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);

			if ($cacheThumbnails) {
			$exif = exif_read_data($file);
			$orientation = intval(@$exif['Orientation']);
			switch ($orientation) {
				case 3:
					$thumb = imagerotate($thumb, 180, 0);
					break;
				case 6:
					$thumb = imagerotate($thumb, 270, 0);
					break;
				case 8:
					$thumb = imagerotate($thumb, 90, 0);
					break;
				default:
					break;
			}
			imagejpeg($thumb, $thumbFile);
			chmod($thumbFile, 0777);
		//	readfile($thumbFile);
		} else {
		$ext = strtolower(substr(strrchr($thumb, "."), 1));
			if ($ext == 'png'){
				imagepng($thumb);
			} else {
				imagejpeg($thumb);
			}
		}
		        imagedestroy($src);
                imagedestroy($thumb);
	}
}
return $thumbFile;
die();
}
}

function logo($titol, $css, $rec, $cont=true, $enlla=true, $num, $numtot, $nologo=false) {
$link = '';
$linktanc = '';
if ($nologo == false) {
	if ($enlla ==true) {
		$link = '<a href="http://192.168.1.18/stock/'.$rec.'" onmouseover="sublogoh.style.display=\'inline-table\'" onmouseout="sublogoh.style.display=\'none\'">';
		$linktanc = '</a>';
		$histmen = '<font color="#CCC">anar</font>enrera';
	} else {
		$link = '<a href="http://192.168.1.18/stock/" onmouseover="sublogoh.style.display=\'inline-table\'" onmouseout="sublogoh.style.display=\'none\'">';
		$sumari = '<div id="edit"><a href="intro.php?rec='.$rec.'" tabindex="2">introduir</a> <img src="img/pencil.png" width="18" height="18" style="vertical-align:middle;" alt="introduir" />';
		$histmen = '<font color="#CCC">sense</font>filtre';
	}
	echo'
	<html>
	<head>
	<title>'.$titol.' > STOCK</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="css/'.$css.'.css">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	</head>
	<body>
		<div id="logo">
			'.$link.'
			<img src="img/stock.png" width="27" height="27" style="vertical-align: text-bottom;" alt="STOCK" /> <div id="logotext">ST<font color="#63819c">O</font>CK<br>
			<div id="sublogoh">'.$histmen.'</div>
			<div id="sublogo"> du <font style="color:#63819c;font-weight: bold;">CIN&Eacute;MA</font></div>
			</div>'.$linktanc.'
			'.$sumari.'
		</div>';
		if ($cont == true){
		echo '<div id="cont">';
		}
	} else {
echo'
<html>
<head>
<title>'.$titol.' > STOCK</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css/'.$css.'.css">
</head>
<body>';
	}
}

function any($ANY) {
	if ($ANY == 200) {
		return 'aprox. 1&ordf; d&egrave;cada del 2000';
	} elseif ($ANY < 200 && $ANY != 0) {
		return 'aprox. '.$ANY.'0';
	} elseif ($ANY == 0) {
		return '...';
	} else {
		return $ANY;
	}
}

function destripmusic($IDP, $contingut, $num, $total) {
	if (preg_match('/\((.*)\)/', $contingut)) {
		$conducted = preg_replace('/(.*)\((.*)\)/',' (<font class="cat">\2</font>)',$contingut);
		$music = preg_replace('/(.*)\((.*)\)/','\1',$contingut);
		$music = trim($music);
		} else {
			$music = trim($contingut);
		}
		if ($num < $total) {
			 $coma = '<font id="coma">,</font> ';
		} else {
			$coma = '<br>';
		}
	$coin = coincidencies($IDP, $music, false, true, false, true);
	echo '<font id="actors"><a href="?musica='.$music.'&rec='.recordfiltre(true).'">'.$music.'</a>'.$coin.$conducted.'</font>'.$coma;
}

function tempsobrir($nom = 'defecte') {
	global $tempsapertura;
	$tempsapertura[$nom] = explode(' ', microtime());
}

function tempstancar($nom = 'defecte') {
	global $tempstancament;
	$tempstancament[$nom] = explode(' ', microtime());
}

function tempsara($nom = 'defecte') {
	global $tempsapertura, $tempstancament;
	if (!isset($tempsapertura[$nom])) {
		return 0;
	}
	if (!isset($tempstancament[$nom])) {
		$temps_stop = explode(' ', microtime());
	} else {
		$temps_stop = $tempstancament[$nom];
	}
	$ara = round($temps_stop[1] - $tempsapertura[$nom][1],3)*1000;
	$ara += round($temps_stop[0] - $tempsapertura[$nom][0],3)*1000;
	return $ara;
}

function peu() {
global $ara;
echo '<div class="copyright"><p> &copy; 2019 <a class="copyright" href="http://192.168.1.18">menjantat&ograve;quio</a> [<font color="#666">ST</font>O<font color="#666">CK</font>]<br>';
	echo $ara.'&nbsp;<font color="#666" style="font-weight:normal;">ms</font></div>';
	echo '</body>
	</html>';
}

//orientat a objectes
//$conn = new mysqli("localhost", "root", "Lleesdlm", $db);
//$conn->set_charset("utf8");

//$conn = mysqli_connect("localhost", "root", "Lleesdlm", $db);

//procedural
$mysqlConn = mysqli_connect('localhost', 'root', 'Lleesdlm', $db);
mysqli_set_charset($mysqlConn, "utf8");

//mysql_connect(':/Applications/MAMP/tmp/mysql/mysql.sock','root','Lleesdlm') or die ("impossible la connexió amb el servidor $host");
//mysql_select_db($db) or die (erroni("la taula $db no existeix"));

if ($_GET['PID'] || $_GET['img']) {
tempsobrir('fitxa');
$useAutoThumbnails = true;
$cacheThumbnails = true;

			$d = dir(IMGDIR);
			$thumbnum = 1;
			while ($file = $d -> read()) {
			list( $IDD,$numus,$act ) = split( '[-]', $file );
				if (strstr($file, $IDP.'-') && strlen($IDD) === strlen($IDP)) {
					list( $ID,$inum,$actor ) = split( '[-]', $file );
					if (!empty($actor)) {
						$actor = str_replace('.png','',$actor);
						$actor = '-'.$actor;
					}
					$thumbnailtemp	=	IMGDIR.$IDP.'-'.$thumbnum.$actor.'.png';
					if ($thumbnum == 1) {
						$thumbnail	=	THUMBDIR.$IDP.'-'.$thumbnum.$actor.'.png';
						$imglink = $IDP.'-'.$thumbnum.$actor;
					}
					$thumbnum++;
					$thumcrea = thumb($thumbnailtemp);
				}
			} // while


	if ($thumbnum == 1 && !file_exists($thumbnail)) {
		$thumbnail	=	'img/img.png';
	}

	include('fitxa.php');

} elseif ($_GET['actor'] || $_GET['director'] || $_GET['musica'] || $_GET['fotografia'] || $_GET['imgsumari']) {
	tempsobrir('sumari');
	include('sumari.php');

} else {
	tempsobrir('llista');
	include('llista.php');
}
?>
