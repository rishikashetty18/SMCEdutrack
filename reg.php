<?php
$server="localhost";
$user="root";
$pass="";
$db=
$con=mysqli_connect($server,$user,$pass);

if(!$con)
{
    die("");
}
echo "Connection established";