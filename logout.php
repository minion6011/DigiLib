<?php
    require_once "main.php"; // required for cookies / session_start

    unset($_SESSION['user_id']);
    unset($_SESSION['isAdmin']);

    header("Location: index.php");
?>