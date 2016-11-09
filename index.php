<?php
$dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists('/app/d/lock')){
    echo 'It\'s Locked.';
}else{
    echo 'Make lock and start.';

    file_put_contents('/app/d/lock', time());

    exec('php /app/bg.php > /app/d/output &');
}