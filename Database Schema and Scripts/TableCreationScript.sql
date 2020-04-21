SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Lab;
DROP TABLE IF EXISTS Grader;
DROP TABLE IF EXISTS Student;
DROP TABLE IF EXISTS Skill;
DROP TABLE IF EXISTS Permission;
DROP TABLE IF EXISTS Class;
DROP TABLE IF EXISTS Section;
DROP TABLE IF EXISTS Access;
DROP TABLE IF EXISTS SectionSkill;
DROP TABLE IF EXISTS SectionLab;
DROP TABLE IF EXISTS StudentSection;
DROP TABLE IF EXISTS Note;
DROP TABLE IF EXISTS Grade;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE Lab (
    ID int NOT NULL AUTO_INCREMENT,
    Name varchar(32) NOT NULL,
    Rubric varchar(260),
    DueDate datetime,
    IsActive boolean NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE Grader (
    ID int NOT NULL AUTO_INCREMENT,
    Username varchar(50) NOT NULL,
    Password varchar(255) NOT NULL,
    Email varchar(100) NOT NULL,
    FirstName varchar(32) NOT NULL,
    LastName varchar(32) NOT NULL,
    IsProfessor boolean NOT NULL,
    IsActive boolean NOT NULL,
    ActivationCode varchar(13),
    PRIMARY KEY(ID)
);

CREATE TABLE Student (
    ID int NOT NULL AUTO_INCREMENT,
    FirstName varchar(32) NOT NULL,
    LastName varchar(32) NOT NULL,
    IsActive boolean NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE Skill (
    ID int NOT NULL AUTO_INCREMENT,
    Name varchar(32) NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE Permission (
    ID int NOT NULL AUTO_INCREMENT,
    Name varchar(32) NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE Class (
    ID int NOT NULL AUTO_INCREMENT,
    Name varchar(32) NOT NULL,
    IsActive boolean NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE Section (
    ID int NOT NULL AUTO_INCREMENT,
    ClassID int NOT NULL,
    SectionNum int NOT NULL,
    IsActive boolean NOT NULL,
    FOREIGN KEY(ClassID) REFERENCES Class(ID),
    PRIMARY KEY(ID)
);

CREATE TABLE Access (
    GraderID int NOT NULL,
    PermissionID int NOT NULL,
    SectionID int NOT NULL,
    FOREIGN KEY(GraderID) REFERENCES Grader(ID),
    FOREIGN KEY(PermissionID) REFERENCES Permission(ID),
    FOREIGN KEY(SectionID) REFERENCES Section(ID),
    PRIMARY KEY(GraderID, PermissionID, SectionID)
);

CREATE TABLE SectionSkill (
    SectionID int NOT NULL,
    SkillID int NOT NULL,
    Learned boolean NOT NULL,
    FOREIGN KEY(SectionID) REFERENCES Section(ID),
    FOREIGN KEY(SkillID) REFERENCES Skill(ID),
    PRIMARY KEY(SectionID, SkillID)
);

CREATE TABLE SectionLab (
    SectionID int NOT NULL,
    LabID int NOT NULL,
    IsActive boolean NOT NULL,
    FOREIGN KEY(SectionID) REFERENCES Section(ID),
    FOREIGN KEY(LabID) REFERENCES Lab(ID),
    PRIMARY KEY(SectionID, LabID)
);

CREATE TABLE StudentSection (
    StudentID int NOT NULL,
    SectionID int NOT NULL,
    IsActive boolean NOT NULL,
    FOREIGN KEY(StudentID) REFERENCES Student(ID),
    FOREIGN KEY(SectionID) REFERENCES Section(ID),
    PRIMARY KEY(StudentID, SectionID)
);

CREATE TABLE Note (
    GraderID int NOT NULL,
    LabID int NOT NULL,
    Text text,
    TimeStamp datetime,
    FOREIGN KEY(GraderID) REFERENCES Grader(ID),
    FOREIGN KEY(LabID) REFERENCES Lab(ID),
    PRIMARY KEY(GraderID, LabID)
);

CREATE TABLE Grade (
    StudentID int NOT NULL,
    GraderID int NOT NULL,
    LabID int NOT NULL,
    Grade int NOT NULL,
    TimeStamp datetime,
    FOREIGN KEY(StudentID) REFERENCES Student(ID),
    FOREIGN KEY(GraderID) REFERENCES Grader(ID),
    FOREIGN KEY(LabID) REFERENCES Lab(ID),
    PRIMARY KEY(StudentID, GraderID, LabID)
);