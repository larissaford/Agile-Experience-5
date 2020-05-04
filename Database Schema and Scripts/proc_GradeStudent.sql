CREATE OR REPLACE PROCEDURE
proc_GradeStudent
(StudentID INTEGER,
LabID INTEGER,
GraderID INTEGER,
Grade INTEGER)
MODIFIES SQL DATA
BEGIN
  DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error;
      ROLLBACK;
    END;
  START TRANSACTION;
    INSERT INTO Grade(StudentID, LabID, Grade) VALUES (StudentID, LabID, Grade);
    INSERT INTO Log(GraderID, GradeID, TimeStamp) VALUES (GraderID, LAST_INSERT_ID(), CURRENT_TIMESTAMP);
  COMMIT;
END;
/
