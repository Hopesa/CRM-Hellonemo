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
$search='Null';
if (isset($_GET['search'])){
    $search = $_GET['search'];
}

?>
    <html>

    <head>

        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/settings.css">
        <link rel="stylesheet" href="css/filter.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script>
            //messy af
            $(document).ready(function () {
                $("#profile").hide();
                $("#security").hide();
                $("#template").hide();
                $("#data").hide();
                $("#buttonuser").click(function () {
                    $("#profile").hide();
                    $("#security").hide();
                    $("#template").hide();
                    $("#data").hide();
                    $("#user").toggle();
                });
                $("#buttonprofile").click(function () {
                    $("#user").hide();
                    $("#security").hide();
                    $("#template").hide();
                    $("#data").hide();
                    $("#profile").toggle();
                });
                $("#buttonsecurity").click(function () {
                    $("#profile").hide();
                    $("#user").hide();
                    $("#template").hide();
                    $("#data").hide();
                    $("#security").toggle();
                });
                $("#buttontemplate").click(function () {

                    $("#profile").hide();
                    $("#security").hide();
                    $("#user").hide();
                    $("#data").hide();
                    $("#template").toggle();
                });
                $("#buttondata").click(function () {

                    $("#profile").hide();
                    $("#security").hide();
                    $("#template").hide();
                    $("#user").hide();
                    $("#data").toggle();
                });
            });
        </script>
        <script>
            $(document).ready(function() {


                //when button is clicked

                $('#addUserButton').click(function(){
                    confirmation();
                });
                $('#ConfirmationButton').click(function(){
                    add_user();
                });

            });
            function confirmation(){
                var pass1 = $('#pass').val();
                var pass2 = $('#passrepeat').val();

                if (pass1 == pass2){
                    window.location.href="#confirmation";
                }
                else{
                    window.location.href="#";
                    $('#flag').html('<div class="warn error-flag" >Error : Password is not Similar</div>');
                    setTimeout(function(){
                        $('#flag').html('');
                    }, 1500);
                }
            }
            function add_user(){
                var username = $('#username').val();
                var pass = $('#pass').val();  //Potential Value

                $.post("function_caller.php",{ action: 'adduser', username:username, pass:pass},
                    function(result){

                        if(result == 1){
                            window.location.href="#";
                            $('#flag').html('<div class="warn success-flag" >Username: '+username+' Successfully Added</div>');
                            setTimeout(function(){
                                $('#flag').html('');
                            }, 1500);
                        }
                        else{
                            window.location.href="#";
                            $('#flag').html('<div class="warn error-flag" >'+username+' Failed to Add</div>');
                            setTimeout(function(){
                                $('#flag').html('');
                            }, 1500);
                        }
                    });
            }
            function blank(){
                window.location.href="#";
                $('#flag').html('<div class="warn error-flag">Error</div>');
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

                <li class="active">
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
            <div class="contact">
                <div id="flag"></div>
                <h1>Settings</h1>
                <div class="nav-small">
                    <ul id="nav">
                        <li id="buttonuser"><img src="images/icon-prospect.png"/>User</li>
                        <li id="buttonprofile"><img src="images/icon-prospect.png"/>Comp Profile</li>
                        <li id="buttonsecurity"><img src="images/icon-prospect.png"/>Security</li>
                        <li id="buttontemplate"><img src="images/icon-prospect.png"/>Templates</li>
                        <li id="buttondata"><img src="images/icon-prospect.png"/>Data Management</li>
                    </ul>
                </div>
<!--User Setting Menu-->
                <div class="panel" id="user"> 
                    <div class="panel-body no-padding col-md-5">
                        <h1>Users</h1>
                        <a style="margin:0% 0% 3% 80%" class="button" href="#addUser">New User</a>
                        <table class="table table-condensed contact-table">
                            <tbody>
                                <tr class="panel-heading">
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Last Changed</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                    $sql=mysql_query("select * from user_data");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $output.='<tr>
                                    <td>'.$data['username'].'</td>
                                    <td>'.$data['Role'].'</td>
                                    <td>'.$data['Last_modified'].'</td>
                                    <td class="action"><a href=setting.php?cid=#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
?>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-left:50px" class="panel-body no-padding col-md-5">
                        <h1>User Groups</h1>
                        <a style="margin:0% 0% 3% 80%" class="button" href="#addGroup">New Group</a>
                        <table class="table table-condensed contact-table">
                            <tbody>
                                <tr class="panel-heading">
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Last Changed</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                    $sql=mysql_query("select * from user_data");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $output.='<tr>
                                    <td>'.$data['username'].'</td>
                                    <td>'.$data['Role'].'</td>
                                    <td>'.$data['Last_modified'].'</td>
                                    <td class="action"><a href=setting.php?cid=#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
?>
                            </tbody>
                        </table>
                    </div>
<!--                    Popup-->
                            <!-- Pop Ups-->

        <div id="addUser" class="overlay">
            <div id="flag-confirmation"></div>
                <div class="popup-small">
                    <div class="red-header">
                        <h2>New <span>Lead</span></h2>
                        <a class="close" href="#">&times;</a>
                    </div>
                    <div class="content-pop">
                        <form class="contact-form">
                            <div class="column">
                                <label>Username</label><input type="text" placeholder="username" id="username"><br>
                                <label>Role</label><input type="text" placeholder="User Role" id="role"><br>
                                <label>Password</label><input type="password" placeholder="password" id="pass"><br>
                                <label>Repeat Password</label><input type="password" placeholder="Repeat" id="passrepeat"><br>
                                <button type="button" class="button" id="addUserButton">Save</button>
                                <a class="button" href="#">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
        <div id="confirmation" class="overlay">
            <div class="popup-small">
                <div class="red-header">
                    <h2><span>Confirmation</span></h2>
                    <a class="close" href="#">&times;</a>
                </div>
                <div class="content-pop">
                    <h3 style="margin-bottom:22px">Are you sure want to add this user??<center><br><button type="button" class="button" id="ConfirmationButton" style="margin-left:40px">Add User</button>
                            <a href="#" class="button">Cancel</a><br></center></h3>
                </div>
            </div>
        </div>
                </div>
<!--Company Profile Setting Menu-->
                <div class="panel" id="profile">
                    <div class="panel-body no-padding col-md-5">
                    <h1>Company Profile Details</h1>
                     <div class="detail">
                         <label>Company Name</label>
                        <input type="text" value="Hello Nemo" class="readonly">
                        <br>
                        <label>Primary Contact</label>
                        <input type="text" value="{{Value}}" class="readonly">
                        <br>
                        <label>Email</label>
                        <input readonly value="{{Value}}" class="readonly">
                        <br>
                        <label>Address</label>
                        <input type="text" value="{{Value}}" class="readonly">
                        <br>
                        <label>Location</label>
                        <input readonly value="{{Value}}" class="readonly">
                         <br>
                         <label>Timezone</label>
                        <input readonly value="{{Value}}" class="readonly">
                         <br>
                         <a class="button" href="#addGroup">Edit Company Profile</a>
                        </div>
                    </div>
                    <div style="margin-left:50px" class="panel-body no-padding col-md-5">
                    <h1>Change Fiscal Year</h1>
                        <div class="detail">
                            <label>Company Name</label>
                        <input type="text" value="Hello Nemo" class="readonly">
                        <br>
                        <label>Fiscal Year Starting Month</label>
                        <select>
                            <option>January</option>
                            <option>January</option>
                            <option>January</option>
                            <option>January</option>
                        </select>
                        <br>
                        <label>Email</label>
                        <input readonly value="{{Value}}" class="readonly">
                        <br>
                         <a class="button" href="#addGroup">Edit Fiscal Year</a>
                        </div>
                    </div>
                    
                    <div class="panel-body no-padding col-md-5">
                        <h1>Bussiness Hours</h1>
                        <a style="margin:0% 0% 3% 71.5%; width:130px" class="button" href="#addUsers">New Bussiness Hours</a>
                        <table class="table table-condensed contact-table">
                            <tbody>
                                <tr class="panel-heading">
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Last Changed</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                    $sql=mysql_query("select * from user_data");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $output.='<tr>
                                    <td>'.$data['username'].'</td>
                                    <td>'.$data['Role'].'</td>
                                    <td>'.$data['Last_modified'].'</td>
                                    <td class="action"><a href=setting.php?cid=#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
?>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-left:50px" class="panel-body no-padding col-md-5">
                        <h1>Holidays</h1>
                        <a style="margin:0% 0% 3% 80%" class="button" href="#addGroup">New Holidays</a>
                        <table class="table table-condensed contact-table">
                            <tbody>
                                <tr class="panel-heading">
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Last Changed</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                    $sql=mysql_query("select * from user_data");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $output.='<tr>
                                    <td>'.$data['username'].'</td>
                                    <td>'.$data['Role'].'</td>
                                    <td>'.$data['Last_modified'].'</td>
                                    <td class="action"><a href=setting.php?cid=#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
?>
                            </tbody>
                        </table>
                    </div>
                </div>
<!--Security Setting-->
                <div class="panel" id="security">
                    <div class="panel-body no-padding col-md-5">
                        <h1>Pass Policy</h1>
                        
                        <div class="detail label-big">
                        <label>User Pass expired in</label>
                        <input type="text" value="90 Days" class="readonly">
                        <br>
                        <label>Min Pass Length</label>
                        <input type="text" value="8 Characters" class="readonly">
                        <br>
                        <label>Max Invalid Login Attempts</label>
                        <input readonly value="10" class="readonly">
                        <br>
                        <label>Session Logout Period</label>
                        <input type="text" value="15 Minutes" class="readonly">
                        <br>
                        <label>Forgot Pass email</label>
                        <input readonly value="admin@hellonemo.com" class="readonly">
                         
                         <br>
                         <a class="button" href="#addGroup">Edit Policy</a>
                        </div>
                    </div>

                    <div style="margin-left:50px; height:400px" class="panel-body no-padding col-md-5">
                        <h1>User Groups</h1>
                        <a style="margin:0% 0% 3% 80%" class="button" href="#addGroup">New Group</a>
                        <table class="table table-condensed contact-table">
                            <tbody>
                            <tr class="panel-heading">
                                <th>Name</th>
                                <th>Role</th>
                                <th>Last Changed</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $sql=mysql_query("select * from user_data");
                            while($data=mysql_fetch_array($sql)){
                                $output ='';
                                $output.='<tr>
                                    <td>'.$data['username'].'</td>
                                    <td>'.$data['Role'].'</td>
                                    <td>'.$data['Last_modified'].'</td>
                                    <td class="action"><a href=setting.php?cid=#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                                echo $output;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tools contact-box col-md-5">
                        <h1>Tools</h1>
                        <div>
                            <a class="square red-link"><img src="images/icon-prospect.png"><br>Import Account/Contact</a>
                            <a class="square green-link"><img src="images/icon-prospect.png"><br>Import Leads/Prospect</a>
                            <a class="square yellow-link"><img src="images/icon-userefresh.png"><br>Export Data</a>
                        </div>
                    </div>
                </div>
<!--Template Management Menu-->
                <div class="panel" id="template">
                    <div class="panel-body no-padding col-md-5">
                        <h1>Mail Template</h1>
                        <a style="margin:0% 0% 3% 80%" class="button" href="#addUsers">New Mail Template</a>
                        <table class="table table-condensed contact-table">
                            <tbody>
                            <tr class="panel-heading">
                                <th>Name</th>
                                <th>Role</th>
                                <th>Last Changed</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $sql=mysql_query("select * from user_data");
                            while($data=mysql_fetch_array($sql)){
                                $output ='';
                                $output.='<tr>
                                    <td>'.$data['username'].'</td>
                                    <td>'.$data['Role'].'</td>
                                    <td>'.$data['Last_modified'].'</td>
                                    <td class="action"><a href=setting.php?cid=#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                                echo $output;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
<!--Data Management Menu-->
                <div class="panel" id="data">
                    <div class="tools contact-box col-md-7">
                        <h1>Tools</h1>
                        <div>
                            <a class="square red-link"><img src="images/icon-prospect.png"><br>Import Account/Contact</a>
                            <a class="square green-link"><img src="images/icon-prospect.png"><br>Import Leads/Prospect</a>
                            <a class="square yellow-link"><img src="images/icon-userefresh.png"><br>Export Data</a>
                            <a class="square blue-link"><img src="images/icon-trash.png"><br>Mass Delete Specific Record</a>
                            <a class="square purple-link"><img src="images/icon-trash.png"><br>Mass Delete Data</a>
                        </div>
                    </div>
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