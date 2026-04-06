<?php
$password = "password"; // your plain password
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>