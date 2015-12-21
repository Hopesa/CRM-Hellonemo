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
$prid = '';
if (isset($_GET['prid'])){
    $prid = $_GET['prid'];
}
$sql=mysql_query("select * from project_data WHERE Project_ID ='$prid' ");
$data=mysql_fetch_assoc($sql);
$sqlx=mysql_query("SELECT * FROM contact_data,company_data,user_data WHERE contact_data.contact_ID= '$data[Contact_ID]' and company_data.company_ID='$data[Company_ID]'
and user_data.user_ID='$data[Project_Owner_ID]'");
$datax=mysql_fetch_assoc($sqlx);
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

            <div class="detail">
                <h1>Project Detail</h1>
                <div class="top">
                    <ul class="breadcrumb no-padding">
                        <li>Home</li>
                        <li>Project</li>
                        <li>{{Project_Name}}</li>
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
                    <input type="text" value="'.$datax['Company_Name'].'" class="readonly">
                    <br>
                    <label>Email</label>
                    <input readonly value="'.$datax['Email'].'" class="readonly">
                    <br>
                     <label>Status</label>
                    <input readonly value="'.$data['Status'].'" class="readonly">

                </div>
                <div class="column" style="margin-left:100px; margin-top:35px;"> <

                    <label>Type</label>
                    <input type="text" value="'.$data['Type'].'" class="readonly">
                    <br>
                    <label>Value</label>
                    <input type="text" value="'.$data['Value'].'" class="readonly">
                    <br>
                    <label>Description</label>
                        <p class="readonly description">'.$data['Detail'].'</p>
                </div>';
                echo $output;

                ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="email" class="overlay">
            <div class="popup">
                <div class="red-header">
                    <h2>Send<span> Email</span></h2>
                    <a class="close" href="#">&times;</a>
                </div>
                <div class="content-pop">
                    <form class="detail-form">
                        <div class="column">
                            <label>To</label>
                            <input type="text" placeholder="Prospect">
                            <br>
                            <label style="margin-top:-340px;">Body</label>
                            <textarea placeholder="Prospect"></textarea>
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
                                <button class="button">Send</button>
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
                            <label>Title</label>
                            <input type="text" placeholder="Prospect">
                            <br>
                            <label>Unit 1</label>
                            <input type="text" placeholder="Prospect">
                            <br>
                            <label>Description</label>
                            <input type="text" placeholder="Type">
                            <br>
                            <label>Cost</label>
                            <input type="text" placeholder="Type">
                            <br>
                            <label>Total</label>
                            <input type="text" placeholder="Type">
                            <br>
                            <br>
                            <button class="button">Add Unit</button>
                            <button class="button">Save</button>
                            <a class="button" href="#">Cancel</a>
                            <br>
                            <br>
                        </div>
                        <div class="column">
                            <label>Email</label>
                            <input type="text" placeholder="Prospect">
                            <br>
                            <label>Reported To</label>
                            <input type="text" placeholder="Type">
                            <br>
                            <label>Owner</label>
                            <textarea></textarea>
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