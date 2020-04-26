<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student</title>
</head>

<body>
	<p>
	<?php
		$ID = $_GET["ID"];
		$FirstName   = $_GET["FirstName"];
		$LastName  = $_GET["LastName"];
		$isActive= $_GET["isActive"];
		
		//display labs of student
		//display grades
		//display classes

		echo "<table style='border: solid 1px black;'>";
		echo "<tr><th>FullName</th></tr>";

		class TableRows extends RecursiveIteratorIterator {
			function __construct($it) {
				parent::__construct($it, self::LEAVES_ONLY);
			}

			function current() {
				return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
			}

			function beginChildren() {
				echo "<tr>";
			}

			function endChildren() {
				echo "</tr>" . "\n";
			}
		}

		$servername = "https://144.13.22.59:3306";
		$username = "g5AppUser";
		$password = "aug5";
		$dbname = "G5AgileExperience";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("
			select FirstName,LastName, StudentID, Class.Name className, Lab.Name labName, BeginDate, DueDate 
			from Student
			inner join Grade on Student.ID=Grade.StudentID
			inner join Lab on Grade.LabID=Lab.ID
			inner join SectionLab on Lab.ID=SectionLab.SectionID
			inner join Section on SectionLab.SectionID=Section.ID
			inner join Class on Section.ClassID=Class.ID
			where Student.isActive and Lab.IsActive and Class.IsActive");
			$stmt->execute();

			// set the resulting array to associative
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
				echo $v;
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
		echo "</table>";
	?>
	</p>
</body>
</html>