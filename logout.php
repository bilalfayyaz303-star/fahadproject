<?php

// Session start karo agar pehle se start nahi hai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// User ki saari session values remove karo
$_SESSION = array();

// Session destroy karo
session_destroy();

// Logout ke baad main website par bhejo
header("Location: index.php");
exit();

?>