<?php
$server="localhost";
 $username="root";
$password= "";
$db="sample";
$con=mysqli_connect($server,$username,$password,$db);
if(!$con)
{
    die("Error");
}
if (session_status() === PHP_SESSION_NONE) 
    {session_start();
        if(!isset($_SESSION['status']))$_SESSION['status']='';
        if(!isset($_SESSION['username']))$_SESSION['username']='';}
