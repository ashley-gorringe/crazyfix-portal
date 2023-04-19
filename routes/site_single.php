<?php
$site = $GLOBALS['database']->get('site','*',['site_slug'=>$slug]);
$site['created_datetime_timeago'] = timeago(strtotime($site['created_datetime']));
$site['updated_datetime_timeago'] = timeago(strtotime($site['updated_datetime']));
$site['created_datetime'] = date('jS \o\f M Y - G:i:s',strtotime($site['created_datetime']));
$site['updated_datetime'] = date('jS \o\f M Y - G:i:s',strtotime($site['updated_datetime']));

$server = $GLOBALS['database']->get('server','*',['server_id'=>$site['server_id']]);
$server['site_count'] = $GLOBALS['database']->count('site',['server_id'=>$server['server_id']]);

if($site['client_id']){
    $client = $GLOBALS['database']->get('client','*',['client_id'=>$site['client_id']]);
}else{
    $client = false;
}

$domains = $GLOBALS['database']->select('domain_site',[
    '[>]domain'=>['domain_id'=>'domain_id'],
],'*',[
    'site_id'=>$site['site_id'],
]);
foreach($domains as $key => $domain){
    if(str_contains($domain['dns_ns'],'cloudflare.com')){
        $privacy = true;
    }else{
        $privacy = false;
    }
    $domains[$key]['issue_privacy'] = $privacy;
    if($domain['issue_dns'] || $privacy){
        $domains[$key]['issues'] = true;
    }else{
        $domains[$key]['issues'] = false;
    }
}

echo $GLOBALS['twig']->render('site_single.twig',[
    'user'=>$_SESSION['user'],
    'team'=>$_SESSION['team'],
    'site'=>$site,
    'server'=>$server,
    'client'=>$client,
    'domains'=>$domains,
]);
?>