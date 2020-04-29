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

		//make it more responsive to the search page
		$filter = $_GET["filter"]; //needs to be the column name i.e. "StudentID" or "Lab.Name"
		$searchBy = $_GET["this"]; //Strings need to be surrounded with single quotes i.e. lab name 'Vidoo'

		//TEST
		//$filter = "Student";
		//$searchBy = "";
		

		try {

			//search by Lab.Name, SectionNum, Class.Name, FirstName, LastName, etc. 
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$arr = [];

			
			
			/*
			if(!empty($searchBy)){
				$where .= "and ".$filter."= ".$searchBy;
			}
			*/
			
			/*
			$stmt = $conn->prepare("
				select distinct Lab.Name labName, BeginDate, DueDate, FirstName,LastName, StudentID, Class.Name className, Section.SectionNum section, Grade
				from Student
				inner join Grade on Student.ID=Grade.StudentID
				inner join Lab on Grade.LabID=Lab.ID
				inner join SectionLab on Lab.ID=SectionLab.SectionID
				inner join Section on SectionLab.SectionID=Section.ID
				inner join Class on Section.ClassID=Class.ID
				where Student.isActive and Lab.IsActive and Class.IsActive $where");
			*/
			$where="";
			switch ($filter) {
				case "Lab":
					if(!empty($searchBy)){
						$where .= "and Lab.Name = '".$searchBy."' or Lab.ID = '".$searchBy."'";
					}
					$stmt = $conn->prepare("
						select distinct Lab.Name labName, BeginDate, DueDate, Class.Name className, Section.SectionNum section
						from Lab 
						inner join SectionLab on Lab.ID=SectionLab.SectionID
						inner join Section on SectionLab.SectionID=Section.ID
						inner join Class on Section.ClassID=Class.ID
						where Lab.IsActive and Class.IsActive $where");
						$stmt->execute();

					echo "<table><tr><th>Lab Name</th><th>Lab begin date</th><th>Lab due date</th><th>Class Name</th><th>Class Section</th></tr>";
					while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
						$arr[] = $row;
						list($labName, $BeginDate, $DueDate, $className, $section) = $row;
						echo "
							<td><a href='Lab.php?labName=".$labName."'>".$labName."</a></td>
							<td>".$BeginDate."</td>
							<td>".$DueDate."</td>
							<td><a href='Class.php?className=".$className."'>".$className."</a></td>
							<td><a href='Class.php?section=".$section."'>".$section."</a></td>
							</tr>";
							
						/* outline for inserting links:
						<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
						*/
		
					}
					break;

				case "Class":
					if(!empty($searchBy)){
						$where .= "and Class.Name = ".$searchBy;
					}
					
					$stmt = $conn->prepare("
						select distinct Class.Name className, Section.SectionNum section
						from Section
						inner join SectionLab on SectionLab.SectionID=Section.ID
						inner join Class on Section.ClassID=Class.ID
						where Class.IsActive $where");
						$stmt->execute();

					echo "<table><tr><th>Class Name</th><th>Class Section</th></tr>";
					while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
						$arr[] = $row;
						list($className, $section) = $row;
						echo "
							<td><a href='Class.php?className=".$className."'>".$className."</a></td>
							<td><a href='Class.php?section=".$section."'>".$section."</a></td>
							</tr>";
						
						/* outline for inserting links:
						<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
						*/
					}
					break;

				case "Student":
					if(!empty($searchBy)){
						$where .= "and FirstName = ".$searchBy." or LastName = ".$searchBy." or StudentID = ".$searchBy;
					}
					
					$stmt = $conn->prepare("
						select distinct FirstName,LastName, StudentID, Class.Name className, Section.SectionNum section
						from Student
						inner join Grade on Student.ID=Grade.StudentID
						inner join Lab on Grade.LabID=Lab.ID
						inner join SectionLab on Lab.ID=SectionLab.SectionID
						inner join Section on SectionLab.SectionID=Section.ID
						inner join Class on Section.ClassID=Class.ID
						where Student.isActive and Lab.isActive and Class.IsActive $where");
					$stmt->execute();
					
					echo "<table><tr><th>Student Name</th><th>Student ID</th><th>Class Name</th><th>Class Section</th></tr>";
					while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
						$arr[] = $row;
						list($FirstName,$LastName, $StudentID, $className, $section) = $row;
						echo "
							<td><a href='Student.php?StudentID=".$StudentID."'>".$FirstName." ".$LastName."</a></td>
							<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
							<td><a href='Class.php?className=".$className."'>".$className."</a></td>
							<td><a href='Class.php?section=".$section."'>".$section."</a></td>
							</tr>";
							
						/* outline for inserting links:
						<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
						*/
					}
				break;	
			break;
			case "":
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
					echo "
					<td><a href='Lab.php?labName=".$labName."'>".$labName."</a></td>
						<td>".$BeginDate."</td>
						<td>".$DueDate."</td>
						<td><a href='Student.php?StudentID=".$StudentID."'>".$FirstName." ".$LastName."</a></td>
						<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
						<td><a href='Class.php?className=".$className."'>".$className."</a></td>
						<td><a href='Class.php?section=".$section."'>".$section."</a></td>
						</tr>";
						
						/* outline for inserting links:
						<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
						*/
				}
		}
			
			
			/*
			echo "<table><tr><th>Lab Name</th><th>Lab begin date</th><th>Lab due date</th><th>Student Name</th><th>Student ID</th><th>Class Name</th><th>Class Section</th><th>Grade</th><th>regrade?</th></tr>";
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				$arr[] = $row;
				list($labName, $BeginDate, $DueDate, $FirstName,$LastName, $StudentID, $className, $section, $grade) = $row;
				echo "<tr><td>".$labName."</td>
				<td>".$BeginDate."</td>
				<td>".$DueDate."</td>
				<td>".$FirstName." ".$LastName."</a></td>
				<td>".$StudentID."</a></td>
				<td>".$className."</td>
				<td>".$section."</td>
				<td>".$grade."</td>
				<td><a href='grade.php?studentID=".$StudentID."'>grading page</a></td></tr>";
				
				/* outline for inserting links:
				<td><a href='Student.php?StudentID=".$StudentID."'>".$StudentID."</a></td>
				*/

			//}
			
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