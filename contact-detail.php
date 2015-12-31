<?php require($_SERVER['DOCUMENT_ROOT'].'/crm-native/includes/config.php');
if (!loggedIn()){
    echo '<!DOCTYPE html>
<html>
    <head>
        <title>Index</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 300;
                font-family: "Lato";
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 70px;
                color: red;
            }
            a {
            color:deepskyblue;
            text-decoration: none;
            font-weight: 800;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">403 Access Denied : Please <a href="login/"> Login</a></div>
            </div>
        </div>
    </body>
</html>';
    exit;
}
$cid='';
if (isset($_GET['cid'])){
    $cid = $_GET['cid'];
}
//Take Data

$sql=mysql_query("select * from contact_data WHERE Contact_ID='$cid'");
$data=mysql_fetch_assoc($sql);
?>
<!DOCTYPE html>
<html>

<head>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/detail.css">
    <link rel="stylesheet prefetch" href="http://fian.my.id/marka/static/marka/css/marka.css">
    <link rel="stylesheet" href="css/filter.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://fian.my.id/marka/static/marka/js/marka.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        $(document).ready(function() {


            //when button is clicked

            $('#sendEmailButton').click(function(){
                check_availability();
            });

        });
        //function to check availability
        function check_availability(){

            //get the values
            var contact = $('#emailto').val();

            //use ajax to run the check
            $.post("check.php", { action:'contact',contact: contact },
                function(result){
                    //if the result is 1
                    if(result == 1){
                        //proceeds input contact
                        sendEmail();
                    }
                    else{
                        //show addCompany form
                        blank();
                    }
                });

        }
        function sendEmail(){
            var emailto = $('#emailto').val();
            var subject = $('#subject').val();  //Subject
            var emailbody = $('#emailbody').val(); //Email content
            var type = 'Contact';
            $.post("function_caller.php",{ action: 'sendemail', emailto: emailto, subject:subject, emailbody:emailbody, type:type},
                function(result){

                    if(result == 1){
                        window.location.href="#";
                        $('#flag').html('<div class="warn success-flag" >Email Sent</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                    else if(result == 2){
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >System Error </div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                    else{
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >Email Failed to send</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                });
        }

        function blank(){
            var contact = $('#emailto').val();
            window.location.href="#";
            $('#flag').html('<div class="warn error-flag">Error Contact: '+contact+' Not Found, <a href="contact.php#popup1" style="color:#48e4ce"> Please Add</a> </div>');
            setTimeout(function(){
                $('#flag').html('');
            }, 1500);
        }
    </script>
    <style>
        body {
            display: block;
            background-color: #f7f7f7;
            padding: 0;
        }
    </style> <script type="text/javascript">
	
	var originalNotifClasses;
	function toggleNotif() {
    var elem = document.getElementById('popupnotif');
    var classes = elem.className;
    if (originalNotifClasses === undefined) {
        originalNotifClasses = classes;
    }
    elem.className = /expanded/.test(classes) ? originalNotifClasses : originalNotifClasses + ' expanded';
}
</script>
<script type="text/javascript">
	
	var originalNavClasses;
	function toggleNav() {
    var elem = document.getElementById('menu');
    var classes = elem.className;
    if (originalNavClasses === undefined) {
        originalNavClasses = classes;
    }
    elem.className = /expanded/.test(classes) ? originalNavClasses : originalNavClasses + ' expanded';
}
</script>
</head>
<body>

<div class="topbar">
    <?php
    //notifications
    $current_date = date('d/m/Y H:i:s');
$sqlnotif = mysql_query("select * from task_data where due_date < '$current_date' and status != 'read' and status !='done'") or die(mysql_error());
$num = mysql_num_rows($sqlnotif);
$sqln = mysql_query("select * from task_data where status ='read'") or die(mysql_error());
while($datan=mysql_fetch_array($sqln)){
    $output ='';
    $output.='<tr>
                                    <td>'.$datan['detail'].'</td>
                                    <td class="action"><a href="#"<img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
}
if(isset($_GET['nact'])){
    $notifact = $_GET['nact']; //Get action for notif
    $nid = $_GET['nid'];
    $sql = mysql_query("update task_data where id = $nid SET ") or die(mysql_error());
}
            ?>
    <img class="logo" src="images/hellonemo-logo-small.png">
	<a href="#footer_nav" onclick="toggleNav(); return false;"><img class="menu_button" src="images/menu.png"></a>
	<div class="notif">
            <img src="images/icon1.png">
            <img src="images/icon2.png">
            <a href="#footer_nav" onclick="toggleNotif(); return false;"><?php if($num > 0) {echo'<img src="images/icon3-red.png">';} else {echo '<img src="images/icon3.png">';} ?></a></a>
			<div class="container_popup">
<div class="arrow_up"></div>
<div class="popupnotif" id="popupnotif">
<div class="popup_count">You have <?php echo $num ?> unread notifications</div>
<table border-spacing=0>


            <?php
            while($datanotif=mysql_fetch_array($sqlnotif)){
                $output ='';
                $output.='
<tr>
		<td class="popup_img">
		<img src="images/notif/notif1.png" />
		</td>
                <td class="popup_desc">
        '.$datanotif['detail'].'
		</td>
		<td>
		<div class="popup_day">Unread</div>
		</td>
	</tr>';
                echo $output; // echo the unreads
            }
            $sqln = mysql_query("select * from task_data where status ='read'") or die(mysql_error());
            while($datan=mysql_fetch_array($sqln)) {
                $output ='';
                $output.='
<tr>
		<td class="popup_img">
		<img src="images/notif/notif1.png" />
		</td>
                <td class="popup_desc">
        '.$datan['detail'].'
		</td>
		<td>
		<div class="popup_day">Read</div>
		</td>
	</tr>';
                echo $output; // echo the unreads
            }
            ?>
</table>
</div>
</div>
            <img src="images/gears.png">
        </div>
    <div class="search"><form>
            <input type="text" placeholder="search">
        </form>
        <img src="images/search.png">
    </div>

</div>
<div class="sidebar">
    <div class="pic"><img src="images/t_2P7AtX.png">
        <center><p>Nashihuddin</p></center>
        <center><button>Logout</button></center>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php">
                <img src="images/Forma-1.png"> <span>Dashboard</span>
            </a>
        </li>
        <li class="active">
            <a href="contact.php">
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>
        <li>
            <a href="leads.php">
                <img src="images/Forma-3.png"> <span>Leads</span>
            </a>
        </li>

        <li>
            <a href="prospect.php">
                <img src="images/Forma-4.png"> <span>Prospects</span>
            </a>
        </li>
        <li>
            <a href="account.php">
                <img src="images/Forma-5.png"> <span>Accounts</span>
            </a>
        </li>
        <li>
            <a href="project.php">
                <img src="images/Forma-6.png"> <span>Projects</span>
            </a>
        </li>
    </ul>
</div>
<div class="content col-md-12">
<div id="flag"></div>
    <div class="detail">
        <h1>Contact</h1>
        <div class="top">
            <ul class="breadcrumb no-padding">
                <li>Home</li>
                <li>Contact</li>
                <li><?php echo $data['Name'] ?></li>
            </ul>
        </div>
        <div class="panel">
            <div class="panel-body no-padding detail-box-large">

                <?php
                    $output ='';
                    $sqlx=mysql_query("select * from company_data, user_data WHERE company_data.Company_ID='$data[Company_ID]' and user_data.User_ID='$data[Account_Owner_ID]'");
                    $datax=mysql_fetch_assoc($sqlx);
                    $output.='<div class="column" style="margin-left:35px; margin-top: 35px;">
                    <label>Name</label>
                    <input readonly type="text" value="'.$data['Name'].'" class="readonly">
                    <br>
                    <label>Email</label>
                    <input readonly type="text" value="'.$datax['Company_Name'].'" class="readonly">
                    <br>
                    <label>Telephone</label>
                    <input readonly value="'.$data['Telephone'].'" class="readonly">
                    <br>
                    <label>Address</label>
                    <input readonly type="text" style="width:230px" value="'.$datax['Address'].'" class="readonly">
                    
                    <br>
                    <br>
                    <a class="button" href="prospect.php?cid='.$cid.'#addProspect">Add to Prospects</a>



                </div>
                <div class="column" style="margin-left:100px; margin-top:35px;">
                    <label>Email</label>
                    <input readonly type="text" value="'.$data['Email'].'" class="readonly">
                    <br>
                    <label>Owner</label>
                    <input readonly type="text" value="'.$datax['username'].'" class="readonly">

                </div>';
                    echo $output;

                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 detail-2">
        <div class="col-md-6 detail-box">
            <h1>Activity History</h1>
            <div>
                <table class="table table-condensed detail-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Date</th>
                        <th>Activity</th>
                        <th>Action</th>

                    </tr>
                    <?php
                    $sqla=mysql_query("select * from activity_data where Type = 'Contact' and Related_ID = '$cid' ORDER BY Date_Created DESC");
                    while($dataa=mysql_fetch_array($sqla)){
                        $output ='';
                        $output.='<tr>
                                                        <td>'.$dataa['Date_Created'].'</td>
                                                        <td>'.$dataa['Detail'].'</td>
                                                        <td class="action"><img style="margin-left:10px" src="images/Delete-Icon.png"></td>
                                                        </tr>
                                                        ';
                                            echo $output;
                                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 detail-box">
            <h1>Email History</h1>

            <div><a class="button" href="#sendemail" style="margin: -35px 0px 0px 390px;">Send eMail</a>

                <table class="table table-condensed detail-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Body</th>

                    </tr>
                    <?php
                    $sqla=mysql_query("select * from email_history where Type = 'Contact' and Related_ID = '$cid' ORDER BY Date_Sent DESC");
                    while($dataa=mysql_fetch_array($sqla)){
                        $output ='';
                        $output.='<tr>
                                                        <td>'.$dataa['Date_Sent'].'</td>
                                                        <td>'.$dataa['Subject'].'</td>
                                                        <td>'.$dataa['Body'].'</td>
                                                        </tr>
                                                        ';
                        echo $output;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="sendemail" class="overlay">
    <div class="popup">
        <div class="red-header">
            <h2>Send<span> Email</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="detail-form">
                <div class="column">
                    <label>To</label>
                    <input type="text" placeholder="Contact Name" id="emailto" value="<?php echo $data['Name'] ?>">
                    <br>
                    <label>Subject Email</label>
                    <input type="text" placeholder="Subject" id="subject">
                    <br>
                    <label style="margin-top:-340px;">Body</label>
                    <textarea id="emailbody"></textarea>
                    <br>
                    <label>Templates</label>

                    <div class="filter">
                        <div class="button-group">
                            <i id="icon"></i>
                            <a id="input" href="">Filter</a>
                            <ul id="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else </a></li>
                                <li><a href="#">Account Settings</a></li>
                                <li><a href="#">Help and Feedback</a></li>
                            </ul>
                        </div>
                    </div>
                    <div style="margin-top:19px">
                        <a class="button" href="#">Back</a>
                        <button type="button" class="button" id="sendEmailButton">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        $(".clickable-row").click(function () {
            window.document.location = $(this).data("href");
        });
    });
</script>
</body>

</html>
