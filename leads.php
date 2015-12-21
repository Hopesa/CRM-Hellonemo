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
$pid = '';
$lid = '';
$value = '';
$contact_name= '';
if (isset($_GET['pid'])){
    $pid = $_GET['pid'];
    $query=mysql_query('SELECT * FROM prospect_data WHERE prospect_ID = "'.$pid.'"');
    $data = mysql_fetch_assoc($query);
    $cid = $data['Contact_ID'];
    $value = $data['Potential_Value'];
}
if (isset($_GET['lid'])){
    $lid = $_GET['lid'];
}
if (isset($cid)){
    $query=mysql_query('SELECT * FROM contact_data WHERE contact_ID = "'.$cid.'"');
    $data = mysql_fetch_assoc($query);
    $contact_name = $data['Name'];
}
?>
<html>
<head>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet prefetch" href="http://fian.my.id/marka/static/marka/css/marka.css">
    <link rel="stylesheet" href="css/filter.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://fian.my.id/marka/static/marka/js/marka.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        $(document).ready(function() {


            //when button is clicked

            $('#addLeadsButton').click(function(){
                check_availability();
            });
            $('#editLeadsButton').click(function(){
                edit_leads();
            });
            $('#ConfirmationButton').click(function(){
                add_leads();
            });

        });
        //function to check availability of prospect
        function check_availability(){

            //get the values
            var prospect = $('#contact_name').val();

            //use ajax to run the check
            $.post("check.php", { action:'prospect',prospect: prospect },
                function(result){
                    //if the result is 1
                    if(result == 1){
                        //proceeds input contact
//                        add_leads();
                        confirmation();
                    }
                    else{
                        //show addCompany form
                        blank();
                    }
                });

        }
        function confirmation(){
            window.location.href="#confirmpop";
        }
        function add_leads(){
            var contact = $('#contact_name').val();
            var value = $('#value').val();  //Potential Value
            var expi = $('#dateexp').val(); //Expiration
            var creator = $('#creator').val(); //Creator
            var pid = '<?php echo $pid ?>';

            $.post("function_caller.php",{ action: 'addleads', contact: contact, value:value, expi:expi, creator:creator, pid:pid},
                function(result){

                    if(result == 1){
                        window.location.href="#";
                        $('#flag').html('<div class="warn success-flag" >Prospect: '+contact+' Successfully Added</div>');
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
                        $('#flag').html('<div class="warn error-flag" >'+contact+' Failed to Add</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                });
        }
        function edit_leads(){
            var name = $('#econtact').val();
            var lid = '<?php echo $lid; ?>';
            var source = $('#esource').val();
            var value = $('#evalue').val();
            var expi = $('#eexpi').val();
            var status = $('#estatus').val();
            $.post("function_caller.php",{ action: 'editleads', lid:lid, source:source, value:value, expi:expi, status:status},
                function(result){
                    if(result == 1){
                        window.location.href="#";
                        $('#flag').html('<div class="warn success-flag" >Leads '+name+' Successfully Edited</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                    else if(result == 2){
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >Error E</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                    else{
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >Error</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                });
        }
        function blank(){
            window.location.href="#";
            $('#flag').html('<div class="warn error-flag">Error: Please Add Prospect First <a href="prospect.php#addProspect" style="color:#48e4ce"> Please Add</a> </div>');
            setTimeout(function(){
                $('#flag').html('');
            }, 1500);
        }
    </script>
    <style>
        body{
            display: block;
            background-color: #f7f7f7;
            padding: 0;
        }
    </style>
</head>
<body>

<div class="topbar">
    <img class="logo" src="images/hellonemo-logo-small.png">
    <div class="search"><form>
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
        <center><p>Nashihuddin</p></center>
        <center><button>Logout</button></center>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="">
                <img src="images/Forma-1.png"> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>
        <li class="active">
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
    <div class="contact">
        <h1>Leads</h1>
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
        <div class="top"><a class="button" href="#addLeads">New Lead</a>
        </div>
        <div class="panel">
            <div class="panel-body no-padding">
                <table class="table table-condensed contact-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Name</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Source</th>
                        <th>Expiration Date</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
<!--                    <tr class="clickable-row" data-href="http://laracast.com/">-->
<!--                        <td>Ashlyn Twombly</td>-->
<!--                        <td></td>-->
<!--                        <td>On Progress</td>-->
<!--                        <td>59999</td>-->
<!--                        <td>12/21/2015</td>-->
<!--                        <td class="action"><img src="images/Edit-Icon.png"><img src="images/Delete-Icon.png"></td>-->
<!--                    </tr>-->
                    <?php
                    $sql=mysql_query('select * from leads_data');
                    if (mysql_num_rows($sql)<1){
                        $output.="<center><h3>Tidak Ada Leads Aktif</h3></center>";
                        echo $output;
                    }
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqlx = "SELECT * FROM contact_data,company_data,user_data WHERE contact_data.contact_ID= '$data[Contact_ID]' and company_data.company_ID='$data[Company_ID]'
and user_data.user_ID='$data[Leads_Creator_ID]'";
                        $queryx = mysql_query($sqlx) or trigger_error("error" . mysql_error());
                        $datax = mysql_fetch_assoc($queryx);
                        $output.='<tr class="" data-href="contact-detail.php?id='.$data['Leads_ID'].'">
                                    <td>'.$datax['Name'].'</td>
                                    <td>'.$datax['Company_Name'].'</td>
                                    <td>'.$data['Status'].'</td>
                                    <td>'.$data['Estimated_Value'].'</td>
                                    <td>'.$data['Source'].'</td>
                                    <td>'.$data['Expiration'].'</td>
                                    <td>'.$datax['username'].'</td>
                                    <td class="action"><a href=leads.php?lid='.$data['Leads_ID'].'#editLeads><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
                    ?>
                    </tbody></table>
            </div>
        </div>
    </div>
    <div class="col-md-12 contact-2">
        <div class="tools col-md-6 contact-box">
            <h1>Tools</h1>
            <div>
                <a class="square red-link"><img src="images/icon-prospect.png"><br>Import Prospects</a>
                <a class="square green-link"><img src="images/icon-trash.png"><br>Mass Delete Prospect</a>
                <a class="square yellow-link" style="padding-top:20px;"><img src="images/icon-message.png"><br>Mass Email Leads</a>
            </div>
        </div>
        <div class="reports col-md-6 contact-box">
            <h1>Reports</h1>
            <div>
                <a class="square red-link"><img src="images/24-clock.png"><br>Mass Email Contact</a>
                <a class="square green-link"><img src="images/monophone.png"><br>Email Status</a>
            </div>
        </div>
    </div>
</div>
<!-- Pop Up New Leads -->

<div id="addLeads" class="overlay">
    <div id="flag-confirmation">/div>
    <div class="popup">
        <div class="red-header">
            <h2>New <span>Lead</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form">
                <div class="column">
                    <label>Contact</label><input type="text" placeholder="Contact Name" id="contact_name" value="<?php echo $contact_name ?>"><br>
                    <label>Estimated Value</label><input type="text" placeholder="Value" id="value" value="<?php echo $value ?>"><br>
                </div>
                <div class="column">
                    <label>Exp Date</label><input type="date" id="dateexp"><br>
                    <label>Owner</label><input readonly value="<?php echo $_SESSION['username']?>" class="readonly" id="creator"><br><br>
                    <button type="button" class="button" id="addLeadsButton">Save</button>
                    <a class="button" href="#">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
<div id="confirmpop" class="overlay">
    <div class="popup-small">
        <div class="red-header">
            <h2><span>Confirmation</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <h3 style="margin-bottom:22px">Are you sure want to follow up this prospect?
                Status: No Status<center><br><button type="button" class="button" id="ConfirmationButton" style="margin-left:40px">Follow Up</button>
                    <a href="#" class="button">Cancel</a><br></center></h3>
        </div>
    </div>
</div>
<div id="editLeads" class="overlay">
    <?php
    $sql=mysql_query("select * from leads_data WHERE Leads_ID ='$lid' ");
    $data=mysql_fetch_assoc($sql);
    $sqlx=mysql_query("select * from contact_data WHERE Contact_ID ='$data[Contact_ID]'");
    $datax=mysql_fetch_assoc($sqlx);
    $output="";
    $output.='<div class="popup">
        <div class="red-header">
            <h2>Edit <span>Leads</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form" method="post">
                <div class="column">
                    <label>Contact</label><input type="text" placeholder="Contact Name" id="econtact" readonly value="'.$datax['Name'].'"><br>
                    <label>Source</label><input type="text" placeholder="Source" id="esource" value="'.$data['Source'].'"><br>
                    <label>Value</label><input type="text" placeholder="Value" id="evalue" value="'.$data['Estimated_Value'].'">
                    <input type="text" hidden id="estatus" value="'.$data['Status'].'">
                </div>
                <div class="column">
                    <label>Email</label><input  type="date" id="eexpi" value="'.$data['Expiration'].'"><br>
                    <button type="button" class="button" id="editLeadsButton">Edit</button>
                    <a class="button" href="#">Cancel</a>
                    <a class="button" href="account.php?lid='.$data['Leads_ID'].'#addAccount">Add to Accounts</a>
                </div>
            </form>
        </div>
    </div>
</div>';
    echo $output;
    ?>
    <script>

        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
            });
        });
    </script>
<script>

    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.document.location = $(this).data("href");
        });
    });
</script>
</body>
</html>