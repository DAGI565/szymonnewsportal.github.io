<?php 
session_start();
unset($_SESSION['user']);
unset($_SESSION['zdj']);
unset($_SESSION['perm']);
session_destroy();
header("Location: index.php");