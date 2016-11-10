<?php
header("Access-Control-Allow-Origin: *");
$dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists('/app/data/lock')){
    echo 'It\'s Locked.';
}else{
    file_put_contents('/app/data/lock', time());
    exec('php /app/worker.php > /app/data/output &');
}