-- Project 1 Schema file
-- This file creates the databases needed for the project + sample students and courses
-- Referenced GitHub - ase230/module1/code/6_RestAPI/api/schema.sql

CREATE TABLE IF NOT EXISTS students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  major VARCHAR(100) NOT NULL,
  year TINYINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  department VARCHAR(100) NOT NULL,
  credits TINYINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO students (name, email, major, year)
VALUES
('John Example', 'johnexample@nku.edu', 'Computer Science', 1),
('Jane Example', 'janeexample@nku.edu', 'Cybersecurity', 2);

INSERT INTO courses (title, department, credits) 
VALUES
('Example Course', 'Cybersecurity', 3),
('Another Example', 'Computer Science', 2);


