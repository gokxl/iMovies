// members table in familydb

Create table members
(mem_id int not null auto_increment,
mem_name varchar(50) not null,
mem_gender char(1),
mem_dob date,
primary key(mem_id));


// Local user to control database
create user 'imovies'@'localhost' identified by 'Vit2020@project';
grant all privileges on *.* to 'imovies'@'localhost';




