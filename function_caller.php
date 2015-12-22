<?php
require($_SERVER['DOCUMENT_ROOT'].'/crm-native/includes/config.php');
$action = $_POST['action'];

if ($action == 'addnewcompany'){
    $company = $_POST['company'];
    if(addnewcompany($_POST['company'],$_POST['industry'],$_POST['ctelephone'],$_POST['caddress'],$_POST['cemail'],$_POST['cdesc'])){
        $sql = mysql_query('SELECT company_name FROM company_data WHERE company_name = "'.$company.'"');
        if(mysql_num_rows($sql)>0){
            //1 means company successfully added
            echo 1;
        }else{
            //if this 0 then company failed to add and not found
            echo 0;
        }
    }
    else{
        //if 0 then fail
        echo 0;
    }
}
else if($action == 'addcontact'){
    $contactname = $_POST['name'];
    if(addContact($_POST['name'], $_POST['tele'], $_POST['company'], $_POST['email'], $_POST['owner'])){
        $sql = mysql_query('SELECT name from contact_data WHERE name="'.$contactname.'"');
        if(mysql_num_rows($sql)>0){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    else{
        echo 2;
    }
}
else if($action == 'editcontact'){
    $contactname = $_POST['name'];
    if(editContact($_POST['cid'], $_POST['name'], $_POST['tele'], $_POST['email'], $_POST['status'])){
        $sql = mysql_query('SELECT name from contact_data WHERE name="'.$contactname.'"');
        if(mysql_num_rows($sql)>0){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    else{
        echo 2;
    }
}
else if($action == 'addprospect'){
    $contact = $_POST['contact'];
    if(addProspect($_POST['contact'], $_POST['value'], $_POST['source'], $_POST['expi'], $_POST['owner'])){
        $sql = mysql_query('SELECT name from contact_data WHERE name="'.$contact.'"');
        if(mysql_num_rows($sql)>0){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    else{
        echo 2;
    }
}
else if($action == 'editprospect'){
    if(editProspect($_POST['pid'], $_POST['source'], $_POST['value'], $_POST['expi'], $_POST['status'])){
        echo 1;
    }
    else{
        echo 0;
    }
}
else if($action == 'addleads'){
    $sql = mysql_query("SELECT * FROM prospect_data WHERE `Prospect_ID`='.$_POST[pid].'");
    $data = mysql_fetch_assoc($sql);
    if ($data['Status']=='New'){
        echo 3;
    }
    else {
        if (addleads($_POST['pid'], $_POST['value'], $_POST['expi'], $_POST['creator'])) {
            echo 1;
        }
        else {
            echo 2;
        }
    }
}
else if($action == 'editleads'){
    if(editLeads($_POST['lid'], $_POST['source'], $_POST['value'], $_POST['expi'], $_POST['status'])){
        echo 1;
    }
    else{
        echo 0;
    }
}
else if($action == 'addaccount'){
    if(addAccount($_POST['lid'], $_POST['value'], $_POST['type'], $_POST['creator'])){
        echo 1;
    }
    else{
        echo 0;
    }
}

else if($action == 'editaccount'){
    if(editAccount($_POST['aid'], $_POST['value'], $_POST['type'], $_POST['source'], $_POST['status'])){
        echo 1;
    }
    else{
        echo 0;
    }
}

else if($action == 'addproject'){
    if(addProject($_POST['aid'], $_POST['project'], $_POST['value'], $_POST['type'], $_POST['detail'], $_POST['date'], $_POST['creator'])){
        echo 1;
    }
    else{
        echo 0;
    }
}

else if($action == 'editproject'){
    if(editProject($_POST['prid'], $_POST['name'], $_POST['value'], $_POST['type'], $_POST['detail'],$_POST['source'], $_POST['date'], $_POST['status'])){
        echo 1;
    }
    else{
        echo 0;
    }
}
else if($action == 'sendemail') {
    $sql = mysql_query('SELECT * from contact_data WHERE name="'.$_POST['emailto'].'"');
    $data=mysql_fetch_assoc($sql);
        $to = $data['Email'];
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->Username = 'dwiasa12@gmail.com';
        $mail->Password = 'prakerin123';
        $mail->setFrom('dwiasa1@gmail.com');
        $mail->addAddress($to);
        $mail->Subject = $_POST['subject'];
        $mail->Body = $_POST['emailbody'];
        $mail->SMTPDebug = 0;
//send the message, check for errors
        if (!$mail->send()) {
            echo 0;
        } else {
            $sqlx = "INSERT INTO `email_history` (`Related_ID`, `Type`, `Subject`, `Body`, `Email`)
VALUES ('$data[Contact_ID]','Contact', '$_POST[subject]', '$_POST[emailbody]', '$to')";
            $queryx = mysql_query($sqlx);
            echo 0;
        }

}
else {
    echo 2;
}
