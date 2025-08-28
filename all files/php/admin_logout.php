<?php
//admin logout
session_start();
$_SESSION = [];
session_destroy();
header('Location: ../admin/login.html');
exit();
