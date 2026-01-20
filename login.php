<?php
include ("connection.php");
$username=$_POST['username'];

$password=$_POST['password'];
$db="sample";

$query="SELECT * FROM `regform` WHERE username='$username' and password='$password' ";

$res=$con->query($query);

if($res->num_rows >= 1)
{
   $_SESSION['status'] = 'admin';
$_SESSION['admin_id'] = $row['id'];   // VERY IMPORTANT
$_SESSION['admin_name'] = $row['name'];

    Header("location:admin.php");
}
else{
    echo "<script>alert('You are not a valid login');
    </script>";
}
// $query="SELECT * FROM `regform` WHERE username='$username' and password='$password';";

// $res = $con->query($query);

// if($res->num_rows>= 1)
// {
// Header("location:admin.html");
// }
// else
// {
//     echo "<script>alert('Invalid login');window.location.href='login.html'</script>";
// }