<?php
    // Database info
    $DATABASE_HOST = '144.13.22.59:3306';
    $DATABASE_USER = 'g5AppUser';
    $DATABASE_PASS = 'aug5';
    $DATABASE_NAME = 'G5AgileExperience';

    // Prepare XML response
    header('Content-Type:text/xml');
    $xml = new SimpleXMLElement('<response></response>');

    // Establish database connection
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    // Check if connection sucessful
    if(mysqli_connect_errno()) {
        $xml->addAttribute('status', 'FAIL');
        $xml->addAttribute('reason', 'Failed to connect to database.');
        echo($xml->asXML());
        exit;
    }

    // Check that grading information is correct (Later, this will also check that GraderID is set, once login is implemented)
    if(!isset($_POST['StudentID'], $_POST['LabID'], $_POST['Grade'])) {
        $xml->addAttribute('status', 'FAIL');
        $xml->addAttribute('reason', 'Failed to receive grade data.');
        echo($xml->asXML());
        exit;
    }

    // Insert grade into the database
    $stmt = $con->prepare('INSERT INTO Grade(StudentID, LabID, Grade) VALUES (?, ?, ?)');
    $stmt->bind_param('iii', $_POST['StudentID'], $_POST['LabID'], $_POST['Grade']);
    if($stmt->execute()) {
        $stmt->close();
    } else {
        $xml->addAttribute('status', 'FAIL');
        $xml->addAttribute('reason', 'SQL Error: ' . $con->error);
        echo($xml->asXML());
        $stmt-close();
        exit;
    }
    
    // Insert log into database
    $stmt = $con->prepare('INSERT INTO Log(GraderID, GradeID, TimeStamp) VALUES (?, ?, CURRENT_TIMESTAMP)');
    // default GraderID to one until login system is finished
    $one = 1;
    $GradeID = $con->insert_id;
    $stmt->bind_param('ii', $one, $GradeID);
    if($stmt->execute()) {
        $stmt->close();
    } else {
        $xml->addAttribute('status', 'FAIL');
        $xml->addAttribute('reason', 'SQL Error: ' . $con->error);
        echo($xml->asXML());
        $stmt->close();
        exit;
    }
    $con->close();
    $xml->addAttribute('status', 'SUCCESS');
    echo($xml->asXML());
    exit;
?>