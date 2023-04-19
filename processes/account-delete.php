<?php
$members_count = $GLOBALS['database']->count('user',['team_id'=>$_SESSION['team_id']]);
$user_level = $GLOBALS['database']->get('user','level',['user_id'=>$_SESSION['user_id']]);

if($user_level == 0){
    //User is the team owner
    if($members_count > 1){
        $response->status = 'error';
        $response->message = 'You must transfer ownership to another member before deleting your account.';
        echo json_encode($response);
        exit;
    }else{
        $GLOBALS['database']->delete('team',['team_id'=>$_SESSION['team_id']]);
        $response->status = 'success';
        echo json_encode($response);
        exit;
    }
}else{
    $GLOBALS['database']->delete('user',['user_id'=>$_SESSION['user_id']]);
    $response->status = 'success';
    echo json_encode($response);
    exit;
}
?>