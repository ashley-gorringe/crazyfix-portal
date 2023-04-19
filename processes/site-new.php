<?php
$submitValid = true;
$errorFields = array();

if(empty($_POST['domain'])){
    $errorFields[] = array(
        'field_name'=>'domain',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validateDomain($_POST['domain'])){
        $domain_name = $_POST['domain'];
    }else{
        $errorFields[] = array(
            'field_name'=>'domain',
            'error_message'=>"This is invalid. Please enter a valid domain. Don't include https://.",
        );
        $submitValid = false;
    }
}

if($_POST['domain_www'] == 'true'){
    $domain_www = true;
}else{
    $domain_www = false;
}

if(empty($_POST['server'])){
    $errorFields[] = array(
        'field_name'=>'server',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    $server_count = $GLOBALS['database']->count('server',[
        'AND'=>[
            'server_slug'=>$_POST['server'],
            'team_id'=>$_SESSION['team_id'],
        ],
    ]);
    if($server_count < 1){
        $response->status = 'error';
        $response->message = 'An error has occured. Please reload the page and try again.';
        echo json_encode($response);
        exit;
    }else{
        $server_slug = $_POST['server'];
    }
}
if(!empty($_POST['client_type'])){
    if($_POST['client_type'] !== 'create' && $_POST['client_type'] !== 'select'){
        $response->status = 'error';
        $response->message = 'An error has occured. Please reload the page and try again.';
        echo json_encode($response);
        exit;
    }
}
if($_POST['client_type'] === 'create'){
    $client_type = 'create';
    if(validBasicText($_POST['client_value'])){
        $client_name = $_POST['client_value'];
    }else{
        $response->message = 'Client Name is invalid. Please only use Letters, Numbers, and Hyphens.';
        $submitValid = false;
    }
}elseif($_POST['client_type'] === 'select'){
    $client_type = 'select';
    $client_count = $GLOBALS['database']->count('client',[
        'AND'=>[
            'client_slug'=>$_POST['client_value'],
            'team_id'=>$_SESSION['team_id'],
        ],
    ]);
    if($client_count < 1){
        $response->status = 'error';
        $response->message = 'An error has occured. Please reload the page and try again.';
        echo json_encode($response);
        exit;
    }else{
        $client_slug = $_POST['client_value'];
    }
}else{
    $client_type = false;
}

if(!$submitValid){
	$response->status = 'error';
	//$response->message = 'There are problems with your submission. Please check the form and try again.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}else{
    if($client_type === 'create'){
        $database->insert('client',[
            'client_name'=>$client_name,
            'team_id'=>$_SESSION['team_id'],
        ]);
        $client_slug = insertDBSlug('client');
    }

    $client_id = $database->get('client','client_id',['client_slug'=>$client_slug]);
    $server_id = $database->get('server','server_id',['server_slug'=>$server_slug]);

    $database->insert('site',[
        'site_name'=>$domain_name,
        'server_id'=>$server_id,
        'client_id'=>$client_id,
        'team_id'=>$_SESSION['team_id'],
    ]);
    $site_id = $database->id();
    $site_slug = insertDBSlug('site');

    addDomain($domain_name,$domain_www,$site_id);
    updateSitePrimaryDomain($site_id,$domain_name);

    /*

    $dns = getDNSRecords($domain);

    $database->insert('domain',[
        'domain_name'=>$domain,
        'dns_ns'=>$dns['dns_ns'],
        'dns_a'=>$dns['dns_a'],
        'team_id'=>$_SESSION['team_id'],
    ]);
    $domain_id = $database->id();
    insertDBSlug('domain');

    if($domain_www){
        $dns = getDNSRecords('www.'.$domain);
        $database->insert('domain',[
            'domain_name'=>'www.'.$domain,
            'dns_ns'=>$dns['dns_ns'],
            'dns_a'=>$dns['dns_a'],
            'team_id'=>$_SESSION['team_id'],
        ]);
        $domain_www_id = $database->id();
        insertDBSlug('domain');
    }

    $database->insert('site',[
        'site_name'=>$domain,
        'server_id'=>$server_id,
        'client_id'=>$client_id,
        'team_id'=>$_SESSION['team_id'],
        'primary_domain_id'=>$domain_id,
    ]);
    $site_id = $database->id();
    $site_slug = insertDBSlug('site');

    $database->insert('domain_site',[
        'domain_id'=>$domain_id,
        'site_id'=>$site_id,
    ]);
    $domain_site_id = $database->id();
    checkDomainSiteDNS($domain_site_id);
    if($domain_www){
        $database->insert('domain_site',[
            'domain_id'=>$domain_www_id,
            'site_id'=>$site_id,
        ]);
        $domain_site_id = $database->id();
        checkDomainSiteDNS($domain_site_id);
    }

    checkSiteDNS($site_id);
    */

    $response->status = 'success';
	$response->successRedirect = '/sites/'.$site_slug;
	echo json_encode($response);
	exit;
}
?>