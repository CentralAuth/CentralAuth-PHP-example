<?php
session_start();

// Clear all session data
session_unset();
session_destroy();

//Consider logging out session wide by calling the CentralAuth logout endpoint
//See https://docs.centralauth.com/developer/logout for more information

// Redirect to homepage
header('Location: /');
exit;
