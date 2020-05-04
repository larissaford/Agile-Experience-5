<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lab</title>
<!--
<link href="*.css" type="text/css" rel="stylesheet">
-->
</head>

<body>
<p>
	<?php
		
		//display labs

		$servername = "localhost:3306";
		$username = "g5AppUser";
		$password = "aug5";
		$dbname = "G5AgileExperience";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("
			select ID, Name, BeginDate, DueDate, Rubric
			from Lab
      where Lab.IsActive");
      

			$stmt->execute([$_GET['LabID']]);
			$arr = $stmt->fetch(PDO::FETCH_NUM); //FETCH_NUM must be used with list
			if(!$arr) exit('no rows');
			list($ID,$Name, $BeginDate, $DueDate, $Rubric) = $arr;
			echo "<table><tr><th>Lab ID</th><th>Lab Name</th><th>Begin Date</th><th>Due Date</th><th>Lab Rubric</th></tr>";
			echo "
				<tr>
        <td>".$ID."</td>
        <td>".$Name."</td>
				<td>".$BeginDate."</td>
				<td>".$DueDate."</td>
        <td>".$Rubric."</td></tr>";
				
				/* outline for inserting links:
				<td><a href='Student.php?movie=<?php echo $StudentID ?>'>".$FirstName." ".$LastName."</a></td>
				*/

			echo "</table>";
			$stmt = null;
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
		echo "</table>";
	?>
	</p>
</body>
</html>