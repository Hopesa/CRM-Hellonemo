<?php
function createAccount($pUsername, $pPassword) { //User Account, Not that "Account"
    // First check we have data passed in.
    if (!empty($pUsername) && !empty($pPassword)) {

        // escape the $pUsername to avoid SQL Injections
        $eUsername = mysql_real_escape_string($pUsername);
        $sql = "SELECT username FROM user_data WHERE username = '" . $eUsername . "' LIMIT 1";
        // Note the use of trigger_error instead of or die.
        $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
        // Error checks (Should be explained with the error)
//        if ($uLen <= 4 || $uLen >= 11) {
//            $_SESSION['error'] = "Username must be between 4 and 11 characters.";
//        }elseif ($pLen < 6) {
//            $_SESSION['error'] = "Password must be longer then 6 characters.";
//        }elseif (mysql_num_rows($query) == 1) {
//            $_SESSION['error'] = "Username already exists.";
//        }else {
            // All errors passed lets
            // Create our insert SQL by hashing the password and using the escaped Username.
            $sql = "INSERT INTO user_data (`username` , `password`) VALUES ('" . $eUsername . "', '" . hashPassword($pPassword, SALT1, SALT2) . "');";
            $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
            $sql = "SELECT user_id FROM user_data WHERE username = '$eUsername'";
            $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
            if ($query) {
                return true;

        }
    }

    return false;
}
function hashPassword($pPassword, $pSalt1="2345#$%@3e", $pSalt2="taesa%#@2%^#") {
    return sha1(md5($pSalt2 . $pPassword . $pSalt1));
}
function loggedIn() {
    // check both loggedin and username to verify user.
    if (isset($_SESSION['loggedin']) && isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
        $sql = "SELECT user_id FROM user_data WHERE username = '$user'";
        $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
        $dataid = mysql_fetch_assoc($query);
        $_SESSION['userid'] = $dataid["user_id"];
        return true;
    }

    return false;
}
function logoutUser() {
    // using unset will remove the variable
    // and thus logging off the user.
    unset($_SESSION['username']);
    unset($_SESSION['loggedin']);
    unset($_SESSION['userid']);

    return true;
}
function validateUser($pUsername, $pPassword) {
    // See if the username and password are valid.
    $sql = "SELECT username FROM user_data
		WHERE username = '" . mysql_real_escape_string($pUsername) . "' AND password = '" . hashPassword($pPassword, SALT1, SALT2) . "' LIMIT 1";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());

    // If one row was returned, the user was logged in!
    if (mysql_num_rows($query) == 1) {
        $row = mysql_fetch_assoc($query);
        $_SESSION['username'] = $row['username'];
        $_SESSION['loggedin'] = true;

        return true;
    }


    return false;
}
//Functions for Data Manipulation
function addContact($contact_name, $tele, $company, $email, $owner){
    $sql = "SELECT * From Company_Data where Company_Name = '$company'";
    $query = mysql_query($sql) or trigger_error("error" . mysql_error());
    $companydata = mysql_fetch_assoc($query);
    $company_ID = $companydata["Company_ID"];
    $sql = "SELECT * FROM user_data WHERE username = '$owner'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    $userdata = mysql_fetch_assoc($query);
    $user_ID = $userdata["User_ID"];
    $status = 'new';
    $sql = "INSERT INTO contact_data (`Name`, `Telephone`, `Email`, `Company_ID`, `Status`, `Case_ID`, `Account_Owner_ID`)
VALUES ('$contact_name', '$tele', '$email', '$company_ID', '$status', '1', '$user_ID')";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES (LAST_INSERT_ID(), 'Contact', 'Contact Created')";
    $queryx = mysql_query($sqlx) or die(mysql_error());
    if ($query) {
        return true;

    }


return false;
}

