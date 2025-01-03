<?php // createTables.php
require_once "../databaseManagement/queryFunctions.php";

// create an associate of array with table names and their column data
$tables = [
    "result2" => "(id SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY, userName VARCHAR(32) NOT NULL, subject VARCHAR(32) NOT NULL, test FLOAT, exam FLOAT, total FLOAT, average FLOAT, position INT NOT NULL, remark TEXT, term VARCHAR(20) NOT NULL, session VARCHAR(20) NOT NULL, create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "signup" => "(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, userName VARCHAR(32) NOT NULL UNIQUE, email VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, password VARCHAR(256) NOT NULL, reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "temp_signup" => "(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, userName VARCHAR(32) NOT NULL UNIQUE, email VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, password VARCHAR(256) NOT NULL, reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "profile" => "(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, firstName VARCHAR(32) NOT NULL, lastName VARCHAR(32) NOT NULL, userName VARCHAR(32) NOT NULL UNIQUE, gender VARCHAR (10) NOT NULL, date VARCHAR(10) NOT NULL, phone VARCHAR (20) NOT NULL, email VARCHAR(100) NOT NULL, class VARCHAR(20), type VARCHAR(20) NOT NULL, picture VARCHAR(255))",
    "posts" => "(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, post TEXT NOT NULL, userName VARCHAR(32) NOT NULL, picture VARCHAR(100), created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "news" => "(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, title TEXT(250) NOT NULL, content TEXT NOT NULL, userName VARCHAR(32) NOT NULL, picture VARCHAR(100), created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "events" => "(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, title VARCHAR(256) NOT NULL, content TEXT NOT NULL, status ENUM('upcoming', 'recent') NOT NULL, event_date DATETIME NOT NULL, post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "resources" => "(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, title VARCHAR(256) NOT NULL, description TEXT NOT NULL, class VARCHAR(20) NOT NULL, path VARCHAR(256) NOT NULL, create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "assignments" => "(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, subject VARCHAR(40) NOT NULL, title VARCHAR(40) NOT NULL, class VARCHAR(20) NOT NULL, assignment TEXT, path TEXT, create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)",
    "notifications" => "(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, title VARCHAR (256) NOT NULL, content VARCHAR (512) NOT NULL, status ENUM('read', 'unread') NOT NULL, userName VARCHAR(60) NOT NULL, create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)"
];

// call the function that creates new tables
createDataTables($tables, $pdo);

// create a function that creates tables with the information  passed to it's parameter
function createDataTables($table_data, $pdo) {
    foreach ($table_data as $key => $value) {
        sqlFunctions("CREATE TABLE {$key} {$value}", [], "{$key} table created successfully.", "Unable to create {$key}. Please try again later.", $pdo);
    }
}
?>