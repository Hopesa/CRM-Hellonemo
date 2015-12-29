<?php
require($_SERVER['DOCUMENT_ROOT'].'/crm-native/includes/config.php');
$action = $_GET['action'];
$id = $_GET['id'];

if ($action == 'company'){
    $query = mysql_query('DELETE FROM company_data WHERE Company_ID = "'.$id.'"') or die (mysql_error());
    if($query){
        echo 1;
    }else{
        echo 0;
    }

}
if ($action == 'contact'){
    $query = mysql_query('DELETE FROM contact_data WHERE Contact_ID = "'.$id.'"') or die (mysql_error());
    if($query){
        echo 1;
    }else{
        echo 0;
    }
}
if ($action == 'prospect'){
    $query = mysql_query('DELETE FROM prospect_data WHERE Prospect_ID = "'.$id.'"') or die (mysql_error());
    if($query){
        echo 1;
    }else{
        echo 0;
    }
}

if ($action == 'leads'){
    $query = mysql_query('DELETE FROM leads_data WHERE Leads_ID = "'.$id.'"') or die (mysql_error());
    if($query){
        echo 1;
    }else{
        echo 0;
    }
}
if ($action == 'account'){
    $query = mysql_query('DELETE FROM account_data WHERE Account_ID = "'.$id.'"') or die (mysql_error());
    if($query){
        echo 1;
    }else{
        echo 0;
    }
}
if ($action == 'project'){
    $query = mysql_query('DELETE FROM project_data WHERE Project_ID = "'.$id.'"') or die (mysql_error());
    if($query){
        echo 1;
    }else{
        echo 0;
    }
}
if(empty($action)){
    echo 0;
    echo $action;
}