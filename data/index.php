<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/plain');

print_r(scandir(__DIR__));