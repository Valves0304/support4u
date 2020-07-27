<?php
// system variables

    //starts a session to this variables
    session_start();

    // adjustin system time
   //  date_default_timezone_set('America/Vancouver');

    // System Version
    putenv("VER=0.10");
    putenv("MANUT=OFF");

    // errors
    // ini_set('display_errors', '0');     # don't show errors...
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

    // enviroment
   // putenv("AMB=DES");          // ambiente de desenvolvimento
    putenv("AMB=PRD");        // ambiente de produção

    // Log file - Directory needs 776 permission
    putenv("LOG_FILE=" . __DIR__ . "/log/" . date('Ymd') . '.log');
    file_put_contents(getenv('LOG_FILE'),'',FILE_APPEND);  // garante a criação do arquivo, se não existir

    // habilita mensagens do kint (debugger tool)
    //    if (getenv('AMB') == "DES") {
    //        Kint::enabled(true);
    //    } else {
    //        Kint::enabled(false);
    //    }

    // variáveis do banco de dados
//    if (getenv('AMB') == "DES") {
//        putenv("DB_HOST" . getenv("IP"));
//        putenv("DB_USER=u426703890_boil");
//        putenv("DB_PASS=boiler");
//        putenv("DB_NAME=c9");
//        putenv("DB_PORT=3306");
//    } else {
//        putenv("DB_HOST=mysql.hostinger.com.br");
//        putenv("DB_USER=u426703890_boil");
//        putenv("DB_PASS=7Zt-YUW-Baq-Ymr");
//        putenv("DB_NAME=u426703890_boil");
//        putenv("DB_PORT=3306");
//    }
