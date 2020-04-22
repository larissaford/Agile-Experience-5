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
			echo '<a href="http://mscs-php.uwstout.edu/2019SU/cs/248/001/fords7798/Assignment5.html">Error: Return to your form to complete the data.</a>)';
	?>
	</p>
</body>
</html>