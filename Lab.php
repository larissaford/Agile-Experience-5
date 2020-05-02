<!--
lab.php

Noah Scherwinski & James Cerasani

Generates a page giving information about a single lab.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lab: <?=$_GET["labName"] ?></title>
    <link rel="stylesheet" type="text/css" href="Agile-Experience-5.css" />
</head>
<body>
<?php
		
		//display labs
		$servername = "144.13.22.59:3306";
		$username = "g5AppUser";
		$password = "aug5";
		$dbname = "G5AgileExperience";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("
            select Lab.ID labID, Lab.Name labName, Lab.BeginDate beginDate, Lab.DueDate dueDate, Lab.Rubric rubric,
            Student.FirstName firstName, Student.LastName lastName, Student.ID studentID,
            Class.Name className, Section.SectionNum sectionNum
            from Lab
            inner join SectionLab on Lab.ID = SectionLab.LabID
            inner join Section on SectionLab.SectionID = Section.ID
            inner join StudentSection on Section.ID = StudentSection.SectionID
            inner join Student on StudentSection.StudentID = Student.ID
            inner join Class on Section.ClassID = Class.ID 
            where Lab.IsActive and Student.isActive and Lab.Name = :name ");
            $stmt->bindParam(':name', $_GET["labName"], PDO::PARAM_STR,64); //Accepting Input
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_NUM);
            list($labID, $labName, $beginDate, $dueDate, $rubric, $firstName, $lastName, $studentID, $className, $sectionNum) = $row;
            ?>
            <h1><?=$labName?> (Lab ID: <?=$labID?>)</h1>
            <table>
                <tr>
                    <th>Begin Date</th>
                    <th>Due Date</th>
                </tr>
                <tr>
                    <td><?=$beginDate?></td>
                    <td><?=$dueDate?></td>
                </tr>
            </table>

            <p>
            Link to Rubric: <a href='rubrics/<?=rubric?>'>Rubric</a>
            </p>
<?php
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                list($labID, $labName, $beginDate, $dueDate, $rubric, $firstName, $lastName, $studentID, $className, $sectionNum) = $row;

                    ?>
                    <tr>
                        <td><a href='Student.php?StudentID=<?=$studentID?>'><?=$studentID?></a></td>
                        <td><a href='Student.php?StudentID=<?=$studentID?>'><?=$firstName?> <?=$lastName?></a></td>
                        <td><a href='Class.php?className=<?=$className?>'><?=$className?></a></td>
                        <td><a href='Class.php?className=<?=$className?>'><?=$sectionNum?></a></td>
                        <td><a href='Grade.php?StudentID=<?=$studentID?>&LabID=<?=$labID?>'>Grade Lab</a></td>
                    </tr>
                    <?php
            }

            if ($stmt->rowCount==0) { //never touched the loop, therefore there is some issue with this lab
                ?>
                <p>
                       This lab (<?=$_GET["labName"]?>) is not active or has no active students. Please add students or activate lab to continue.
                      </p>
                      <?php
            }
            ?>
            <!--Notes form has no link to save text value.-->
            
            </table>
            <br \><br \>
            <div id='inputNotes' align='left'>
                <form action='/savenote.php'>
                    <label for='Comment'>Add new comment below</label><br><br>
                    <textarea rows='4' cols='50'></textarea>
                    </textarea><br><br>
                <input type='submit' value='Submit'>
                </form>
            </div>
            <?php
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
        $conn = null;
    ?>
</body>
</html>
