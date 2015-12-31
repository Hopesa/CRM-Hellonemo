<html>

<head>
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

?>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/contact.css">
        <link rel="stylesheet prefetch" href="http://fian.my.id/marka/static/marka/css/marka.css">
        <link rel="stylesheet" href="css/filter.css">

        <link rel="stylesheet" href="css/popup.css" media="all" />
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
        <script type="text/javascript" src="js/modernizr.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {



                //result texts
                var error_flag = 'Company Failed to add';

                //when button is clicked

                $('#addCompanyButton').click(function () {
                    addnewcompany();
                });
                $('#addContactButton').click(function () {
                    check_availability();
                });
                $('#editContactButton').click(function () {
                    edit_contact();
                });



            });

            function addnewcompany() {

                var company = $('#companyname').val();
                var industry = $('#industry').val();
                var ctelephone = $('#ctelephone').val();
                var caddress = $('#caddress').val();
                var cemail = $('#cemail').val();
                var cdesc = $('#cdesc').val();

                $.post("function_caller.php", {
                        action: 'addnewcompany',
                        company: company,
                        industry: industry,
                        ctelephone: ctelephone,
                        caddress: caddress,
                        cemail: cemail,
                        cdesc: cdesc
                    },
                    function (result) {
                        if (result == 1) {
                            $('#error_flag').html('Company ' + company + ' Successfully ' + result + ' added');
                        } else if (result == 2) {
                            $('#error_flag').html('Your SQL Probs error');
                        } else {
                            $('#error_flag').html('Error : Company ' + company + ' failed' + result + ' to add');
                        }
                    });
            }
            //function to check availability
            function check_availability() {

                //get the values
                var company = $('#company').val();

                //use ajax to run the check
                $.post("check.php", {
                        action: 'company',
                        company: company
                    },
                    function (result) {
                        //if the result is 1
                        if (result == 1) {
                            //proceeds input contact
                            add_contact();
                        } else {
                            //show addCompany form
                            blank();
                        }
                    });

            }

            function add_contact() {
                var company = $('#company').val();
                var name = $('#name').val();
                var tele = $('#tele').val();
                var email = $('#email').val();
                var owner = $('#owner').val();

                $.post("function_caller.php", {
                        action: 'addcontact',
                        name: name,
                        company: company,
                        tele: tele,
                        email: email,
                        owner: owner
                    },
                    function (result) {

                        if (result == 1) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn success-flag" >Contact: ' + name + ' Successfully Added</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                            reload();
                        } else if (result == 2) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >Contact: ' + name + ' Failed to Add</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);

                        } else {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >System Error</div>');
                            setTimeout(function () {
                                $('#flag').html('');
                            }, 1500);
                        }
                    });
            }

            function edit_contact() {
                var cid = '<?php echo $cid ?>'
                var name = $('#ename').val();
                var tele = $('#etele').val();
                var email = $('#eemail').val();
                var status = $('#estatus').val();

                $.post("function_caller.php", {
                        action: 'editcontact',
                        cid: cid,
                        name: name,
                        tele: tele,
                        email: email,
                        status: status
                    },
                    function (result) {
                        if (result == 1) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn success-flag" >Contact ' + name + ' Successfully Edited</div>');
                            reload();
                        } else if (result == 2) {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >Error E</div>');
                        } else {
                            window.location.href = "#";
                            $('#flag').html('<div class="warn error-flag" >Error</div>');
                        }
                    });
            }

            function blank() {
                var company = $('#company').val();
                window.location.href = "contact.php#addCompany";
                $('#error_flag').html('Error : Company ' + company + ' Not Found, Please add');
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
    <div class="content">
        <div id="flag"></div>
        <div class="contact">
            <h1>Contact</h1>
            <div class="top"><a class="button" href="#popup1">New Contact</a>
            </div>
            <div class="panel">
                <div class="panel-body no-padding">
                    <table class="table table-condensed contact-table">
                        <tbody>
                            <tr class="panel-heading">
                                <th>Name</th>
                                <th>Company</th>
                                <th class="hide2">Telephone</th>
                                <th class="hide1">Email</th>
                                <th>Status</th>
                                <th class="hide2">Action</th>
                            </tr>
                            <?php
                    $sql=mysql_query('select * from contact_data');
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqla=mysql_query("select * from company_data WHERE Company_ID='$data[Company_ID]'");
                        $cdata=mysql_fetch_assoc($sqla);
                        $output.='<tr>
                                    <td class="clickable-row" data-href="contact-detail.php?cid='.$data['Contact_ID'].'">'.$data['Name'].'</td>
                                    <td class="clickable-row" data-href="contact-detail.php?cid='.$data['Contact_ID'].'">'.$cdata['Company_Name'].'</td>
                                    <td class="hide2 clickable-row" data-href="contact-detail.php?cid='.$data['Contact_ID'].'">'.$data['Telephone'].'</td>
                                    <td class="hide1 clickable-row" data-href="contact-detail.php?cid='.$data['Contact_ID'].'">'.$data['Email'].'</td>
                                    <td class="clickable-row" data-href="contact-detail.php?cid='.$data['Contact_ID'].'">'.$data['Status'].'</td>
                                    <td class="hide2 action"><a href=contact.php?cid='.$data['Contact_ID'].'#editContact><img src="images/Edit-Icon.png"></a><a href=contact.php?cid='.$data['Contact_ID'].'#confirmdel><img src="images/Delete-Icon.png"</a></td>
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
                </div>
            </div>
        </div>
    </div>
    <!-- Pop Up New Account -->

    <div id="popup1" class="overlay">
        <div class="popup">
            <div class="red-header">
                <h2>New <span>Contact</span></h2>
                <a class="close" href="#">&times;</a>
            </div>
            <div class="content-pop">
                <form class="contact-form" method="post">
                    <div class="column">
                        <label>Name</label>
                        <input required type="text" placeholder="Name" id="name">
                        <br>
                        <label>Company</label>
                        <input required type="text" placeholder="Company" id="company">
                        <br><a class="button" style="margin-left:140px; width:200px" href="#addCompany">Add New Company</a>
                        <br>
                        <label>Telephone</label>
                        <input type="text" placeholder="Telephone" id="tele">
                        <br>
                    </div>
                    <div class="column">
                        <label>Email</label>
                        <input required type="text" placeholder="Email" id="email">
                        <br>

                        <label>Owner</label>
                        <input readonly value="<?php echo $_SESSION['username']?>" class="readonly" id="owner">
                        <br>
                        <br>
                        <button type="button" class="button" id="addContactButton">Save</button>
                        <a class="button" href="#">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="editContact" class="overlay">
        <?php
$sql=mysql_query("select * from contact_data WHERE Contact_ID ='$cid' ");
    $data=mysql_fetch_assoc($sql);
    $output="";
    $output.='<div class="popup">
        <div class="red-header">
            <h2>Edit <span>Contact</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form" method="post">
                <div class="column">
                    <label>Name</label><input type="text" placeholder="Name" id="ename" value="'.$data['Name'].'"><br>
                    <label>Telephone</label><input type="text" placeholder="Telephone" id="etele" value="'.$data['Telephone'].'"><br>
                    <input type="text" hidden id="estatus" value="'.$data['Status'].'">
                </div>
                <div class="column">
                    <label>Email</label><input  type="text" placeholder="Email" id="eemail" value="'.$data['Email'].'"><br>
                    <button type="button" class="button" id="editContactButton">Save</button>
                    <a class="button" href="#">Cancel</a>
                    <a class="button" href="prospect.php?cid='.$data['Contact_ID'].'#addProspect">Add to prospect</a>
                </div>
            </form>
        </div>
    </div>
</div>';
echo $output;
?>
            <div id="addCompany" class="overlay">
                <div class="warn" id="error_flag"></div>
                <div class="popup">
                    <div class="red-header">
                        <h2>New<span>Company</span></h2>
                        <a class="close" href="#">&times;</a>
                    </div>
                    <div class="content-pop">
                        <form class="contact-form" method="post">
                            <div class="column">
                                <label>Company Name</label>
                                <input type="text" placeholder="Company" id="companyname">
                                <br>
                                <label>Industry</label>
                                <input type="text" placeholder="Industry" id="industry">
                                <br>
                                <label>Telephone</label>
                                <input type="tel" placeholder="Telephone" id="ctelephone">
                                <br>
                                <label>Address</label>
                                <input type="text" placeholder="address" id="caddress">
                            </div>
                            <div class="column">
                                <label>Email</label>
                                <input type="email" placeholder="email" id="cemail">
                                <br>
                                <label>Description</label>
                                <textarea placeholder="description" id="cdesc"></textarea>
                                <br>
                                <br>
                                <br>
                                <button type="button" class="button" id="addCompanyButton">Save</button>
                                <a class="button" href="#">Cancel</a>
                                <a class="button" href="#">Add Prospect</a>
                            </div>
                        </form>
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
                        <h3 style="margin-bottom:22px">Are you sure want to delete this contact??<center><br><a  class="button" href=delete.php?action=contact&id=<?php echo $cid ?> style="margin-left:40px">Yes</a>
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