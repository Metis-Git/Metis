<?php

session_start();


if (!isset($_SESSION["cookies"]["allow_set_cookies"]) || $_SESSION["cookies"]["allow_set_cookies"] == false) {
	// Wenn keine Cookies gesetzt wurden, bzw. nicht zugestimmt wurde wird zu JavaScript-Meldung weitergeleitet.
	header("Location: ./../");
}
elseif(!isset($_SESSION["cookies"]["request_send"]) || $_SESSION["cookies"]["request_send"] == false) {
	$_SESSION["cookie_caller"] = "index/";
	$_SESSION["cookie_request_get"] = true;
	header("Location: ./../cookies.php");
}

include("header.php");

?>

<p>
	<p><b><u>Das ist tempor&auml;r:</u></b></p>
	<ul>
		<li><a href="./../registered/home/">Die Fake-Anmeldung bleibt!</a></li>
		<li>tempor&auml;re Anmeldung als:
			<ul>
				<li><a href="tmpSignIn/Jakob.php">Jakob (un = Jakob; pw = jakob)</a></li>
				<li><a href="tmpSignIn/Bruno.php">Bruno (un = Bruno; pw = bruno)</a></li>
				<li><a href="tmpSignIn/Karl.php">Karl (un = Karl; pw = karl)</a></li>
			</ul>
		</li>		
		<li><a href="./../tmpDelCookies.php">Cookies l&ouml;schen</a></li>
	</ul>
	<p>
		<h2>Zur Anmeldung:</h2>
		Zuerst Lokalserver (bei Xampp), und dann erst die Batch-Datei ("startBackend.bat") starten.
	</p>
</p>

<?php
include("footer.php");
?>