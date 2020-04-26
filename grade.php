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

    // Check that grading information is correct (Later, this will also check that GraderID is set, once login is implemented)
    if(!isset($_POST['StudentID'], $_POST['LabID'], $_POST['Grade'])) {
        exit('Failed to retrieve POST information.');
    }
    // Insert grade into the database
    if($stmt = $con->prepare('INSERT INTO Grade(StudentID, LabID, Grade) VALUES (?, ?, ?)')) {
        $stmt->bind_param('iii', $_POST['StudentID'], $_POST['LabID'], $_POST['Grade']);
        $stmt->execute();
        $stmt->close();
    } else {
        exit('SQL Error: ' . $con->error);
    }
    // Insert log into database
    if($stmt = $con->prepare('INSERT INTO Log(GraderID, GradeID, TimeStamp) VALUES (?, ?, CURRENT_TIMESTAMP)')) {
        // default GraderID to one until login system is finished
        $one = 1;
        $GradeID = $con->insert_id;
        echo($con->insert_id);
        $stmt->bind_param('ii', $one, $GradeID);
        $stmt->execute();
        $stmt->close();
    } else {
        exit('SQL Error: ' . $con->error);
    }
    $con->close();
?>