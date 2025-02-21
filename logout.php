<?php
session_start();
session_destroy();
header("Location: /komersial/auth/login.php");
exit();
?>
