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
			select Name,Rubric, BeginDate, DueDate, Class.Name className, Lab.Name labName, BeginDate, DueDate 
			from Lab
			inner join Grade on Lab.ID=Grade.LabID
			inner join SectionLab on Lab.ID=SectionLab.SectionID
			inner join Section on SectionLab.SectionID=Section.ID
			inner join Class on Section.ClassID=Class.ID
      where Student.isActive and Lab.IsActive and Class.IsActive and LabID=?");
      

			$stmt->execute([$_GET['LabID']]);
			$arr = $stmt->fetch(PDO::FETCH_NUM); //FETCH_NUM must be used with list
			if(!$arr) exit('no rows');
			list($Name,$ID, $BeginDate, $DueDate, $labName,  $RUBRIC, $Grade) = $arr;
			echo "<table><tr><th>Lab Name</th><th>Lab ID</th><th>Begin Date</th><th>Due Date</th><th>Lab Rubric</th></tr>";
			echo "
				<tr><td>".$Name." ".$LastName."</td>
				<td>".$ID."</td>
				<td>".$BeginDate."</td>
				<td>".$DueDate."</td>
        <td>".$RUBRIC."</td>
        <td>".$Grade."</td></tr>";
				
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