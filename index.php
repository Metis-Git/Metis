<html>
    <head>
        <link rel="icon" href="faviconMetis.ico" type="image/x-icon" />
        <title>
            Metis
        </title>
        <?php
            //Erkl�rung siehe index/header.php
            if (!isset($_COOKIE["cookie"])) {
				echo
				'
				<script type="text/javascript">

				var cookie = confirm("Diese Web-Site verwendet Cookies.\nBitte stimmen Sie zu, um unsere Web-Site zu verwenden.\n"+
				"Diese Cookies verbleiben bis zur n�chsten L�schung Ihrer Browserdaten auf Ihren Computer.");

				if (cookie == false) {
					history.back();
				}
                else {
                    document.cookie = "cookie=true"
                    window.location = "index";
                }

				</script>';
			}
            else {
                header('location: index');
            }
            
        ?>

    </head>
    <body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #c2f9ff;">
        <p>
            
        </p>
    </body>
</html>