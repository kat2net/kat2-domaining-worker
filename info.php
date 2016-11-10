<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$version = '0.0.6';

if(
    (file_exists('/app/data/lock'))
    &&
    (file_exists('/app/data/list_id'))
    &&
    (file_exists('/app/data/domains_done'))
    &&
    (file_exists('/app/data/domains_total'))
){
    $id = file_get_contents('/app/data/list_id');
    $domains_done = file_get_contents('/app/data/domains_done');
    $domains_total = file_get_contents('/app/data/domains_total');

    $id = (int)$id;
    $domains_done = (int)$domains_done;
    $domains_total = (int)$domains_total;
    $domains_left = (int)$domains_total - $domains_done;

    $info = array(
            'v' => $version,
            'name' => getenv('name'),
            'list' => array(
                'active' => true,
                'id' => $id,
                'domains_done' => $domains_done,
                'domains_left' => $domains_left,
                'domains_total' => $domains_total,
                'percentage' => number_format(($domains_done / $domains_total) * 100, 4)
            )
        );
}else if(
    (file_exists('/app/data/lock'))
    &&
    (
        (!file_exists('/app/data/list_id'))
        ||
        (!file_exists('/app/data/domains_done'))
        ||
        (!file_exists('/app/data/domains_total'))
    )
){
    $info = array(
        'v' => $version,
        'name' => getenv('name'),
        'list' => array(
            'active' => true,
            'id' => 0,
            'domains_done' => 0,
            'domains_left' => 0,
            'domains_total' => 0,
            'percentage' => 0
        )
    );
}else{
    $info = array(
        'v' => $version,
        'name' => getenv('name'),
        'list' => array(
            'active' => false,
            'id' => 0,
            'domains_done' => 0,
            'domains_left' => 0,
            'domains_total' => 0,
            'percentage' => 0
        )
    );
}

echo json_encode($info, JSON_PRETTY_PRINT);