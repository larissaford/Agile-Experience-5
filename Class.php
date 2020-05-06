<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lab: <?=$_GET["className"] ?></title>
    <link rel="stylesheet" type="text/css" href="Agile-Experience-5.css" />
</head>
<body>
<?php
		
		//display class
		$servername = "localhost:3306";
		$username = "g5AppUser";
		$password = "aug5";
        $dbname = "G5AgileExperience";

        try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("
			select Lab.ID labID, Lab.Name labName, BeginDate, DueDate, Rubric, FirstName, LastName, Student.ID, Class.Name className, Class.ID, Section.SectionNum section
					from Student
					inner JOIN StudentSection ON Student.ID = StudentSection.StudentID
					inner JOIN Section ON Section.ID = StudentSection.SectionID
					inner JOIN SectionLab ON Section.ID = SectionLab.SectionID
					inner JOIN Lab ON Lab.ID = SectionLab.LabID
					inner join Class on Section.ClassID=Class.ID
					WHERE Student.IsActive 
					AND StudentSection.IsActive 
					AND Section.IsActive
					AND SectionLab.IsActive
                    AND Lab.IsActive and Class.Name=?
                    GROUP BY Lab.ID");
			
			$stmt->execute([$_GET['className']]);
			
			$row = $stmt->fetch(PDO::FETCH_NUM);
			//$arr[] = $row;
			list($labID, $labName, $beginDate, $dueDate, $rubric, $firstName, $lastName, $studentID, $className, $classID, $section) = $row;
			?>
			<h1><?=$className?> - Section <?=$section?> (Class ID: <?=$classID?>)</h1>
            Labs:
			<table><tr><th>Lab Name</th><th>Lab ID</th><th>Lab begin date</th><th>Lab due date</th></tr>	

			<?php
			do {
				list($labID, $labName, $beginDate, $dueDate, $rubric, $firstName, $lastName, $studentID, $className, $section, $Grade) = $row;
				
                echo "<tr>
                    <td><a href='Lab.php?labName=".$labName."'>".$labName."</a></td>
					<td><a href='Lab.php?labName=".$labName."'>".$labID."</a></td>
					<td>".$beginDate."</td>
					<td>".$dueDate."</td>
					</tr>";
			
            } while ($row = $stmt->fetch(PDO::FETCH_NUM));
            echo "</table>";
            $stmt = $conn->prepare("
			select Lab.ID labID, Lab.Name labName, BeginDate, DueDate, Rubric, FirstName, LastName, Student.ID, Class.Name className, Class.ID, Section.SectionNum section
					from Student
					inner JOIN StudentSection ON Student.ID = StudentSection.StudentID
					inner JOIN Section ON Section.ID = StudentSection.SectionID
					inner JOIN SectionLab ON Section.ID = SectionLab.SectionID
					inner JOIN Lab ON Lab.ID = SectionLab.LabID
					inner join Class on Section.ClassID=Class.ID
					WHERE Student.IsActive 
					AND StudentSection.IsActive 
					AND Section.IsActive
					AND SectionLab.IsActive
                    AND Lab.IsActive and Class.Name=?
                    GROUP BY Student.FirstName, Student.LastName, Student.ID, Section.SectionNum");
            $stmt->execute([$_GET['className']]);
			
            echo "Students:";
            echo "<table><tr><th>Student ID</th><th>Student Name</th></tr>";
            
            do {
				list($labID, $labName, $beginDate, $dueDate, $rubric, $firstName, $lastName, $studentID, $className, $section, $Grade) = $row;
				
                echo "<tr>
                    <td><a href='Student.php?StudentID=".$studentID."'>".$studentID."</a></td>
                    <td><a href='Student.php?StudentID=".$studentID."'>".$firstName." ".$lastName."</a></td>
                    </tr>";
                
			} while ($row = $stmt->fetch(PDO::FETCH_NUM));


			echo "</table>";
			$stmt = null;
			
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
        










        /*
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("
            select Class.ID classID, Class.Name className, Lab.Name labName,
			Lab.BeginDate beginDate, Lab.DueDate dueDate, Student.FirstName firstName, 
			Student.LastName lastName, Student.ID studentID, Section.SectionNum sectionNum, max(Log.TimeStamp) mostRecent
            from Lab
            inner join Grade on Lab.ID = Grade.LabID
            inner join Student on Grade.StudentID = Student.ID
            inner join StudentSection on Student.ID = StudentSection.StudentID
            inner join Section on StudentSection.SectionID = Section.ID
            inner join Class on Section.ClassID = Class.ID 
            inner join Log on Grade.ID=Log.GradeID
            where Lab.IsActive and Student.isActive and Class.Name = :name
            GROUP BY Student.FirstName, Student.LastName, Student.ID, Section.SectionNum");
            $stmt->bindParam(':name', $_GET["className"], PDO::PARAM_STR,64); //Accepting Input
            $stmt->execute();
            
            $counter = 0;
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $arr[] = $row;
                list($classID, $className, $labName, $beginDate, $dueDate, $firstName, $lastName, $studentID, $sectionNum) = $row;
                
                if ($counter == 0) {
                    echo "
                        <h1>".$className." - ".$sectionNum." (Class ID: ".$classID.")</h1>
						<p>
						Labs:
						</p>
                        <table>
                            <tr>
								<th>Lab Name</th>
                                <th>Begin Date</th>
                                <th>Due Date</th>
                            </tr>
                            <tr>
								<td><a href='Lab.php?labName=".$labName."'>".$labName."</a></td>
                                <td>".$beginDate."</td>
                                <td>".$dueDate."</td>
                            </tr>
                        </table>
                        <p>
                        Students:
                        </p>
                        <table>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Section Number</th>
                            </tr>
                            <tr>
                                <td><a href='Student.php?StudentID=".$studentID."'>".$studentID."</a></td>
                                <td><a href='Student.php?StudentID=".$studentID."'>".$firstName." ".$lastName."</a></td>
                                <td><a href='Class.php?section=".$sectionNum."'>".$sectionNum."</a></td>
                            </tr>
                    ";
                    $counter++;
                }
                else {
                    echo "
                        <tr>
                            <td><a href='Student.php?StudentID=".$studentID."'>".$studentID."</a></td>
                            <td><a href='Student.php?StudentID=".$studentID."'>".$firstName." ".$lastName."</a></td>
                            <td><a href='Class.php?section=".$sectionNum."'>".$sectionNum."</a></td>
                        </tr>
                    ";
                }
            }

            if ($counter == 0) { //never touched the loop, therefore there is some issue with this lab
                echo "<p>
                       This class (".$_GET["className"].") is not active or has no active students. Please add students or activate class to continue.
                      </p>";
            }
            echo "</table>";
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
        $conn = null;
        */
    ?>
    

</body>
</html>