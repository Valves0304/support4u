<?php
// system variables

    //starts a session to this variables
    session_start();

    // adjustin system time
     date_default_timezone_set('America/Vancouver');

    // System Version
    putenv("VER=1.0");
    putenv("MANUT=OFF");

    // errors
    // ini_set('display_errors', '0');     # don't show errors...
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

    // enviroment
   // putenv("AMB=DES");          // development
    putenv("AMB=PRD");        // production

    // Log file - Directory needs 776 permission
    putenv("LOG_FILE=" . __DIR__ . "/log/" . date('Ymd') . '.log');
    file_put_contents(getenv('LOG_FILE'),'',FILE_APPEND);  // starts the log file
