<?php
const ACCESS_ALLOWED = true;

// Start the session before unsetting and destroying it
session_start();
session_unset();
session_destroy();

// Redirect to the index page
header("Location: ./index.php");
exit();