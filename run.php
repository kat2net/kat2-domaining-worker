<?php
$dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists('/app/d/lock')){
    echo 'It\'s Locked.';
}else{
    file_put_contents('/app/d/lock', time());
    exec('php /app/bg.php > /app/d/output &');
}