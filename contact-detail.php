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
                    <input type="text" value="'.$data['Name'].'" class="readonly">
                    <br>
                    <label>Email</label>
                    <input type="text" value="'.$datax['Company_Name'].'" class="readonly">
                    <br>
                    <label>Telephone</label>
                    <input readonly value="'.$data['Telephone'].'" class="readonly">
                    <br>
                    <label>Address</label>
                    <input type="text" value="'.$datax['Address_ID'].'" class="readonly">



                </div>
                <div class="column" style="margin-left:100px; margin-top:35px;">
                    <label>Email</label>
                    <input type="text" value="'.$data['Email'].'" class="readonly">
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

            <div><a class="button" href="#popup1" style="margin: -35px 0px 0px 390px;">Send eMail</a>

                <table class="table table-condensed detail-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Date</th>
                        <th>Activity</th>
                        <th>Subject</th>

                    </tr>
                    <tr class="clickable-row">
                        <td>12/21/2015</td>
                        <td>Nashihuddin Bilal</td>
                        <td class="action"><img src="images/Edit-Icon.png"><img src="images/Delete-Icon.png"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="popup1" class="overlay">
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
<script>
    jQuery(document).ready(function ($) {
        $(".clickable-row").click(function () {
            window.document.location = $(this).data("href");
        });
    });
</script>
</body>

</html>
