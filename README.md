# FrontEnd

Bei Ver�ffentlichung:
Den Ordner index/tmpSignIn l�schen, sowie alle links in der Datei index/index.php, zudem k�nnen alle Dateien, welche tmp in ihren Namen haben gel�scht werden.

# Sessions

$_SESSION["visual_mode"]:			(dark/bright)
$_SESSION["caller"]:				(der Pfad, indem nach aufruf von cookies.php geheadert wird)
$_SESSION["cookies.php_type"]:		(bei aufrufung von cookies.php, ist dies der typ von dem was gemacht werden soll)
$_SESSION["called"]:				(ob cookies.php schon aufgerufen wurde)
$_SESSION["login_failed"]:			(ist nur gesetzt, wenn der Loginvorgang fehlschlug)
$_SESSION["first"]:					(erste anmeldung)

$_SESSION["user"]:					(ist ein Arry, welches alle userdaten speichert, bis auf das Passwort des Users)