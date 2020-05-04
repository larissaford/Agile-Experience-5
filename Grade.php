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
    if(!isset($_GET['StudentID'], $_GET['LabID'])) {
        exit('Failed to retrieve GET information.');
    }

    // Get student and lab information based on POST data
    $stmt = $con->prepare(
        'SELECT FirstName, LastName, Name, Rubric, BeginDate, DueDate, GradeForCompletion
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
    $stmt->bind_param('ii', $_GET['StudentID'], $_GET['LabID']);
    $stmt->execute();
    $stmt->bind_result($FirstName, $LastName, $LabName, $Rubric, $BeginDate, $DueDate, $GradeForCompletion);
    $stmt->fetch();
    $stmt->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Grading Page</title>
        <script src="Grade.js"></script>
        <link rel="stylesheet" type="text/css" href="Agile-Experience-5.css" />
    </head>
    <body>
        <h2><?=$LastName?>, <?=$FirstName?>: <?=$LabName?></h2>
        <p>
            Begin Date: <?=$BeginDate?></br>
            Due Date: <?=$DueDate?></br>
            Rubric: <?=$Rubric?></br>
        </p>

        <?php
        $stmt = $con->prepare(
            'SELECT MaxScore, Criteria.Name, GradeForCompletion 
            FROM Lab 
            JOIN Criteria ON Lab.ID = Criteria.LabID 
            WHERE Lab.ID = ?;'
            );   
        $stmt->bind_param('i', $_GET['LabID']);
        $stmt->execute();

        $stmt->bind_result($MaxScore, $CriteriaName, $GradeForCompletion);
        ?>
        <form onsubmit="return submitGrade(this)" method="post" autocomplete="off" id="GradeForm">
        <table>
        <?php
        $TotalScore = 0;
        while($stmt->fetch())
        {
            $TotalScore += $MaxScore;
            ?>
            <tr>
                <th><?=$CriteriaName?></th>
            <?php
            if($GradeForCompletion) {
            ?>
                <td><p><input type="checkbox" name="CriteriaGrade" checked max="<?=$MaxScore?>" oninput="updateScore()"> worth <?=$MaxScore?> points</p></td>
            <?php
            } else {
            ?>
                <td><p><input type="number" name="CriteriaGrade" min="0" max="<?=$MaxScore?>" step="1" value="<?=$MaxScore?>" oninput="updateScore()"> / <?=$MaxScore?> points</p></td>
            <?php
            }
            ?>
            </tr>
            <?php
        }
        ?>
            <tr>
                <th>Total Score:</th>
                <td><p id="TotalGrade"><?=$TotalScore?> out of <?=$TotalScore?> points.</p></td>
            </tr>
        </table>
            <input type="hidden" id="Grade" name="Grade" value="<?=$TotalScore?>">
            <input type="submit" value="Submit"><br>
            <p id="status"></p>
            <input type="hidden" name="StudentID" value="<?=$_GET['StudentID']?>"/>
            <input type="hidden" name="LabID" value="<?=$_GET['LabID']?>"/>
        </form>
        <?php
        $stmt->close();
        $con->close();
        ?>
    </body>
</html>
