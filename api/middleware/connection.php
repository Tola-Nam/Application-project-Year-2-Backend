<?php

function connection()
{
    return new mysqli("Localhost", "root", "", "applicationprojectbackendruppy2");
}
if
(!connection()) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "Connected successfully";
    return connection();
}