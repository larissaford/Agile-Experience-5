<?php
    // Database info
    $DATABASE_HOST = '144.13.22.59:3306';
    $DATABASE_USER = 'g5AppUser';
    $DATABASE_PASS = 'aug5';
    $DATABASE_NAME = 'G5AgileExperience';

    // Establish database connection
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    // Check if connection sucessful
    if(mysqli_connect_errno()) {
        exit('Failed to connect: ' . mysqli_connect_error());
    }

    // Check that POST information is correct.
    if(!isset($_POST['StudentID'], $_POST['LabID'])) {
        exit('Failed to retrieve POST information.');
    }

    // Get student and lab information based on POST data
    $stmt = $con->prepare(
        'SELECT FirstName, LastName, Name, Rubric, BeginDate, DueDate
        FROM Student
        JOIN StudentSection ON Student.ID = StudentSection.StudentID
        JOIN Section ON Section.ID = StudentSection.SectionID
        JOIN SectionLab ON Section.ID = SectionLab.SectionID
        JOIN Lab ON Lab.ID = SectionLab.LabID
        WHERE Student.IsActive 
        AND StudentSection.IsActive 
        AND Section.IsActive
        AND SectionLab.IsActive
        AND Lab.IsActive
        AND Student.ID = ?
        AND Lab.ID = ?'
    );
    $stmt->bind_param('ii', $_POST['StudentID'], $_POST['LabID']);
    $stmt->execute();
    $stmt->bind_result($FirstName, $LastName, $LabName, $Rubric, $BeginDate, $DueDate);
    $stmt->fetch();
    $stmt->close();
    $con->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Grading Page</title>
        <script src="Grade.js"></script>
    </head>
    <body>
        <h2><?=$LastName?>, <?=$FirstName?>: <?=$LabName?></h2>
        <p>
            Begin Date: <?=$BeginDate?></br>
            Due Date: <?=$DueDate?></br>
            Rubric: <?=$Rubric?></br>
        </p>
        <form onsubmit="return submitGrade(this)" method="post" autocomplete="off">
            <label>Grade:</label>
            <input type="number" id="Grade" name="Grade" min="0" max="100" step="1" value="100" required>
            <input type="submit" value="Submit"><br>
            <p id="status"></p>
            <input type="hidden" name="StudentID" value="<?=$_POST['StudentID']?>"/>
            <input type="hidden" name="LabID" value="<?=$_POST['LabID']?>"/>
        </form>
    </body>
</html>