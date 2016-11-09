<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$worker_name = getenv('worker_name');

$list_active = 'no';
$list_id = '';
$list_domains_done = '';
$list_domains_total = '';
$list_domains_left = '';
$list_percentage = '';

if(file_exists('/app/d/lock')){
    $data = file('/app/d/output');
    $last_line = $data[count($data)-2];
    $last_line = explode('|', $last_line);
    $last_line = explode(':', $last_line[0]);

    $list_active = 'yes';
    $list_id = $last_line[2];
    $list_domains_done = $last_line[0];
    $list_domains_total = $last_line[1];
    $list_domains_left = $list_domains_total - $list_domains_done;
    $list_percentage = ($list_domains_done / $list_domains_left) * 100;

    $last_line = explode('|', $last_line);
    $last_line = explode(':', $last_line[0]);
}

$array = array(
    'v' => 1,
    'worker_name' => $worker_name,
    'list' => array(
        'id' => $list_id,
        'active' => $list_active,
        'domains_done' => $list_domains_done,
        'domains_left' => $list_domains_left,
        'domains_total' => $list_domains_total,
        'percentage' => $list_percentage
    )
);

echo json_encode($array, JSON_PRETTY_PRINT);