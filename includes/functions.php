<?php
//Notes
//Change to PDO, Tidy up,
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
    $detail = mysql_real_escape_string($detail);
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

function addTask($uid, $detail, $date){
    $status = 'Pending';
    $query = mysql_query("INSERT INTO `task_data` (`User_ID`, `detail`, `due_date`, `status`) VALUES ('$uid', '$detail', '$date', '$status')");
    if($query){
        return true;
    }
    return false;
}

function addEvent($uid, $detail, $date, $start, $end){
    $status = 'Pending';
    $query = mysql_query("INSERT INTO `event_data` (`User_ID`, `detail`,`start`, `end`, `due_date`, `status`) VALUES ('$uid', '$detail', '$start','$end','$date', '$status')");
    if($query){
        return true;
    }
    return false;
}
function sendQuotation($id ,$name ,$projectname, $description, $cost, $total, $email){ //so many html
    $current_date = date('d/m/Y == H:i:s');
    $body = '';
    $body.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Quotations</title>

</head>

<body itemscope="" itemtype="http://schema.org/EmailMessage" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6; background-color: #f6f6f6; margin: 0; padding: 0;" bgcolor="#f6f6f6">

<table class="body-wrap" style="font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0; padding: 0;" bgcolor="#f6f6f6">
	<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
		<td style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top"></td>
		<td class="container" width="600" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; width: 100% !important; margin: 0 auto; padding: 0;" valign="top">
			<div class="content" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 10px;">
				<table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; padding: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
					<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
						<td class="content-wrap aligncenter" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 20px;" align="center" valign="top">
							<table width="100%" cellpadding="0" cellspacing="0" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
								<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
									<td class="content-block" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
										<h1 class="aligncenter" style="font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 22px !important; color: #000; line-height: 1.2; font-weight: 600 !important; text-align: center; margin: 20px 0 5px; padding: 0;" align="center">Quotation</h1>
									</td>
								</tr>
								<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
									<td class="content-block" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
										<h2 class="aligncenter" style="font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; box-sizing: border-box; font-size: 18px !important; color: #000; line-height: 1.2; font-weight: 600 !important; text-align: center; margin: 20px 0 5px; padding: 0;" align="center">Thanks for Inquiring Us.</h2>
									</td>
								</tr>
								<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
									<td class="content-block aligncenter" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
										<table class="invoice" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; text-align: left; width: 100% !important; margin: 40px auto; padding: 0;">
											<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
												<td style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;" valign="top">'.$name.'<br style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;" />'.$current_date.'</td>
											</tr>
											<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
												<td style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;" valign="top">
													<table class="invoice-items" cellpadding="0" cellspacing="0" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0; padding: 0;">
														<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
															<td style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top">Service 1 : '.$projectname.'</td>
															<td class="alignright" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" align="right" valign="top">'.$cost.'</td>
														</tr>

														<tr class="total" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
															<td class="alignright" width="80%" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">Total</td>
															<td class="alignright" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">'.$total.'</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
									<td class="content-block aligncenter" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
										Please Email Us for follow through
									</td>
								</tr>
								<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
									<td class="content-block aligncenter" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
										Hellonemo Digital Agency. Griya Shanta, Malang
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div class="footer" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
					<table width="100%" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
						<tr style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
							<td class="aligncenter content-block" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">Questions? Email <a href="mailto:" style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0; padding: 0;">hello@hellonemo.com</a></td>
						</tr>
					</table>
				</div></div>
		</td>
		<td style="font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top"></td>
	</tr>
</table>

</body>
</html>
';
    $subject = 'Quotation for '.$projectname.'';
    $to = $email;
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->IsHTML(true);
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
function sendInvoice($id ,$name ,$companyname , $email){
    $output = '';$aid = $id;
    $sql=mysql_query("select * from account_data WHERE Account_ID ='$aid' ");
    $data=mysql_fetch_assoc($sql);
    $sqlx=mysql_query("SELECT * FROM contact_data,user_data WHERE contact_data.contact_ID= '$data[Contact_ID]'
and user_data.user_ID='$data[Account_Creator_ID]'");
    $datax = mysql_fetch_assoc($sqlx);
    $cdata = mysql_fetch_assoc(mysql_query("SELECT * FROM company_data WHERE company_ID='$data[Company_ID]'"));

    $total = '';  //init total cost/value
    $sqla=mysql_query("select * from project_data where Account_ID = $aid and status = 'done'");
    $i = 1;
    while($dataa=mysql_fetch_array($sqla)){
        $output.='<tr>    <td class="service" style="text-align: left; vertical-align: top; padding: 20px;" align="left" valign="top">'.$i.'</td>
                                                        <td class="service" style="text-align: left; vertical-align: top; padding: 20px;" align="left" valign="top">'.$dataa['Project_Name'].'</td>
                                                        <td class="desc" style="text-align: left; vertical-align: top; padding: 20px;" align="left" valign="top">'.$dataa['Detail'].'</td>
                                                        <td class="unit" style="text-align: right; font-size: 1.2em; padding: 20px;" align="right">'.$dataa['Type'].'</td>
                                                        <td class="total" style="text-align: right; font-size: 1.2em; padding: 20px;" align="right">'.$dataa['Value'].' IDR</td>
                                                        </tr>
                                                        ';
        $total = $total + $dataa['Value'];
        $i++;
    }
    $body = '';
    $current_date = date('D d F Y');
    $body.='
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Example 1</title>

</head>
<body style="position: relative; width: 21cm; height: 29.7cm; color: #001028; background-color: #FFFFFF; font-size: 12px; font-family: Arial; margin: 0 auto;" bgcolor="#FFFFFF">
<header class="clearfix" style="margin-bottom: 30px; padding: 10px 0;">
    <div id="logo" style="text-align: center; margin-bottom: 10px;" align="center">
        <img src="http://hopesa.github.io/images/hellonemo-logo.png" style="width: 90px;" />
    </div>
    <h1 style="margin: 0 0 20px; border-top-style: solid; border-top-width: 1px; border-top-color: #5D6975; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #5D6975; color: #5D6975; font-size: 2.4em; line-height: 1.4em; font-weight: normal; text-align: center; background-image: url("http://hopesa.github.io/images/dimension.png");" align="center">INVOICE</h1>
    <div id="company" class="clearfix" style="float: right; text-align: right;" align="right">
        <div style="white-space: nowrap;">'.$cdata['Company_Name'].'</div>
        <div style="white-space: nowrap;">'.$datax['Telephone'].'</div>
        <div style="white-space: nowrap;">'.$cdata['Email'].'</div>
    </div>
    <div id="project" style="float: left;">
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">CLIENT</span>'.$datax['Name'].'</div>
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">ADDRESS</span>'.$cdata['Address'].'</div>
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">EMAIL</span>'.$datax['Email'].'</div>
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">DATE</span>'.$current_date.'</div>
    </div>
</header>
<main>
    <table style="width: 100%; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;">
        <thead>
        <tr>

            <th style="text-align: center; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="center">No</th>
            <th class="service" style="text-align: left; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="left">SERVICE</th>
            <th class="desc" style="text-align: left; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="left">DESCRIPTION</th>
            <th style="text-align: center; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="center">TYPE</th>
            <th style="text-align: center; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="center">COST</th>
        </tr>
        </thead>
        <tbody>
        '.$output.'
        <tr>
            <td colspan="4" class="grand total" style="text-align: right; font-size: 1.2em; border-top-width: 1px; border-top-color: #5D6975; border-top-style: solid; padding: 20px;" align="right">Total</td>
            <td class="grand total" style="text-align: right; font-size: 1.2em; border-top-width: 1px; border-top-color: #5D6975; border-top-style: solid; padding: 20px;" align="right">'.$total.'</td>
        </tr>
        </tbody>
    </table>
    <div id="notices">
        <div>NOTICE:</div>
        <div class="notice" style="color: #5D6975; font-size: 12px;">Please Confirm to us after you made your payment</div>
    </div>
</main>
<footer style="color: #5D6975; width: 100%; height: 30px; border-top-style: solid; border-top-width: 1px; border-top-color: #C1CED9; text-align: center; padding: 8px 0;">
    Invoice was created on a computer and is valid without the signature and seal. &copy Hellonemo Digital Agency 2013-2016
</footer>

<style type="text/css">
    .clearfix:after { content: "" !important; display: table !important; clear: both !important; }
    body { position: relative !important; width: 21cm !important; height: 29.7cm !important; margin: 0 auto !important; color: #001028 !important; background: #FFFFFF !important; font-size: 12px !important; font-family: Arial !important; }
</style>
</body>
</html>';

    $subject = 'Invoice for '.$companyname.'';
    $to = $email;
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->IsHTML(true);
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
VALUES ('$id', 'Acccount', 'Invoice Sent')";
        $queryx = mysql_query($sqlx) or die(mysql_error());
    }
}
function CsvExport($table,$filename = 'exported.csv')
{
    $csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
    $sql_query = "select * from $table";

    // Gets the data from the database
    $result = mysql_query($sql_query) or die(mysql_error());
    $fields_cnt = mysql_num_fields($result);


    $schema_insert = '';

    for ($i = 0; $i < $fields_cnt; $i++)
    {
        $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
                stripslashes(mysql_field_name($result, $i))) . $csv_enclosed;
        $schema_insert .= $l;
        $schema_insert .= $csv_separator;
    } // end for

    $out = trim(substr($schema_insert, 0, -1));
    $out .= $csv_terminated;

    // Format the data
    while ($row = mysql_fetch_array($result))
    {
        $schema_insert = '';
        for ($j = 0; $j < $fields_cnt; $j++)
        {
            if ($row[$j] == '0' || $row[$j] != '')
            {

                if ($csv_enclosed == '')
                {
                    $schema_insert .= $row[$j];
                } else
                {
                    $schema_insert .= $csv_enclosed .
                        str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
                }
            } else
            {
                $schema_insert .= '';
            }

            if ($j < $fields_cnt - 1)
            {
                $schema_insert .= $csv_separator;
            }
        } // end for

        $out .= $schema_insert;
        $out .= $csv_terminated;
    } // end while

    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($out));
    // Output to browser with appropriate mime type, you choose ;)
    header("Content-type: text/x-csv");
    //header("Content-type: text/csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=$filename");
    echo $out;
    return $out;

}

?>