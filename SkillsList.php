<?php
    // Database info
    $DATABASE_HOST = 'localhost:3306';
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
    if(!isset($_GET['ClassID'])) {
        exit('Failed to retrieve GET information.');
    }

    $stmt = $con->prepare(
        'SELECT Class.ID, Class.Name'
        FROM Class
        WHERE Class.isActive
        AND Class.ID = ?
    );
    $stmt->bind_param('i', $_GET["ClassID"]);
    $stmt->execute();
    $stmt->bind_result($ClassID, $ClassName);
    $stmt->fetch();
    $stmt->close();
<html>
    <head>
        <title>Skills Page</title>
        <link rel="stylesheet" type="text/css" href="Agile-Experience-5.css" />
    </head>
    <body>
        <h2>Skills List</h2>
        <?php
        $stmt = $con->prepare(
            'SELECT Skill.Name, Learned
            FROM Skill, SectionSkill
            inner join Class on Section.ClassID = Class.ID
            inner join Section on SectionSkill.SectionID = Section.ID
            WHERE Class.ID = ?
            And Section.ID = ?'
        );
        $stmt->bind_param('ii', $_GET['ClassID'], $_GET['SectionID']);
        $stmt->execute();

        $stmt->bind_result($SkillName, $Learned);
        ?>
        <table>
        <?php
        while($stmt->fetch())
        {
            ?>
            <tr>
                <td><?=$SkillName></td>
                <?php
                if($Learned)
                {
                    ?>
                    <td><input type="checkbox" name="SkillChecked" checked</td>
                    <?php
                }
                else
                {
                    ?>
                    <td><input type="checkbox" name="SkillUnchecked"</td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
        </table>
    </body
</html

