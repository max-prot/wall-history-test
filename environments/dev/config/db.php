<?php

return [
    'class' => 'yii\db\Connection',

    'dsn' => 'mysql:host=wall_history_mysql;dbname=wall_history',
    'username' => 'wall_history',
    'password' => 'wall_history',

    /*  Please use the configurations below instead of the above configurations when using OpenServer.
    'dsn' => 'mysql:host=localhost;dbname=wallhistory',
    'username' => 'root',
    'password' => 'root',
    */

    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
