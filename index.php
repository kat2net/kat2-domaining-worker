<?php
$dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists('/app/d/lock')){
    echo 'Started';
}else{
    echo 'Not Started';
}