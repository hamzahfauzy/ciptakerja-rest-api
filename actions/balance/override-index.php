<?php
$type  = $_GET['type'] ?? 'production';
$where = (empty($where) ? 'WHERE ' : ' AND ') . " user_type = '$type'";

$db->query = "SELECT * FROM $table $where ORDER BY ".$columns[$order[0]['column']]." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $db->exec('all');

$total = $db->exists($table,$where,[
    $columns[$order[0]['column']] => $order[0]['dir']
]);

return compact('data','total');