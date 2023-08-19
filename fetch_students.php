<?php
// Conexión a la base de datos y otras configuraciones
include("php/dbconnect.php");
include("php/checklogin.php");
if (isset($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    
    $query = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name FROM students WHERE CONCAT(first_name, ' ', last_name) LIKE '%$name%'";
    
    $result = mysqli_query($conn, $query);
    
    $students = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
    
    echo json_encode($students);
} else {
    echo json_encode(array());
}
?>