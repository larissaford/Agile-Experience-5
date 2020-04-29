<!--
lab.php

Noah Scherwinski
edited by Larissa Ford

Generates a page giving information about a lab.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lab - Grading Tool</title>
</head>
<body>
<?php
		
		//display labs
		$servername = "localhost:3306";
		$username = "g5AppUser";
		$password = "aug5";
        $dbname = "G5AgileExperience";
        
        $lab = $_GET["labName"];
        //$labName = str_replace('"', "'", $labName);

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("
            select Lab.ID labID, Lab.Name labName, Lab.BeginDate beginDate, Lab.DueDate dueDate, Lab.Rubric rubric,
            Student.FirstName firstName, Student.LastName lastName, Class.Name className, Section.SectionNum sectionNum
            from Lab
            inner join Grade on Lab.ID = Grade.LabID
            inner join Student on Grade.StudentID = Student.ID
            inner join StudentSection on Student.ID = StudentSection.StudentID
            inner join Section on StudentSection.SectionID = Section.ID
            inner join Class on Section.ClassID = Class.ID 
            where Lab.IsActive and Student.isActive and Lab.Name = $lab");
            //$stmt->bindParam(':name', , PDO::PARAM_STR,64); //Accepting Input
            $stmt->execute();
            

			$arr = $stmt->fetch(PDO::FETCH_NUM); //FETCH_NUM must be used with list
			if(!$arr) exit('no rows');
            list($labID, $labName, $beginDate, $dueDate, $Rubric) = $arr;
            
            echo "
                <h1>".$labName." (".$labID.")</h1>
                <table>
                    <tr>
                        <th>Begin Date</th>
                        <th>Due Date</th>
                    </tr>
                    <tr>
                        <td>".$beginDate."</td>
                        <td>".$dueDate."</td>
                    </tr>
                </table>

                <p>
                Link to Rubric: ".$rubric."
                </p>
            ";	
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
        $conn = null;

    ?>

</body>
</html>
