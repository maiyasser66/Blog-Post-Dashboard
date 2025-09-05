<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'blog_post_system';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
} catch (mysqli_sql_exception) {
    echo 'SERVER ERROR!';
    die;
}