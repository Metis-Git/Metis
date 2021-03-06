<?php
	// TODO username bei Cookies verwenden
	global $position;
	$position = 1;
	$time = time() + (3600*24*360);	// Die Cookies bleiben ein Jahr gespeichert.
	include("./../header.php");
	$id = $_SESSION["user"]["id"];
	
	if(!isset($_COOKIE["nSubjekts".$id])) {	// Anzahl der F�cher wird auf 0 gesetzt.
		setcookie("nSubjekts".$id, 0, $time);
		header("location: ./../grades/");	// Dadurch wird die URL zur�ckgesetzt.
	}

	$remove = null;

	if(isset($_SESSION["must_reload"])) {	// Diese Neuladem�glichkeit erm�glicht die Verwendung von neugesetzten Cookies.
		unset($_SESSION["must_reload"]);
		header("location:".$_SERVER['REQUEST_URI']);
	}

	if(isset($_SESSION["subj"]) && isset($_SESSION["average".$_SESSION["subj"]])) {	// Empf�ngt die Daten von der Berechnung des Durchschnittes.
		$grades = array();
		for($i = 0; $i < $_SESSION["average".$_SESSION["subj"]]["n"]; $i++) {
			array_push($grades, $_SESSION["average".$_SESSION["subj"]][$i]);
		}
		setcookie("grades".$_SESSION["subj"].$id, serialize($grades), $time);
		setcookie("average".$_SESSION["subj"].$id, $_SESSION["average".$_SESSION["subj"]]["num"], $time);
		unset($_SESSION["average".$_SESSION["subj"]]);
		unset($_SESSION["subj"]);
		unset($_SESSION["subjekt"]);
		$_SESSION["must_reload"] = true;
		header("location: ./../grades/");
	}

	for($i = 0; $i < $_COOKIE["nSubjekts".$id]; $i++) {	// �berpruft, ob ein Fach entfernt werden soll.
		if(isset($_GET["remove".$i])) {
			$remove = $i+1;
		}
	}

	for($i = 0; $i < $_COOKIE["nSubjekts".$id]; $i++) {	// �berpruft, ob der Durchschnitt berechnet werden soll.
		if(isset($_GET["subj".$i])) {
			$_SESSION["subjekt"] = $_COOKIE["nSubj".$i.$id];
			$_SESSION["subj"] = $i;
			$header = "location: calc.php?";
			$array = array();
			echo $_COOKIE["grades".$i.$id];
			if(isset($_COOKIE["grades".$i.$id]) && $_COOKIE["grades".$i.$id] != 0 &&  $_COOKIE["grades".$i.$id] != null) {
				$array = unserialize($_COOKIE["grades".$i.$id]);
				for($j = 0; $j < count($array); $j++) {
					$header .= $j . "=" . $array[$j] . "&";
				}
				$_SESSION["num"] = count($array);
				header($header);
			}
			else {
				header("location: calc.php");
			}
		}
	}

	if(isset($_GET["newSubjekt"]) && $_GET["newSubjekt"] != null && isset($_GET["addSubjekt"])) {	// F�gt neues Fach hinzu.
		setcookie("nSubj".$_COOKIE["nSubjekts".$id].$id, $_GET["newSubjekt"], $time);
		setcookie("average".$_COOKIE["nSubjekts".$id].$id, 0.0, $time);
		setcookie("grades".$_COOKIE["nSubjekts".$id].$id, 0.0, $time);
		setcookie("nSubjekts".$id, $_COOKIE["nSubjekts".$id]+1, $time);
		$_SESSION["must_reload"] = true;
		header("location: ./../grades/");	// Dadurch wird die URL zur�ckgesetzt.
	}
	elseif(isset($_GET["reset"])) {
		for($i = 0; $i < $_COOKIE["nSubjekts".$id]; $i++) {	// Die Cookies werden zur�ckgesetzt.
			if(isset($_COOKIE["nSubj".$i.$id]))
				setcookie("nSubj".$i.$id, $_COOKIE["nSubj".$i.$id], 1);
		}
		setcookie("nSubjekts".$id, 0, $time);
		header("location: ./../grades/");	// Dadurch wird die URL zur�ckgesetzt.
	}
	elseif($remove != null) {
		$remove--;
		for($i = $remove; $i < ($_COOKIE["nSubjekts".$id])-1; $i++) {
			if(isset($_COOKIE["nSubj".($i+1).$id])) {
				setcookie("nSubj".$i.$id, $_COOKIE["nSubj".($i+1).$id], $time);
				setcookie("average".$i.$id, $_COOKIE["average".($i+1).$id], $time);	// �bernimmt das Array
				setcookie("grades".$i.$id, $_COOKIE["grades".($i+1).$id], $time);
			}
		}
		setcookie("nSubj".$_COOKIE["nSubjekts".$id].$id, false, 1);	// Das Cookie wird entfernt
		setcookie("nSubjekts".$id, ($_COOKIE["nSubjekts".$id])-1, $time);
		$_SESSION["must_reload"] = true;
		header("location: ./../grades/");	// Dadurch wird die URL zur�ckgesetzt
	}

?>

<div>
	<form action="index.php" methode="get">
		<table border="<?php echo(($_COOKIE["nSubjekts".$id] != 0)?"1":"0");?>" width="40%" cellspacing="0" bordercolor="#777760">
			<tr><?php
				if($_COOKIE["nSubjekts".$id] != 0) {
					echo "\n";
					echo "\t\t\t\t<th width=\"7%\"></th>\n";
					echo "\t\t\t\t<th align=\"left\" width=\"50%\">Fach</th>\n";
					echo "\t\t\t\t<th align=\"center\" width=\"18%\">&Oslash;<th>\n";
					//echo "\t\t\t\t<th width=\"25%\"></th>\n";
				}
				else {
					echo "\t\t\t\t<th>F&auml;cher</th><th></th>\n";
				}

				?>
			</tr><?php echo "\n";
			for($i = 0; $i < $_COOKIE["nSubjekts".$id]; $i++) {	// Gibt die Buttons usw. aus.
				echo "\t\t\t<tr>\n";
				echo "\t\t\t\t<td align=\"center\" width=\"7%\"><input style=\"width:100%;\" type=\"submit\" name=\"remove"
				. $i . "\" value=\"&minus;\"</td>\n";
				echo "\t\t\t\t<td align=\"left\" width=\"50%\">" . $_COOKIE["nSubj".$i.$id] . ": </td>\n";
				echo "\t\t\t\t<td align=\"center\" width=\"18%\">" . number_format($_COOKIE["average".$i.$id], 2, ",", ".") . "</td>\n";
				echo "\t\t\t\t<td align=\"center\" width=\"25%\"><input style=\"width:100%;\" type=\"submit\" name=\"subj"
				. $i . "\" value=\"&Oslash berechnen\" width=\"100%\" /></td>\n";
				echo "\t\t\t</tr>\n";
			}
			?>
		</table>
		<table border="0" width="40%" cellspacing="0">
			<tr>
				<th width="7%"></th>
				<th width="50%"><input type="text" name="newSubjekt" placeholder="Fachname/-bezeichnung" style="width:100%;" /></th>
				<th width="18%"><input type="submit" name="addSubjekt" value="Fach hinzuf&uuml;gen" /></th>
				<th width="25%"><input type="submit" name="reset" value="zur&uuml;cksetzten" /></th>
			</tr>
		</table>
	</form>
</div>

<?php
	include("./../footer.php");
?>