-- Create database
CREATE DATABASE IF NOT EXISTS student_results_db;

-- Use the database
USE student_results_db;

-- Drop table if exists to recreate with new structure
DROP TABLE IF EXISTS students_results;

-- Create table
CREATE TABLE students_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    register_no VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    class VARCHAR(50) NOT NULL,
    sub1_name VARCHAR(100) NULL,
    sub1_mark DECIMAL(5,2) NULL,
    sub2_name VARCHAR(100) NULL,
    sub2_mark DECIMAL(5,2) NULL,
    sub3_name VARCHAR(100) NULL,
    sub3_mark DECIMAL(5,2) NULL,
    sub4_name VARCHAR(100) NULL,
    sub4_mark DECIMAL(5,2) NULL,
    sub5_name VARCHAR(100) NULL,
    sub5_mark DECIMAL(5,2) NULL,
    sub6_name VARCHAR(100) NULL,
    sub6_mark DECIMAL(5,2) NULL,
    sub7_name VARCHAR(100) NULL,
    sub7_mark DECIMAL(5,2) NULL,
    sub8_name VARCHAR(100) NULL,
    sub8_mark DECIMAL(5,2) NULL,
    sub9_name VARCHAR(100) NULL,
    sub9_mark DECIMAL(5,2) NULL,
    sub10_name VARCHAR(100) NULL,
    sub10_mark DECIMAL(5,2) NULL,
    total DECIMAL(5,2) NULL,
    result ENUM('PASS', 'FAIL') NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);