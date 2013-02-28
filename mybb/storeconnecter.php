<?php

//Disallow direct Initialization for extra security.

if (!defined("IN_MYBB")) {
    die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

// Hooks
if($mybb->settings[''] == 1){
    $plugins->add_hook('global_start', 'storeconnecter_global_start');
}

$plugins->add_hook('newreply_do_newreply_end', 'storeconnecter_newreply_end');
$plugins->add_hook('newthread_do_newthread_end', 'storeconnecter_newthread_end');
$plugins->add_hook('polls_do_newpoll_end','storeconnecter_newpoll');
$plugins->add_hook('polls_vote_end','storeconnecter_vote_end');

// Information
function storeconnecter_info() {
    return array(
        "name" => "Store Connecter",
        "description" => "This plugin connects to alongubs SourceMod Store Plugin",
        "website" => "https://forums.alliedmods.net/forumdisplay.php?f=157",
        "author" => "Arrow768",
        "authorsite" => "http://sourcedonates.com",
        "version" => "0.1",
        "guid" => "",
        "compatibility" => "16*"
    );
}

// Activate
function storeconnecter_activate() {
    global $db;

    $storeconnecter_group = array(
        'gid' => 'NULL',
        'name' => 'storeconnecter',
        'title' => 'Store Connecter',
        'description' => 'Settings for the Store Connecter',
        'disporder' => "1",
        'isdefault' => "0",
    );
    $db->insert_query('settinggroups', $storeconnecter_group);
    $gid = $db->insert_id();

    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_enable',
        'title' => 'Do you want to enable Store Connecter?',
        'description' => 'If you set this option to yes, this plugin be active on your board.',
        'optionscode' => 'yesno',
        'value' => '1',
        'disporder' => 1,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_steamidrow',
        'title' => 'Name of the SteamID row in the Users table',
        'description' => 'Enter the name of the SteamID row here',
        'optionscode' => 'text',
        'value' => 'fid1',
        'disporder' => 2,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_postcredits',
        'title' => 'Credits a user gets for a post',
        'description' => 'The number of credits a user gets for a post',
        'optionscode' => 'text',
        'value' => '1',
        'disporder' => 3,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_threadcredits',
        'title' => 'Credits a user gets for a thread',
        'description' => 'The number of credits a user gets for a thread',
        'optionscode' => 'text',
        'value' => '1',
        'disporder' => 4,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid'=>'NULL',
        'name'=>'storeconnecter_votecredits',
        'title' => 'Credits a user gets for a vote',
        'description' => 'The number of credits a user gets for a vote',
        'optionscode' => 'text',
        'value' => '1',
        'disporder' => 5,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid'=>'NULL',
        'name'=>'storeconnecter_pollcredits',
        'title' => 'Credits a user gets for a Poll',
        'description' => 'The number of credits a user gets for a poll',
        'optionscode' => 'text',
        'value' => '1',
        'disporder' => 6,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_dbhost',
        'title' => 'Store Database Host',
        'description' => 'The Host of the Store Database',
        'optionscode' => 'text',
        'value' => 'localhost',
        'disporder' => 7,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_dbuser',
        'title' => 'Store Database User',
        'description' => 'User of the Store Database',
        'optionscode' => 'text',
        'value' => 'user',
        'disporder' => 8,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_dbpass',
        'title' => 'Store Database Password',
        'description' => 'Password of the Store Database',
        'optionscode' => 'text',
        'value' => 'password',
        'disporder' => 9,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    
    $storeconnecter_setting = array(
        'sid' => 'NULL',
        'name' => 'storeconnecter_dbname',
        'title' => 'Store Database Name',
        'description' => 'Name of the Store Database',
        'optionscode' => 'text',
        'value' => 'store',
        'disporder' => 10,
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $storeconnecter_setting);
    rebuild_settings();
}

// Deactivate
function storeconnecter_deactivate() {
    global $db;
    $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('storeconnecter_enable','storeconnecter_steamidrow','storeconnecter_postcredits','storeconnecter_threadcredits','storeconnecter_dbhost','storeconnecter_dbuser','storeconnecter_dbpass','storeconnecter_dbname','storeconnecter_votecredits','storeconnecter_pollcredits')");
    $db->query("DELETE FROM " . TABLE_PREFIX . "settinggroups WHERE name='storeconnecter'");
    rebuild_settings();
}



function storeconnecter_global_start() {
    global $mybb, $db;

    if ($mybb->settings['storeconnecter_enable'] == 1) {
              
        //get the authid
        $auth = steamid_to_auth($mybb->user[$mybb->settings['storeconnecter_steamidrow']]);
        echo "auth:".$auth."  ";
        
        $credits = get_storecredits();
        echo "credits:". $credits . "   ";
    }
}

function storeconnecter_newreply_end(){
    global $mybb, $db;
    
    if ($mybb->settings['storeconnecter_enable'] == 1) {
        
        $credits = get_storecredits($mybb->user[$mybb->settings['storecredits_steamidrow']]);
        $steamid = $mybb->user[$mybb->settings['storeconnecter_steamidrow']];
        //get the credits
        $new_credits = $credits + $mybb->settings['storeconnecter_postcredits'];
        
        update_storecredits($new_credits);
    }
}

function storeconnecter_newthread_end(){
    global $mybb, $db;
    
    if ($mybb->settings['storeconnecter_enable'] == 1) {
        
        $credits = get_storecredits($mybb->user[$mybb->settings['storecredits_steamidrow']]);
        $steamid = $mybb->user[$mybb->settings['storeconnecter_steamidrow']];
        //get the credits
        $new_credits = $credits + $mybb->settings['storeconnecter_threadcredits'];
        
        update_storecredits($new_credits);
    }
}

function storeconnecter_newpoll(){
    global $mybb, $db;
    
    if ($mybb->settings['storeconnecter_enable'] == 1) {
        
        $credits = get_storecredits($mybb->user[$mybb->settings['storecredits_steamidrow']]);
        $steamid = $mybb->user[$mybb->settings['storeconnecter_steamidrow']];
        //get the credits
        $new_credits = $credits + $mybb->settings['storeconnecter_pollcredits'];
        
        update_storecredits($new_credits);
    }
}

function storeconnecter_vote_end(){
    global $mybb, $db;
    
    if ($mybb->settings['storeconnecter_enable'] == 1) {
        
        $credits = get_storecredits($mybb->user[$mybb->settings['storecredits_steamidrow']]);
        $steamid = $mybb->user[$mybb->settings['storeconnecter_steamidrow']];
        //get the credits
        $new_credits = $credits + $mybb->settings['storeconnecter_votecredits'];
        
        update_storecredits($new_credits);
    }
}


function steamid_to_auth($steamid){
    //from https://forums.alliedmods.net/showpost.php?p=1890083&postcount=234
    $toks = explode(":", $steamid);
    $odd = (int)$toks[1];
    $halfAID = (int)$toks[2];
    
    return ($halfAID*2) + $odd;
}

function get_storecredits(){
    global $mybb;
    $auth = steamid_to_auth($mybb->user[$mybb->settings['storeconnecter_steamidrow']]);
    
    //connect to a external db
    $host = $mybb->settings['storeconnecter_dbhost'];
    $dbname = $mybb->settings['storeconnecter_dbname'];
    $user = $mybb->settings['storeconnecter_dbuser'];
    $pass = $mybb->settings['storeconnecter_dbpass'];

    try{
        //connect to the DB
        $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $STH = $DBH->prepare("SELECT * FROM store_users WHERE auth = ?");
        if($STH->execute(array($auth))){
            $i = 0;
            $store_credits = 0;
            while($row = $STH->fetch()){
                $i++;
                $store_credits = $row["credits"];
            }
        }

        if($i = 1){
            return $store_credits;
        }else{
            return 0;
        }

    }catch(PDOException $e){
        echo $e;
    }
}

function update_storecredits($new_credits){
    global $mybb;
    $auth = steamid_to_auth($mybb->user[$mybb->settings['storeconnecter_steamidrow']]);
    
    //connect to a external db
    $host = $mybb->settings['storeconnecter_dbhost'];
    $dbname = $mybb->settings['storeconnecter_dbname'];
    $user = $mybb->settings['storeconnecter_dbuser'];
    $pass = $mybb->settings['storeconnecter_dbpass'];
    
    try{
        //connect to the DB
        $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "UPDATE store_users SET credits=:credits WHERE auth=:auth";
        
        $STH = $DBH->prepare($sql);
        $STH->execute(array(
            ':credits'=>$new_credits,
            ':auth'=>$auth,
        ));
    }catch(PDOException $e){
        echo $e;
    }
}
?>