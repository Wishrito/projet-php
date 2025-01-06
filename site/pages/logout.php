<?php
const ACCESS_ALLOWED = true;
session_unset();
session_destroy();
header("Location: ./index.php");
exit();
