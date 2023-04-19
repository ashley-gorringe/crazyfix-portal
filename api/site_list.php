<?php
header('Content-Type: application/json');
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if($_GET['sort'] == 'updated-desc'){
    $order = [
        'updated_datetime'=>'DESC',
    ];
}elseif($_GET['sort'] == 'updated-asc'){
    $order = [
        'updated_datetime'=>'ASC',
    ];
}elseif($_GET['sort'] == 'created-desc'){
    $order = [
        'created_datetime'=>'DESC',
    ];
}elseif($_GET['sort'] == 'created-asc'){
    $order = [
        'created_datetime'=>'ASC',
    ];
}elseif($_GET['sort'] == 'name-desc'){
    $order = [
        'site_name'=>'DESC',
    ];
}elseif($_GET['sort'] == 'name-asc'){
    $order = [
        'site_name'=>'ASC',
    ];
}else{
    $order = [
        'updated_datetime'=>'DESC',
    ];
}


$where = [
    'ORDER'=>$order,
    'site.team_id'=>$_SESSION['team_id'],
];
if($_GET['context'] == 'home'){
    $where = [
        'ORDER'=>[
            'updated_datetime'=>'DESC',
        ],
        'site.team_id'=>$_SESSION['team_id'],
        'LIMIT'=>5,
    ];
}

if(isset($_GET['server'])){
    $server_slug = $_GET['server'];
    $server_count = $GLOBALS['database']->count('server',[
        'AND'=>[
            'server_slug'=>$server_slug,
            'team_id'=>$_SESSION['team_id'],
        ],
    ]);
    if($server_count < 1){
        $response->status = 'error';
        $response->message = 'An error has occured. Please reload the page and try again.';
        echo json_encode($response);
        exit;
    }else{
        $server_id = $GLOBALS['database']->get('server','server_id',['server_slug'=>$server_slug]);
        $where = [
            'ORDER'=>$order,
            'AND'=>[
                'site.server_id'=>$server_id,
                'site.team_id'=>$_SESSION['team_id'],
            ]
        ];
    }
}

$sites = $GLOBALS['database']->select('site',[
    '[>]server'=>['server_id'=>'server_id'],
    '[>]client'=>['client_id'=>'client_id'],
],'*',$where);

foreach ($sites as $key => $site){
    if(empty($site['client_id'])){
        $sites[$key]['client_name'] = '-';
    }
    $sites[$key]['created_timeago'] = timeago(strtotime($site['created_datetime']));
    $sites[$key]['updated_timeago'] = timeago(strtotime($site['updated_datetime']));
}

echo json_encode($sites);
exit;
?>