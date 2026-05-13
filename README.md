# Student Management System

A full-stack web application built with PHP and MySQL that allows 
admins to manage student records through a secure dashboard.

## Live Demo

[View Live Site](https://mikeldiangelo.infinityfree.me/login.php)

## Features

- Secure admin authentication with password hashing
- Register, edit, and delete student records
- Search students by name using JOIN queries
- Paginated student dashboard (5 records per page)
- Student photo upload with file validation
- Registration activity logs stored in text files
- Session-based access control on all pages
- Admin dashboard with total student count

## Tech Stack

- PHP (OOP)
- MySQL
- Bootstrap 5
- HTML & CSS
- Basic JavaScript

## Database Setup

Create a database called `student_db` and run the following SQL:

CREATE TABLE students (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(75) NOT NULL UNIQUE,
    course VARCHAR(50) NOT NULL,
    admin_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL
);

## Installation

1. Clone the repository
2. Move files to your XAMPP htdocs folder
3. Create the database using the SQL above
4. Update config.php with your database credentials
5. Visit localhost/login.php in your browser

## Security

- Passwords hashed using PHP password_hash()
- All database queries use prepared statements
- Session authentication on every protected page
- File upload validation (type, size, format)
