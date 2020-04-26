<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Results</title>
    <link rel="stylesheet" type="text/css" href="Agile-Experience-5.css" />
  </head>

<body>
<?php

		$servername = "localhost:3306";
		$username = "g5AppUser";
		$password = "aug5";
		$dbname = "G5AgileExperience";

		//TO-DO: make it more responsive to the search page
		$filter = $_GET["filter"]; //needs to be the column name i.e. "StudentID" or "Lab.Name"
		$searchBy = $_GET["this"]; //Strings need to be surrounded with single quotes i.e. lab name 'Vidoo'

		//TEST
		$filter = "StudentID";
		$searchBy = 3; //strings need 

	

		//click on lab: lab results page (lab.php) brought up
		//click on student: student results page (Student.php) brought up

		try {



			//search by Labname, section number, className, studentFirstName, student LastName, 
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$arr = [];
			$where="";
			if(!empty($searchBy)){
				$where .= "and ".$filter."= ".$searchBy;
			}
				
			$stmt = $conn->prepare("
				select distinct Lab.Name labName, BeginDate, DueDate, FirstName,LastName, StudentID, Class.Name className, Section.SectionNum section
				from Student
				inner join Grade on Student.ID=Grade.StudentID
				inner join Lab on Grade.LabID=Lab.ID
				inner join SectionLab on Lab.ID=SectionLab.SectionID
				inner join Section on SectionLab.SectionID=Section.ID
				inner join Class on Section.ClassID=Class.ID
				where Student.isActive and Lab.IsActive and Class.IsActive $where");
			
			$stmt->execute();
			
			echo "<table><tr><th>Lab Name</th><th>Lab begin date</th><th>Lab due date</th><th>Student Name</th><th>Student ID</th><th>Class Name</th><th>Class Section</th></tr>";
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				$arr[] = $row;
				list($labName, $BeginDate, $DueDate, $FirstName,$LastName, $StudentID, $className, $section) = $row;
				echo "<tr><td>".$labName."</td>
				<td>".$BeginDate."</td>
				<td>".$DueDate."</td>
				<td>".$FirstName." ".$LastName."</a></td>
				<td>".$StudentID."</a></td>
				<td>".$className."</td>
				<td>".$section."</td></tr>";

				/* outline for inserting links:
				<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
				*/

			}
			echo "</table>";
			if(!$arr) exit('No rows');
			//var_export($arr);
			$stmt = null;
			
			
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
		echo "</table>";
?>
	