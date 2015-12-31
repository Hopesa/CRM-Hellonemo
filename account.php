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
$aid = '';
$value = '';
$contact_name= '';
if (isset($_GET['lid'])){
    $lid = $_GET['lid'];
    $query=mysql_query('SELECT * FROM leads_data WHERE Leads_ID = "'.$lid.'"');
    $data = mysql_fetch_assoc($query);
    $cid = $data['Contact_ID'];
    $value = $data['Estimated_Value'];
}
if (isset($_GET['aid'])){
    $aid = $_GET['aid'];
}
if (isset($cid)){
    $query=mysql_query('SELECT * FROM contact_data WHERE contact_ID = "'.$cid.'"');
    $data = mysql_fetch_assoc($query);
    $contact_name = $data['Name'];
}
?>
    <html>

    <head>

        <title>CRM | Account</title>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/contact.css">
        <link rel="stylesheet prefetch" href="http://fian.my.id/marka/static/marka/css/marka.css">
        <link rel="stylesheet" href="css/filter.css">
        <!-- favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
        <link rel="manifest" href="images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- end of favicon -->
        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script>
            $(document).ready(function () {


                //when button is clicked

                $('#addAccountButton').click(function () {
                    check_availability();
                });
                $('#editAccountButton').click(function () {
                    edit_Account();
                });
                $('#ConfirmationButton').click(function () {
                    add_Account();
                });

            });
            //function to check availability of prospect
            function check_availability() {

                //get the values
                var account = $('#contact_name').val();

                //use ajax to run the check
                $.post("check.php", {
                        action: 'account',
                        account: account
                    },
                    function (result) {
                        //if the result is 1
                        if (result == 1) {
                            //proceeds input contact
                            //                        confirmation();

                        } else {
                            //show addCompany form
                            blank();
                        }
                    });

            }

            function confirmation() {
                window.location.href = "#confirmpop";
            }

            function add_Account() {
                var contact = $('#contact_name').val();
                var value = $('#value').val(); //Potential Value
                var type = $('#type').val(); // Type of Account
                var creator = $('#creator').val(); //Creator
                var lid = '<?php echo $lid ?>';

                $.post("function_caller.php", {
                        action: 'addaccount',
                        contact: contact,
                        value: value,
                        type: type,
                        creator: creator,
                        lid: lid
                    },
                    function (result) {

                        if (result == 1) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn success-flag" >Account: ' + contact + ' Successfully Added</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                            reload();

                        } else if (result == 2) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >System Error </div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                        } else {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >' + contact + ' Failed to Add</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                        }
                    });
            }

            function edit_Account() {
                var name = $('#econtact').val();
                var aid = '<?php echo $aid; ?>';
                var source = $('#esource').val();
                var type = $('#etype').val();
                var value = $('#evalue').val();
                var status = $('#estatus').val();
                $.post("function_caller.php", {
                        action: 'editaccount',
                        aid: aid,
                        source: source,
                        value: value,
                        type: type,
                        status: status
                    },
                    function (result) {
                        if (result == 1) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn success-flag" >Account ' + name + ' Successfully Edited</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                            reload();
                        } else if (result == 2) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >Error E</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                        } else {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >Error</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                        }
                    });
            }

            function blank() {
                window.location.href = "#";
                $('#flag').html('<div class="warn error-flag">Error: Leads Not Found<a href="leads.php#addLeads" style="color:#48e4ce">Add</a> </div>');
                setTimeout(function () {
                    $('#flag').html('');
                }, 1500);
            }

            function reload() {
                location.reload();
            }
        </script>
        </script>
        <script type="text/javascript">
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
                            <div class="popup_count">You have
                                <?php echo $num ?> unread notifications</div>
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
                <div class="search">
                    <form>
                        <input type="text" placeholder="search">
                    </form>
                    <img src="images/search.png">
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
                <li>
                    <a href="dashboard.php">
                        <img src="images/Forma-1.png"> <span>Dashboard</span>
                    </a>
                </li>
                <li>
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
                <li class="active">
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
        <ul class="mobile-menu" id="menu">
            <div class="search2">
                <form>
                    <input type="text" placeholder="search">
                    <img src="images/search.png">
                </form>
            </div>
            <li>
                <a href="dashboard.php">
                    <img src="images/Forma-1.png"> <span>Dashboard</span>
                </a>
            </li>
            <li>
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
            <li class="active">
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
        <div class="content col-md-12">
            <div id="flag"></div>
            <div class="contact">
                <h1>Accounts</h1>
                <div class="top"><a class="button" href="#addAccount">New Account</a>
                </div>
                <div class="panel">
                    <div class="panel-body no-padding">
                        <table class="table table-condensed contact-table">
                            <tbody>
                                <tr class="panel-heading">
                                    <th>Name</th>
                                    <th class="hide2">Company</th>
                                    <th class="hide1">Email</th>
                                    <th class="hide1">Type</th>
                                    <th>Status</th>
                                    <th>Value</th>
                                    <th class="hide1">Owner</th>
                                    <th class="hide2">Action</th>
                                </tr>
                                <?php
                    $sql=mysql_query('select * from account_data');
                    if (mysql_num_rows($sql)<1){
                        $output.="<center><h3>Tidak Ada Account Aktif</h3></center>";
                        echo $output;
                    }
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqlx = "SELECT * FROM contact_data,company_data,user_data WHERE contact_data.contact_ID= '$data[Contact_ID]' and company_data.company_ID='$data[Company_ID]'
and user_data.user_ID='$data[Account_Creator_ID]'";
                        $queryx = mysql_query($sqlx) or trigger_error("error" . mysql_error());
                        $datax = mysql_fetch_assoc($queryx);
                        $output.='<tr>
                                    <td class="clickable-row" data-href="account-detail.php?aid='.$data['Account_ID'].'">'.$datax['Name'].'</td>
                                    <td class="clickable-row hide2" data-href="account-detail.php?aid='.$data['Account_ID'].'">'.$datax['Company_Name'].'</td>
                                    <td class="clickable-row hide1" data-href="account-detail.php?aid='.$data['Account_ID'].'">'.$datax['Email'].'</td>
                                    <td class="clickable-row hide1" data-href="account-detail.php?aid='.$data['Account_ID'].'">'.$data['Type'].'</td>
                                    <td class="clickable-row" data-href="account-detail.php?aid='.$data['Account_ID'].'">'.$data['Status'].'</td>
                                    <td class="clickable-row" data-href="account-detail.php?aid='.$data['Account_ID'].'">'.$data['Value'].'</td>
                                    <td class="clickable-row hide1" data-href="account-detail.php?aid='.$data['Account_ID'].'">'.$datax['username'].'</td>
                                    <td class="action hide2" ><a href=account.php?aid='.$data['Account_ID'].'#editAccount><img src="images/Edit-Icon.png"></a><a href=account.php?aid='.$data['Account_ID'].'#confirmdel><img src="images/Delete-Icon.png"</a> </td>
                                </tr>';
                        echo $output;
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 contact-2">
                <div class="tools col-md-6 contact-box">
                    <h1>Tools</h1>
                    <div>
                        <a class="square red-link"><img src="images/icon-prospect.png">
                            <br>Import Prospects</a>
                        <a class="square green-link"><img src="images/icon-trash.png">
                            <br>Mass Delete Prospect</a>
                        <a class="square yellow-link" style="padding-top:20px;"><img src="images/icon-message.png">
                            <br>Mass Email Prospect</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pop Up New Account -->

        <div id="addAccount" class="overlay">
            <div class="popup">
                <div class="red-header">
                    <h2>New <span>Account</span></h2>
                    <a class="close" href="#">&times;</a>
                </div>
                <div class="content-pop">
                    <form class="contact-form">
                        <div class="column">
                            <label>Leads Name</label>
                            <input type="text" placeholder="Leads Name" id="contact_name" value="<?php echo $contact_name ?>">
                            <br>
                            <label>Type</label>
                            <input type="text" placeholder="Type" id="type">
                            <br>
                        </div>
                        <div class="column">
                            <label>Value</label>
                            <input type="text" placeholder="Value" id="value" value="<?php echo $value ?>">
                            <br>
                            <label>Owner</label>
                            <input readonly value="<?php echo $_SESSION['username'] ?>" class="readonly" id="creator">
                            <br>
                            <br>
                            <button type="button" class="button" id="addAccountButton">Save</button>
                            <a class="button" href="#">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="editAccount" class="overlay">
            <?php
    $sql=mysql_query("select * from account_data WHERE Account_ID ='$aid' ");
    $data=mysql_fetch_assoc($sql);
    $sqlx=mysql_query("select * from contact_data WHERE Contact_ID ='$data[Contact_ID]'");
    $datax=mysql_fetch_assoc($sqlx);
    $output="";
    $output.='<div class="popup">
        <div class="red-header">
            <h2>Edit <span>Account</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form" method="post">
                <div class="column">
                    <label>Contact</label><input type="text" placeholder="Account Name" id="econtact" readonly value="'.$datax['Name'].'"><br>
                    <label>Source</label><input type="text" placeholder="Source" id="esource" value="'.$data['Source'].'"><br>
                    <label>Value</label><input type="text" placeholder="Value" id="evalue" value="'.$data['Value'].'">
                    <input type="text" hidden id="estatus" value="'.$data['Status'].'">
                </div>
                <div class="column">
                    <label>Type</label><input  type="text" id="etype" value="'.$data['Type'].'"><br>
                    <button type="button" class="button" id="editAccountButton">Edit</button>
                    <a class="button" href="#">Cancel</a>
                    <a class="button" href="account.php?lid='.$data['Account_ID'].'#addAccount">Add to Accounts</a>
                </div>
            </form>
        </div>
    </div>
</div>';
    echo $output;
    ?>
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
                <div id="confirmdel" class="overlay">
                    <div class="popup-small">
                        <div class="red-header">
                            <h2><span>Confirmation</span></h2>
                            <a class="close" href="#">&times;</a>
                        </div>
                        <div class="content-pop">
                            <h3 style="margin-bottom:22px">Are you sure want to delete this account??<center><br><a  class="button" href=delete.php?action=account&id=<?php echo $aid ?> style="margin-left:40px">Yes</a>
                        <a href="#" class="button">Cancel</a><br></center></h3>
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