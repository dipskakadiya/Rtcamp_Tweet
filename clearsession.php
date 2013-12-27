<?php

session_start();
/* Clear the current sessions */
session_destroy();

/* Redirect user to the index page */
header('Location: index.php');
?>