function editContact($cid, $contact_name, $tele, $email, $status){
    $statusx = ' (Edited)';
    $status = $status.$statusx;
    $sql = "UPDATE contact_data SET `Name` = '$contact_name', `Telephone` = '$tele', `Email` = '$email', `Status` = '$status' WHERE `Contact_ID` = '$cid'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    if ($query) {
        return true;

    }
    return false;
}
function addnewcompany($company, $industry, $ctelephone, $caddress, $cemail, $cdesc){
    $sql = "INSERT INTO company_data (`Company_Name`, `Address_ID`, `Email`, `Industry`,`Company_Telephone`, `Description`)
VALUES ('$company','$caddress','$cemail','$industry','$ctelephone','$cdesc');";
    $query = mysql_query($sql) or die(mysql_error());
//    echo ' <html><script> window.alert("'.$query.' pls"); </script></html>';
    if ($query) {
        return true;
    }
    return false;
}
function addProspect($contact, $value, $source, $expi, $owner){
    $sql = "SELECT * FROM contact_data WHERE Name = '$contact'";
    $query = mysql_query($sql) or trigger_error("error" . mysql_error());
    $data = mysql_fetch_assoc($query);
    $contact_ID = $data["Contact_ID"];
    $company_ID = $data["Company_ID"];
    $sql = "SELECT * FROM user_data WHERE username = '$owner'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    $userdata = mysql_fetch_assoc($query);
    $user_ID = $userdata["User_ID"];
    $status = 'Pending';
    $sql = "INSERT INTO `prospect_data` (`Contact_ID`,`Company_ID`, `Expiration`, `Prospect_Owner_ID`, `Source`, `Status`, `Potential_Value`)
VALUES ('$contact_ID', '$company_ID', '$expi', '$user_ID', '$source', '$status', '$value')";
    $query = mysql_query($sql);
    $sqlx = "UPDATE contact_data SET `Status` = 'On Prospect' WHERE `Contact_ID` = '$contact_ID'";
    $queryx = mysql_query($sqlx) or trigger_error("Query Failed: " . mysql_error());
    $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES (LAST_INSERT_ID(), 'Prospect', 'Prospect Created')";
    $queryx = mysql_query($sqlx);
    $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES ('$contact_ID', 'Contact', 'Contact Added to Prospect')";
    $queryx = mysql_query($sqlx);

    if ($query) {
        return true;
    }
    return false;
}
function editProspect($pid, $source, $value, $expi, $status){
    $statusx = ' (Edited)';
    $status = $status.$statusx;
    $sql = "UPDATE prospect_data SET `Expiration` = '$expi', `Source` = '$source', `Status` = '$status', `Potential_Value` = '$value' WHERE `Prospect_ID` = '$pid'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    if ($query){
        return true;
    }
    return false;
}
function addLeads($pid, $value, $expi, $creator){
    $sql = "SELECT * FROM user_data WHERE username = '$creator'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    $userdata = mysql_fetch_assoc($query);
    $user_ID = $userdata["User_ID"];
    $sql = mysql_query("SELECT * FROM prospect_data WHERE `Prospect_ID`='$pid'");
    $data = mysql_fetch_assoc($sql);
    $status = "Pending";
    $sqlx = "INSERT INTO `leads_data` (`Contact_ID`, `Company_ID`, `Status`, `Estimated_Value`, `Source`, `Expiration`, `Leads_Creator_ID`)
VALUES ('$data[Contact_ID]', '$data[Company_ID]', '$status', '$value', '$data[Source]','$expi','$user_ID')";
    $query = mysql_query($sqlx) or trigger_error("Error" . mysql_error());
    $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES (LAST_INSERT_ID(), 'Leads', 'Leads Created')";
    $queryx = mysql_query($sqlx) or die(mysql_error());
    $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES ('$data[Contact_ID]', 'Contact', 'Contact Added to Leads')";
    $queryx = mysql_query($sqlx) or die(mysql_error());
    if ($query) {
        $contacq = mysql_query("UPDATE contact_data SET `Status` = 'On Leads' WHERE `Contact_ID` = '$data[Contact_ID]'") or trigger_error("Query Failed: " . mysql_error());
        $sqlxx = mysql_query("DELETE FROM `prospect_data` WHERE `Prospect_ID` = '$pid'");
        if ($sqlx){
            return true;
        }
        else{
            return false;
        }
    }
    return false;

}
function editLeads($lid, $source, $value, $expi, $status){
    $statusx = ' (Edited)';
    $status = $status.$statusx;
    $sql = "UPDATE leads_data SET `Expiration` = '$expi', `Source` = '$source', `Status` = '$status', `Estimated_Value` = '$value' WHERE `Leads_ID` = '$lid'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    if ($query){
        return true;
    }
    return false;
}
function addAccount($lid, $value, $type, $creator){

    $sql = "SELECT * FROM user_data WHERE username = '$creator'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    $userdata = mysql_fetch_assoc($query);
    $user_ID = $userdata["User_ID"];
    $sql = mysql_query("SELECT * FROM leads_data WHERE `Leads_ID`='$lid'");
    $data = mysql_fetch_assoc($sql);
    $status = "New";
    $sqlx = "INSERT INTO `account_data` (`Contact_ID`, `Company_ID`, `Status`, `Value`, `Type`, `Source`, `Account_Creator_ID`)
VALUES ('$data[Contact_ID]', '$data[Company_ID]', '$status', '$value', '$type', '$data[Source]','$user_ID')";
    $query = mysql_query($sqlx) or trigger_error("Error" . mysql_error());
    $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES (LAST_INSERT_ID(), 'Account', 'Account Created')";
    $queryx = mysql_query($sqlx) or die(mysql_error());
    if ($query) {
        $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES ('$data[Contact_ID]', 'Contact', 'An Account Created for Contact')";
        $queryx = mysql_query($sqlx) or die(mysql_error());
        $contacq = mysql_query("UPDATE contact_data SET `Status` = 'Account Created' WHERE `Contact_ID` = '$data[Contact_ID]'") or trigger_error("Query Failed: " . mysql_error());
        $sqlxx = mysql_query("DELETE FROM `leads_data` WHERE `Leads_ID` = '$lid'");
        if ($sqlxx){
            return true;
        }
        else{
            return false;
        }

    }
    return false;

}
function editAccount($aid, $value, $type, $source, $status){
    $statusx = ' (Edited)';
    $status = $status.$statusx;
    $sql = "UPDATE account_Data SET `Type` = '$type', `Source` = '$source', `Status` = '$status', `Value` = '$value' WHERE `Account_ID` = '$aid'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    if ($query){
        return true;
    }
    return false;

}
function addProject($aid, $project_name, $value, $type, $detail, $date, $creator){

    $sql = "SELECT * FROM user_data WHERE username = '$creator'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    $userdata = mysql_fetch_assoc($query);
    $user_ID = $userdata["User_ID"];
    $sql = mysql_query("SELECT * FROM account_data WHERE `Account_ID`='$aid'");
    $data = mysql_fetch_assoc($sql);
    $status = "New";
    $sqlx = "INSERT INTO `project_data` (`Account_ID`, `Contact_ID`, `Company_ID`, `Project_Name`, `Detail`, `Status`, `Value`, `Type`, `Source`, `Expected_Completion`, `Project_Owner_ID`)
VALUES ('$data[Account_ID]', '$data[Contact_ID]', '$data[Company_ID]', '$project_name', '$detail', '$status', '$value', '$type', '$data[Source]', '$date','$user_ID')";
    $query = mysql_query($sqlx) or trigger_error("Error" . mysql_error());
    $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES ('$data[Contact_ID]', 'Contact', 'A Project Attached to Associated Account')";
    $queryx = mysql_query($sqlx) or die(mysql_error());
    if ($query) {
        return true;
    }
    return false;

}
function editProject($prid, $prname, $value, $type, $detail, $source, $date, $status){

    $statusx = ' (Edited)';
    $status = $status.$statusx;
    $sql = "UPDATE `project_data` SET `Project_Name` = '$prname', `Detail` = '$detail', `Status` = '$status', `Type` = '$type', `Source` = '$source', `Value` = '$value', `Expected_Completion` = '$date' WHERE Project_ID = '$prid'";
    $query = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error());
    if ($query){
        return true;
    }
    return false;

}
function addTemplate($name, $subject, $body){
    $query = mysql_query("INSERT INTO `template_email` (`name`, `subject`, `body`) VALUES ('$name', '$subject', '$body')");
    if($query){
        return true;
    }
    return false;
}
function sendQuotation($id ,$name ,$projectname, $description, $cost, $total, $email){ //so many html
    $current_date = date('d/m/Y == H:i:s');
    $body = '';
    $body.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Quotations</title>
<link href="http://mailgun.github.io/transactional-email-templates/styles.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body itemscope itemtype="http://schema.org/EmailMessage">

<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" width="600">
			<div class="content">
				<table class="main" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="content-wrap aligncenter">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="content-block">
										<h1 class="aligncenter">Quotation</h1>
									</td>
								</tr>
								<tr>
									<td class="content-block">
										<h2 class="aligncenter">Thanks for Inquiring Us.</h2>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										<table class="invoice">
											<tr>
												<td>'.$name.'<br>'.$current_date.'</td>
											</tr>
											<tr>
												<td>
													<table class="invoice-items" cellpadding="0" cellspacing="0">
														<tr>
															<td>Service 1 : '.$projectname.'</td>
															<td class="alignright">'.$cost.'</td>
														</tr>

														<tr class="total">
															<td class="alignright" width="80%">Total</td>
															<td class="alignright">'.$total.'</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										Please Email Us for follow through
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										Hellonemo Digital Agency. Griya Shanta, Malang
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div class="footer">
					<table width="100%">
						<tr>
							<td class="aligncenter content-block">Questions? Email <a href="mailto:">hello@hellonemo.com</a></td>
						</tr>
					</table>
				</div></div>
		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';
    $subject = 'Quotation for '.$projectname.'';
    $to = $email;
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = 'dwiasa12@gmail.com'; //Sender Email
    $mail->Password = 'prakerin123';
    $mail->setFrom('dwiasa1@gmail.com');
    $mail->addAddress($to); //Recipient eMail
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->SMTPDebug = 0;
//send the message, check for errors
    if (!$mail->send()) {
        echo 0;
    } else {
        $sqlx = "INSERT INTO `activity_data` (`Related_ID`, `Type`, `Detail`)
VALUES ('$id', 'Leads', 'Quotation Sent')";
        $queryx = mysql_query($sqlx) or die(mysql_error());
        return true;
    }
}
function addCase(){

    if ($query) {
        return true;

    }


    return false;

}
function editCase(){

    if ($query) {
        return true;

    }


    return false;

}
function addSolutions(){

    if ($query) {
        return true;

    }


    return false;

}
function editSolutions(){

    if ($query) {
        return true;

    }


    return false;

}
?>