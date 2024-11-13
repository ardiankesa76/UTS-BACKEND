CREATE DATABASE ardiankesa1; 

USE ardiankesa1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nilai INT(11) DEFAULT 0
);
