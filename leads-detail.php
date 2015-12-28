<?php
require($_SERVER['DOCUMENT_ROOT'].'/crm-native/includes/config.php');
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
$lid = '';
if (isset($_GET['lid'])){
    $lid = $_GET['lid'];
}
$sql=mysql_query("select * from leads_data WHERE Leads_ID='$lid'");
$data=mysql_fetch_assoc($sql);

//Take Data Early
$sqlx = "SELECT * FROM contact_data,user_data WHERE contact_data.contact_ID= '$data[Contact_ID]'
and user_data.user_ID='$data[Leads_Creator_ID]'";
$queryx = mysql_query($sqlx) or trigger_error("error" . mysql_error());
$datax = mysql_fetch_assoc($queryx);
$cdata = mysql_fetch_assoc(mysql_query("SELECT * FROM company_data WHERE company_ID='$data[Company_ID]'")); //Can this be done?
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
            $('#sendQuotationButton').click(function(){
                sendQuotation();
            });

        });
        //function to check availability
        function check_availability(){

            //get the values
            var contact = $('#emailto').val();

            //use ajax to run the check
            $.post("check.php", { action:'leads',leads: contact },
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
            var type = 'leads';
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
        function sendQuotation(){
            var id = <?php echo $lid ?>;
            var name = "<?php echo $datax['Name'] ?>";
            var projectname = $('#projectname').val();
            var description = $('#description').val();
            var cost = $('#cost').val();
            var total = $('#total').val();
            var email = "<?php echo $datax['Email']?>";
            $.post("function_caller.php",{ action: 'quotation', id:id, name:name, projectname:projectname, description:description, cost:cost, total:total, email:email},
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
    </style>
</head>

<body>

<div class="topbar">
    <img class="logo" src="images/hellonemo-logo-small.png">
    <div class="search">
        <form>
            <input type="text" placeholder="search">
        </form>
        <img src="images/search.png">
    </div>

    <div class="notif">
        <img src="images/icon1.png">
        <img src="images/icon2.png">
        <img src="images/icon3.png">
        <img src="images/gears.png">
    </div>

</div>
<div class="sidebar">
    <div class="pic"><img src="images/t_2P7AtX.png">
        <center>
            <p>Nashihuddin</p>
        </center>
        <center>
            <button>Logout</button>
        </center>
    </div>
    <ul class="sidebar-menu">
        <li class="active">
            <a href="">
                <img src="images/Forma-1.png"> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Leads</span>
            </a>
        </li>

        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Prospects</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Accounts</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Projects</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Reports</span>
            </a>
        </li>
    </ul>
</div>
<div class="content col-md-12">
<div id="flag"></div>
    <div class="detail">
        <h1>Leads</h1>
        <div class="top">
            <ul class="breadcrumb no-padding">
                <li>Home</li>
                <li>Leads</li>
                <li><?php echo $datax['Name'] ?></li>
            </ul>
        </div>
        <div class="panel">
            <div class="panel-body no-padding detail-box-large">
                <?php
                $output ='';
                $output.='<div class="column" style="margin-left:35px; margin-top: 35px;">
                    <label>Name</label>
                    <input type="text" value="'.$datax['Name'].'" class="readonly">
                    <br>
                    <label>Company</label>
                    <input type="text" value="'.$cdata['Company_Name'].'" class="readonly">
                    <br>
                    <label>Status</label>
                    <input readonly value="'.$data['Status'].'" class="readonly">
                    <br>
                     <label>Expiration Date</label>
                    <input readonly value="'.$data['Expiration'].'" class="readonly">

                </div>
                <div class="column" style="margin-left:100px; margin-top:35px;"> <

                    <label>Source</label>
                    <input type="text" value="'.$data['Source'].'" class="readonly">
                    <br>
                    <label>Value</label>
                    <input type="text" value="'.$data['Estimated_Value'].'" class="readonly">
                    <br>
                    <label>Owner</label>
                    <input type="text" value="'.$datax['username'].'" class="readonly">

                </div>';
                echo $output;

                ?>

            </div>
        </div>
    </div>
    <div class="col-md-12 detail-2">
        <div class="col-md-6 detail-box">
            <h1>Activity History</h1>
            <div><a class="button" href="#quote" style="margin: -35px 0px 0px 380px;">Send Quotation</a>
                <table class="table table-condensed detail-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Date</th>
                        <th>Activity</th>
                        <th>Action</th>

                    </tr>
                    <?php
                    $sqla=mysql_query("select * from activity_data where Type = 'Leads' and Related_ID = '$lid' ORDER BY Date_Created DESC");
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
                    $sqla=mysql_query("select * from email_history where Type = 'leads' and Related_ID = '$lid' ORDER BY Date_Sent DESC");
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
                    <input type="text" placeholder="Contact Name" id="emailto" value="<?php echo $datax['Name'] ?>">
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
<div id="quote" class="overlay">
    <div class="popup-2">
        <div class="red-header">
            <h2>Send<span> Quotation</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form">
                <div class="column">

                    <label>Unit 1</label>
                    <input type="text" placeholder="Project Name" id="projectname">
                    <br>
                    <label>Description</label>
                    <input type="text" placeholder="Description" id="description">
                    <br>
                    <label>Cost</label>
                    <input type="number" placeholder="Cost" id="cost">
                    <br>
                    <label>Total</label>
                    <input type="number" placeholder="Total" id="total">
                    <br>
                    <br>
                    <button class="button">Add Unit</button>
                    <button class="button" id="sendQuotationButton">Send Quotation</button>
                    <a class="button" href="#">Cancel</a>
                    <br>
                    <br>
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
