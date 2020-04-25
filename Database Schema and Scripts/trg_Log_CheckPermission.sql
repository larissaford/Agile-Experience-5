CREATE OR REPLACE TRIGGER trg_Log_CheckPermission 
BEFORE INSERT ON Log
FOR EACH ROW
BEGIN
  IF(SELECT Count(*) FROM Grader
    JOIN Access ON Grader.ID = Access.GraderID
    JOIN Section ON Section.ID = Access.SectionID AND Section.IsActive
    JOIN SectionLab ON Section.ID = SectionLab.SectionID AND SectionLab.IsActive
    JOIN Lab ON SectionLab.LabID = Lab.ID AND Lab.IsActive
    JOIN Grade ON Lab.ID = Grade.LabID
    JOIN Permission ON Access.PermissionID = Permission.ID
      AND (Permission.Name = 'Admin' OR Permission.Name = 'Grade')
    WHERE Grader.ID = New.GraderID AND Grader.IsActive 
      AND Grade.ID = New.GradeID) = 0
  THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Grader does not have permission.';
  END IF;
END;
/
