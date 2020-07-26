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

//entities with attributes for Ineternet Movies Booking system
a. Login (Customer / Admin / Owner )   
    Customer to view movies and shows and book tickets. 
    Admin to add movies, add shows, update / delete shows
    Owner to add Screens, view summary reports
b. Profile   c. Movies   d. Screens   e. Shows   f. Bookings   

Create table Movies (
    movie_id int not null auto_increment,
    movie_name varchar (60) not null,
    movie_cast varchar(100),
    movie_director varchar(60),
    movie_img_fn varchar(20),
    movie_language varchar(15),
    movie_rel_date date,
    primary key(movie_id));







