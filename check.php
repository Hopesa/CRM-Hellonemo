<?php
require($_SERVER['DOCUMENT_ROOT'].'/crm-native/includes/config.php');
$action = $_POST['action'];

if ($action == 'company'){

        $company = $_POST['company'];
        $sql = mysql_query('SELECT company_name FROM company_data WHERE company_name = "'.$company.'"');
    if(mysql_num_rows($sql)>0){
        //and we send 1 to the ajax request
        echo 1;
    }else{
        //else if it's not bigger then 0, then it's available '
        //and we send 0 to the ajax request
       echo 2;
    }

}
if ($action == 'contact'){
    $contact = $_POST['contact'];
    $sql = mysql_query('SELECT name FROM contact_data WHERE name = "'.$contact.'"');
    if(mysql_num_rows($sql)>0){
        //and we send 1 to the ajax request
        echo 1;
    }else{
        //else if it's not bigger then 0, then it's available '
        //and we send 0 to the ajax request
        echo 0;
    }
}
if ($action == 'prospect'){
    $contact = $_POST['prospect']; // Get Contact_ID
    $sql = mysql_query('SELECT * FROM contact_data WHERE name = "'.$contact.'"');
    $data = mysql_fetch_assoc($sql);
    $sqlx = mysql_query('SELECT * FROM prospect_data WHERE contact_ID = "'.$data['Contact_ID'].'"'); // Check Associated Prospect
    if(mysql_num_rows($sqlx)>0){
        //and we send 1 to the ajax request
        echo 1;
    }else{
        //else if it's not bigger then 0, then it's available '
        //and we send 0 to the ajax request
        echo 0;
    }
}

if ($action == 'leads'){
    $contact = $_POST['leads']; // Get Contact_ID
    $sql = mysql_query('SELECT * FROM contact_data WHERE name = "'.$contact.'"');
    $data = mysql_fetch_assoc($sql);
    $sqlx = mysql_query('SELECT * FROM leads_data WHERE contact_ID = "'.$data['Contact_ID'].'"'); // Check Associated Prospect
    if(mysql_num_rows($sqlx)>0){
        //and we send 1 to the ajax request
        echo 1;
    }else{
        //else if it's not bigger then 0, then it's available '
        //and we send 0 to the ajax request
        echo 0;
    }
}
if ($action == 'account'){
    $contact = $_POST['account']; // Get Contact_ID
    $sql = mysql_query('SELECT * FROM contact_data WHERE name = "'.$contact.'"');
    $data = mysql_fetch_assoc($sql);
    $sqlx = mysql_query('SELECT * FROM account_data WHERE contact_ID = "'.$data['Contact_ID'].'"'); // Check Associated Prospect
    if(mysql_num_rows($sqlx)>0){
        //and we send 1 to the ajax request
        echo 1;
    }else{
        //else if it's not bigger then 0, then it's available '
        //and we send 0 to the ajax request
        echo 0;
    }
}
if(empty($action)){
    echo 0;
}