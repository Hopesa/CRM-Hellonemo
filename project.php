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
$aid = '';
$prid = '';
$value = '';
$contact_name= '';
if (isset($_GET['aid'])){
    $aid = $_GET['aid'];
    $query=mysql_query('SELECT * FROM account_data WHERE Account_ID = "'.$aid.'"');
    $data = mysql_fetch_assoc($query);
    $cid = $data['Contact_ID'];
    $value = $data['Value'];
}
if (isset($_GET['prid'])){
    $prid = $_GET['prid'];
}
if (isset($cid)){
    $query=mysql_query('SELECT * FROM contact_data WHERE contact_ID = "'.$cid.'"');
    $data = mysql_fetch_assoc($query);
    $contact_name = $data['Name'];
}
?>
    <html>

    <head>

        <title>CRM | Project</title>
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

                $('#addProjectButton').click(function () {
                    check_availability();
                });
                $('#editProjectButton').click(function () {
                    edit_Project();
                });
                $('#ConfirmationButton').click(function () {
                    add_Project();
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
                            confirmation();
                        } else {
                            //show addCompany form
                            blank();
                        }
                    });

            }

            function confirmation() {
                window.location.href = "#confirmpop";
            }

            function add_Project() {
                var contact = $('#contact_name').val();
                var project_name = $('#project_name').val();
                var value = $('#value').val(); //Potential Value
                var type = $('#type').val(); // Type of Account
                var date = $('#completion_date').val();
                var detail = $('#detail').val();
                var creator = $('#creator').val(); //Creator
                var aid = '<?php echo $aid ?>';

                $.post("function_caller.php", {
                        action: 'addproject',
                        contact: contact,
                        project: project_name,
                        value: value,
                        type: type,
                        creator: creator,
                        date: date,
                        detail: detail,
                        aid: aid
                    },
                    function (result) {

                        if (result == 1) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn success-flag" >Project Successfully Added</div>');
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
                            $('#flag').html('<div class="warn error-flag" >Project Failed to Add</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                        }
                    });
            }

            function edit_Project() {
                var date = $('#ecompletion_date').val();
                var detail = $('#edetail').val();
                var name = $('#eproject_name').val();
                var prid = '<?php echo $prid; ?>';
                var source = $('#esource').val();
                var type = $('#etype').val();
                var value = $('#evalue').val();
                var status = $('#estatus').val();
                $.post("function_caller.php", {
                        action: 'editproject',
                        prid: prid,
                        name: name,
                        source: source,
                        date: date,
                        value: value,
                        type: type,
                        status: status,
                        detail: detail
                    },
                    function (result) {
                        if (result == 1) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn success-flag" >Project ' + name + ' Successfully Edited</div>');
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
                $('#flag').html('<div class="warn error-flag">Error: Account Not Found <a href="account.php#addAccount" style="color:#48e4ce">Add</a> </div>');
                setTimeout(function () {
                    $('#flag').html('');
                }, 1500);
            }

            function reload() {
                location.reload();
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
        <style>
            body {
                display: block;
                background-color: #f7f7f7;
                padding: 0;
            }
        </style>
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
                <li>
                    <a href="account.php">
                        <img src="images/Forma-5.png"> <span>Accounts</span>
                    </a>
                </li>
                <li class="active">
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
            <li>
                <a href="account.php">
                    <img src="images/Forma-5.png"> <span>Accounts</span>
                </a>
            </li>
            <li class="active">
                <a href="project.php">
                    <img src="images/Forma-6.png"> <span>Projects</span>
                </a>
            </li>
        </ul>
        <div class="content col-md-12">
            <div id="flag"></div>
            <div class="contact">
                <h1>Projects</h1>
                <div class="top"><a class="button" href="#addProject">New Project</a>
                </div>
                <div class="panel">
                    <div class="panel-body no-padding">
                        <table class="table table-condensed contact-table">
                            <tbody>
                                <tr class="panel-heading">
                                    <th>Name</th>
                                    <th>Contact Name</th>
                                    <th class="hide1">Company</th>
                                    <th class="hide1">Type</th>
                                    <th>Status</th>
                                    <th class="hide1">Source</th>
                                    <th class="hide1">Value</th>
                                    <th class="hide2">Expected Completion</th>
                                    <th class="hide1">Owner</th>
                                    <th class="hide2">Action</th>
                                </tr>
                                <?php
                    $sql=mysql_query('select * from project_data');
                    if (mysql_num_rows($sql)<1){
                        $output.="<center><h3>Tidak Ada Project Aktif</h3></center>";
                        echo $output;
                    }
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqlx = "SELECT * FROM contact_data,company_data,user_data WHERE contact_data.contact_ID= '$data[Contact_ID]' and company_data.company_ID='$data[Company_ID]'
and user_data.user_ID='$data[Project_Owner_ID]'";
                        $queryx = mysql_query($sqlx) or trigger_error("error" . mysql_error());
                        $datax = mysql_fetch_assoc($queryx);
                        $output.='<tr class="clickable-row" data-href="project-detail.php?prid='.$data['Project_ID'].'">
                                    <td>'.$data['Project_Name'].'</td>
                                    <td>'.$datax['Name'].'</td>
                                    <td class="hide1">'.$datax['Company_Name'].'</td>
                                    <td class="hide1">'.$data['Type'].'</td>
                                    <td>'.$data['Status'].'</td>
                                    <td class="hide1">'.$data['Source'].'</td>
                                    <td class="hide1">'.$data['Value'].'</td>
                                    <td class="hide2">'.$data['Expected_Completion'].'</td>
                                    <td class="hide1">'.$datax['username'].'</td>
                                    <td class="action hide2"><a href=project.php?prid='.$data['Project_ID'].'#editProject><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
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
                        <a class="square red-link"><img src="images/refresh-time.png">
                            <br>Import Prospects</a>
                        <a class="square green-link"><img src="images/Checkbox.png">
                            <br>Mass Delete Prospect</a>
                    </div>
                </div>

            </div>
        </div>
        <!-- Pop Up -->

        <div id="addProject" class="overlay">
            <div class="popup">
                <div class="red-header">
                    <h2>New <span>Project</span></h2>
                    <a class="close" href="#">&times;</a>
                </div>
                <div class="content-pop">
                    <form class="contact-form" ">
                <div class="column ">
                    <label>Project Name</label><input required type="text " placeholder="Project Name " id="project_name " ><br>
                    <label>Associated Contact Name</label><input type="text " placeholder="Contact Name " id="contact_name " value="<?php echo $contact_name ?>">
                        <br>
                        <label>Project Type</label>
                        <input type="text" placeholder="Type" id="type">
                        <br>
                </div>
                <div class="column">
                    <label>Project Value</label>
                    <input type="number" placeholder="Value of the Project" id="value" value="<?php echo $value ?>">
                    <br>
                    <label>Est Completion Date</label>
                    <input type="date" id="completion_date">
                    <br>
                    <label>Project Deail</label>
                    <textarea placeholder="Description" id="detail"></textarea>
                    <br>
                    <label>Owner</label>
                    <input readonly value="<?php echo $_SESSION['username'] ?>" class="readonly" id="creator">
                    <br>
                    <br>
                    <button type="button" class="button" id="addProjectButton">Save</button>
                    <a class="button" href="#">Cancel</a>
                </div>
                </form>
            </div>
        </div>
        </div>
        <div id="editProject" class="overlay">
            <?php
    $sql=mysql_query("select * from project_data WHERE Project_ID ='$prid' ");
    $data=mysql_fetch_assoc($sql);
    $sqlx=mysql_query("select * from contact_data WHERE Contact_ID ='$data[Contact_ID]'");
    $datax=mysql_fetch_assoc($sqlx);
    $output="";
    $output.='<div class="popup">
        <div class="red-header">
            <h2>Edit <span>Project</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form" method="post">
                <div class="column">
                    <label>Contact</label><input type="text" placeholder="Contact Name" id="econtact" readonly value="'.$datax['Name'].'"><br>
                    <label>Project Name</label><input type="text" placeholder="Project Name" id="eproject_name" readonly value="'.$data['Project_Name'].'"><br>
                    <label>Source</label><input type="text" placeholder="Source" id="esource" value="'.$data['Source'].'"><br>
                    <label>Value</label><input type="text" placeholder="Value" id="evalue" value="'.$data['Value'].'">
                    <input type="text" hidden id="estatus" value="'.$data['Status'].'">
                </div>
                <div class="column">
                    <label>Type</label><input  type="text" id="etype" value="'.$data['Type'].'"><br>
                    <label>Est Completion Date</label><input type="date" id="ecompletion_date" ><br>
                    <label>Type</label><textarea id="edetail" >'.$data['Detail'].'</textarea><br>
                    <button type="button" class="button" id="editProjectButton">Edit</button>
                    <a class="button" href="#">Cancel</a>
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
                            <h3 style="margin-bottom:22px">Are you sure want to add Project?
                <br><button type="button" class="button" id="ConfirmationButton" style="margin-left:40px">Add Project</button>
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