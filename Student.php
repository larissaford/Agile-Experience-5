<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student</title>
</head>

<body>
	<p>
	<?php
		$ID = $_GET["ID"];
		$FirstName   = $_GET["FirstName"];
		$LastName  = $_GET["LastName"];
		$isActive= $_GET["isActive"];
		
		if(IsTrue($isActive) && IsSet($FirstName) && IsSet($LastName) && strlen($ID)==5){
		
			print "$LastName, $FirstName<br  />";
			print "$ID";
		}else
			//TO-DO, link back to html: echo '<a href="http://mscs-php.uwstout.edu.html">Error: Return to your form to complete the data.</a>)';
	?>
	</p>
</body>
</html>