
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Results</title>
    <link rel="stylesheet" type="text/css" href="Agile-Experience-5.css" />
  </head>

<?php
		//display student name and ID
		//display labs of student
		//display grades
		//display classes
		//display rubric

		$servername = "localhost:3306";
		$username = "g5AppUser";
		$password = "aug5";
		$dbname = "G5AgileExperience";

		$arr = [];	
				
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("
			select distinct Lab.ID, Lab.Name labName, BeginDate, DueDate, FirstName, LastName, Student.ID, Class.Name className, Section.SectionNum section, Grade
					from Student
					JOIN StudentSection ON Student.ID = StudentSection.StudentID
					JOIN Section ON Section.ID = StudentSection.SectionID
					JOIN SectionLab ON Section.ID = SectionLab.SectionID
					JOIN Lab ON Lab.ID = SectionLab.LabID
					inner join Class on Section.ClassID=Class.ID
					inner join Grade on Student.ID=Grade.StudentID
					WHERE Student.IsActive 
					AND StudentSection.IsActive 
					AND Section.IsActive
					AND SectionLab.IsActive
					AND Lab.IsActive and Student.ID=?");
			
			$stmt->execute([$_GET['StudentID']]);

			
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				$arr[] = $row;
				list($labID, $labName, $beginDate, $dueDate, $rubric, $firstName, $lastName, $studentID, $className, $section, $Grade) = $row;

				echo "<table><tr><th>Student Name</th><th>Student ID</th><th>Class Name</th><th>Class Section</th><th>Lab Name</th><th>Lab ID</th><th>Lab begin date</th><th>Lab due date</th><th>Lab Rubric</th><th>Lab Grade</th><th>regrade?</th></tr>";
				echo "
					<tr><td>".$firstName." ".$lastName."</td>
					<td>".$studentID."</td>
					<td><a href='Class.php?className=".$className."'>".$className."</a></td>
					<td>".$section."</td>
					<td><a href='Lab.php?labName=".$labName."'>".$labName."</a></td>
					<td><a href='Lab.php?labName=".$labName."'>".$labID."</a></td>
					<td>".$beginDate."</td>
					<td>".$dueDate."</td>
					<td>".$rubric."</td>
					<td>".$Grade."</td>
					<td><a href='Grade.php?StudentID=".$studentID."&LabID=".$labID."'>Grading Page</td></tr>";
			}
					
				/* outline for inserting links:
				<td><a href='Student.php?movie=<?php echo $StudentID ?>'>".$FirstName." ".$LastName."</a></td>
				*/

			echo "</table>";
			if(!$arr) exit('No rows');
			$stmt = null;
			
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
		echo "</table>";
?>
	