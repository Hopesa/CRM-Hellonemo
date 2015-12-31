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
	<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
	<link rel="manifest" href="images/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!-- end of favicon -->
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://fian.my.id/marka/static/marka/js/marka.js"></script>
    <script src="js/filter.js"></script>
	<script type="text/javascript" src="js/modernizr.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript">


        $(document).ready(function() {



            //result texts
            var error_flag = 'Company Failed to add';

            //when button is clicked

            $('#addCompanyButton').click(function(){
                    addnewcompany();
            });
            $('#addContactButton').click(function(){
                    check_availability();
            });
            $('#editContactButton').click(function(){
                    edit_contact();
            });



        });
        function addnewcompany(){

            var company = $('#companyname').val();
            var industry = $('#industry').val();
            var ctelephone = $('#ctelephone').val();
            var caddress = $('#caddress').val();
            var cemail = $('#cemail').val();
            var cdesc = $('#cdesc').val();

            $.post("function_caller.php",{action: 'addnewcompany', company:company, industry:industry, ctelephone:ctelephone, caddress:caddress, cemail:cemail, cdesc:cdesc },
                function(result){
                    if (result == 1) {
                        $('#error_flag').html('Company ' +company+ ' Successfully ' +result+ ' added');
                    }
                    else if (result == 2){
                        $('#error_flag').html('Your SQL Probs error');
                    }
                    else {
                        $('#error_flag').html('Error : Company ' +company+ ' failed' +result+ ' to add');
                    }
                });
        }
        //function to check availability
        function check_availability(){

            //get the values
            var company = $('#company').val();

            //use ajax to run the check
            $.post("check.php", { action:'company',company: company },
                function(result){
                    //if the result is 1
                    if(result == 1){
                        //proceeds input contact
                        add_contact();
                    }
                    else{
                        //show addCompany form
                        blank();
                    }
                });

        }
        function add_contact(){
            var company = $('#company').val();
            var name = $('#name').val();
            var tele = $('#tele').val();
            var email = $('#email').val();
            var owner = $('#owner').val();

            $.post("function_caller.php",{ action: 'addcontact', name: name, company:company, tele:tele, email:email, owner:owner},
                function(result){

                    if(result == 1){
                        window.location.href="contact.php#addCompany";
                        $('#error_flag').html('Contact Successfully Added');
                    }
                    else if(result == 2){
                        window.location.href="contact.php#addCompany";
                        $('#error_flag').html('Function Error');
                    }
                    else{
                        window.location.href="contact.php#addCompany";
                        $('#error_flag').html('Error : Contact Failed '+result+' to Add');
                    }
                });
        }
        function edit_contact(){
            var cid = '<?php echo $cid ?>'
            var name = $('#ename').val();
            var tele = $('#etele').val();
            var email = $('#eemail').val();
            var status = $('#estatus').val();

            $.post("function_caller.php",{ action: 'editcontact', cid:cid, name: name, tele:tele, email:email, status:status},
                function(result){
                    if(result == 1){
                        window.location.href="#";
                        $('#flag').html('<div class="warn success-flag" >Contact '+name+' Successfully Edited</div>');
                    }
                    else if(result == 2){
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >Error E</div>');
                    }
                    else{
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >Error</div>');
                    }
                });
        }
        function blank(){
            var company = $('#company').val();
            window.location.href="contact.php#addCompany";
            $('#error_flag').html('Error : Company '+company+' Not Found, Please add');
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
	<a href="#footer_nav" onclick="toggleNav(); return false;"><img class="menu_button" src="images/menu.png"></a>
    <div class="notif">
        <img src="images/icon1.png">
        <img src="images/icon2.png">
        <img src="images/icon3.png">
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
        <div class="top"><a class="button" href="#popup1">New Account</a>
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
                        $output.='<tr data-href="contact-detail.php?id='.$data['Contact_ID'].'">
                                    <td>'.$data['Name'].'</td>
                                    <td>'.$cdata['Company_Name'].'</td>
                                    <td class="hide2">'.$data['Telephone'].'</td>
                                    <td class="hide1">'.$data['Email'].'</td>
                                    <td>'.$data['Status'].'</td>
                                    <td class="hide2 action"><a href=contact.php?cid='.$data['Contact_ID'].'#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
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
                    <label>Name</label><input type="text" placeholder="Name" id="name"><br>
                    <label>Company</label><input type="text" placeholder="Company" id="company"><br>
                    <label>Telephone</label><input type="text" placeholder="Telephone" id="tele"><br>
                </div>
                <div class="column">
                    <label>Email</label><input type="text" placeholder="Email" id="email"><br>
                    <label>Reported To</label><input type="text" placeholder="Reported" id="reportedto"><br>
                    <label>Owner</label><input readonly value="<?php echo $_SESSION['username']?>" class="readonly" id="owner"><br><br>
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
                    <label>Company Name</label><input type="text" placeholder="Company" id="companyname"><br>
                    <label>Industry</label><input type="text" placeholder="Industry" id="industry"><br>
                    <label>Telephone</label><input type="tel" placeholder="Telephone" id="ctelephone"><br>
                    <label>Address</label><input type="text" placeholder="address" id="caddress">
                </div>
                <div class="column">
                    <label>Email</label><input type="email" placeholder="email" id="cemail"><br>
                    <label>Description</label><textarea placeholder="description" id="cdesc"></textarea><br>
                    <br><br>
                    <button type="button" class="button" id="addCompanyButton">Save</button>
                    <a class="button" href="#">Cancel</a>
                    <a class="button" href="#">Add Prospect</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.document.location = $(this).data("href");
        });

    });
</script>
</body>
</html>