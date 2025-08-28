<?php
//user logout
session_start();
session_destroy();
header('Location: ../index.html');
exit();
?>