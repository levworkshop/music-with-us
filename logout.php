<?php

// if there are any session cookies - delete them

session_start();
session_destroy();
header('location: login.php');
