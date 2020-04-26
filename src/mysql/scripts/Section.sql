delete from Section where true;

alter table Section AUTO_INCREMENT = 0;

insert into Section (ClassID, SectionNum, IsActive) values (1, 2, false);
insert into Section (ClassID, SectionNum, IsActive) values (2, 3, false);
insert into Section (ClassID, SectionNum, IsActive) values (3, 1, false);
insert into Section (ClassID, SectionNum, IsActive) values (4, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (5, 3, false);
insert into Section (ClassID, SectionNum, IsActive) values (6, 2, false);
insert into Section (ClassID, SectionNum, IsActive) values (7, 1, false);
insert into Section (ClassID, SectionNum, IsActive) values (8, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (9, 1, true);
insert into Section (ClassID, SectionNum, IsActive) values (10, 2, false);
insert into Section (ClassID, SectionNum, IsActive) values (11, 1, true);
insert into Section (ClassID, SectionNum, IsActive) values (12, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (13, 4, true);
insert into Section (ClassID, SectionNum, IsActive) values (14, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (15, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (16, 3, true);
insert into Section (ClassID, SectionNum, IsActive) values (17, 4, true);
insert into Section (ClassID, SectionNum, IsActive) values (18, 1, false);
insert into Section (ClassID, SectionNum, IsActive) values (19, 2, false);
insert into Section (ClassID, SectionNum, IsActive) values (20, 3, true);
insert into Section (ClassID, SectionNum, IsActive) values (21, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (22, 3, false);
insert into Section (ClassID, SectionNum, IsActive) values (23, 2, false);
insert into Section (ClassID, SectionNum, IsActive) values (24, 1, false);
insert into Section (ClassID, SectionNum, IsActive) values (25, 1, true);
insert into Section (ClassID, SectionNum, IsActive) values (26, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (27, 4, true);
insert into Section (ClassID, SectionNum, IsActive) values (28, 1, true);
insert into Section (ClassID, SectionNum, IsActive) values (29, 4, false);
insert into Section (ClassID, SectionNum, IsActive) values (30, 3, true);

commit;
