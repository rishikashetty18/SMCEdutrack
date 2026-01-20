<?php
function logAction($con,$action){
  $uid=$_SESSION['admin_id']??0;
  $role=$_SESSION['status']??'guest';
  $stmt=$con->prepare(
    "INSERT INTO activity_logs(user_id,role,action) VALUES(?,?,?)"
  );
  $stmt->bind_param("iss",$uid,$role,$action);
  $stmt->execute();
}
