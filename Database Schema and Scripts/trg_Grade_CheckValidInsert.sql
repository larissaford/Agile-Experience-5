CREATE OR REPLACE TRIGGER trg_Grade_CheckValidInsert 
BEFORE INSERT ON Grade
FOR EACH ROW
BEGIN
  IF NOT(SELECT True FROM Lab
    JOIN SectionLab ON Lab.ID = SectionLab.LabID AND SectionLab.IsActive
    JOIN Section ON Section.ID = SectionLab.SectionID AND Section.IsActive
    JOIN StudentSection ON Section.ID = StudentSection.SectionID AND StudentSection.IsActive
    JOIN Student ON Student.ID = StudentSection.StudentID AND Student.IsActive
    WHERE Lab.ID = New.LabID AND Lab.IsActive
      AND Student.ID = New.StudentID)
  THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Student does not belong to a class to which this lab is assigned.';
  END IF;
  IF(SELECT Sum(Criteria.MaxScore) FROM Lab
      JOIN Criteria ON Lab.ID = Criteria.LabID
      WHERE Lab.ID = New.LabID) < New.Grade
  THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Grade is higher than max value for this lab.';
  END IF;
END;
/
