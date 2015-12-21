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
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet prefetch" href="http://fian.my.id/marka/static/marka/css/marka.css">
    <link rel="stylesheet" href="css/filter.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://fian.my.id/marka/static/marka/js/marka.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
    $(function() {
    $('#panel').hide();
});
  
$('body').on('click','#nav li a', function(e) {
    $('#panel').show();
    $('#panel').children().hide();
    $($(this).attr('href')).show();
    e.preventDefault();
});
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
            <h1>Search Result</h1>
            <div class="nav-small">
                <ul id="nav">
                    <li><a href="#contact">Contact</a></li>
                    <li>Leads</li>
                    <li>Prospect</li>
                    <li>Account</li>
                    <li>Projects</li>
                </ul>
            </div>
            
        <div class="panel" id="panel">
            <div class="panel-body no-padding" id="contact">
                <table class="table table-condensed contact-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Name</th>
                        <th>Company</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $sql=mysql_query("select * from contact_data where contact_data.name like '%$search%' or contact_data.email like '%$search%'");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqla=mysql_query("select * from company_data WHERE Company_ID='$data[Company_ID]'");
                        $cdata=mysql_fetch_assoc($sqla);
                        $output.='<tr data-href="contact-detail.php?id='.$data['Contact_ID'].'">
                                    <td>'.$data['Name'].'</td>
                                    <td>'.$cdata['Company_Name'].'</td>
                                    <td>'.$data['Telephone'].'</td>
                                    <td>'.$data['Email'].'</td>
                                    <td>'.$data['Status'].'</td>
                                    <td class="action"><a href=contact.php?cid='.$data['Contact_ID'].'#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
?>
                    </tbody></table>
            </div>
            <div class="panel-body no-padding" id="prospect">
                <table class="table table-condensed contact-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Name</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Source</th>
                        <th>Expiration Date</th>
                        <th>Creator</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $sql=mysql_query("select * from contact_data where name like '%$search%'");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqlx=mysql_query("select * from prospect_data,company_data WHERE prospect_data.Contact_ID='$data[Contact_ID]' and company_data.Company_ID ='$data[Company_ID]'");
                        $datax=mysql_fetch_assoc($sqlx);
                        $comp = mysql_query("select * from company_data,user_data WHERE company_data.Company_ID='$datax[Company_ID]' and user_data.User_ID='$datax[Prospect_Owner_ID]'");
                        $compdata=mysql_fetch_assoc($comp);
                        $output.='<tr data-href="contact-detail.php?id='.$data['Contact_ID'].'">
                                    <td>'.$data['Name'].'</td>
                                    <td>'.$compdata['Company_Name'].'</td>
                                    <td>'.$datax['Status'].'</td>
                                    <td>'.$datax['Potential_Value'].'</td>
                                    <td>'.$datax['Source'].'</td>
                                    <td>'.$datax['Expiration'].'</td>
                                    <td>'.$compdata['username'].'</td>
                                    <td class="action"><a href=contact.php?cid='.$data['Contact_ID'].'#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
?>
                    </tbody></table>
            </div>
            <div class="panel-body no-padding" id="Leads">
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
                    <?php
                    $sql=mysql_query("select * from contact_data where name like '%$search%'");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqlx=mysql_query("select * from leads_data,company_data WHERE leads_data.Contact_ID='$data[Contact_ID]' and company_data.Company_ID ='$data[Company_ID]'");
                        $datax=mysql_fetch_assoc($sqlx);
                        $comp = mysql_query("select * from company_data,user_data WHERE company_data.Company_ID='$datax[Company_ID]' and user_data.User_ID='$datax[Leads_Creator_ID]'");
                        $compdata=mysql_fetch_assoc($comp);
                        $output.='<tr data-href="contact-detail.php?id='.$data['Contact_ID'].'">
                                    <td>'.$data['Name'].'</td>
                                    <td>'.$compdata['Company_Name'].'</td>
                                    <td>'.$datax['Status'].'</td>
                                    <td>'.$datax['Potential_Value'].'</td>
                                    <td>'.$datax['Source'].'</td>
                                    <td>'.$datax['Expiration'].'</td>
                                    <td>'.$compdata['username'].'</td>
                                    <td class="action"><a href=contact.php?cid='.$data['Contact_ID'].'#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
                    ?>
                    </tbody></table>
            </div>
            <div class="panel-body no-padding" id="account">
                <table class="table table-condensed contact-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Owner</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $sql=mysql_query("select * from contact_data where name like '%$search%'");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqlx=mysql_query("select * from account_data,company_data WHERE account_data.Contact_ID='$data[Contact_ID]' and company_data.Company_ID ='$data[Company_ID]'");
                        $datax=mysql_fetch_assoc($sqlx);
                        $comp = mysql_query("select * from company_data,user_data WHERE company_data.Company_ID='$datax[Company_ID]' and user_data.User_ID='$datax[Account_Creator_ID]'");
                        $compdata=mysql_fetch_assoc($comp);
                        $output.='<tr data-href="contact-detail.php?id='.$data['Contact_ID'].'">
                                    <td>'.$data['Name'].'</td>
                                    <td>'.$compdata['Company_Name'].'</td>
                                    <td>'.$datax['Email'].'</td>
                                    <td>'.$datax['Type'].'</td>
                                    <td>'.$datax['Status'].'</td>
                                    <td>'.$datax['Value'].'</td>
                                    <td>'.$compdata['username'].'</td>
                                    <td class="action"><a href=contact.php?cid='.$data['Contact_ID'].'#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
                    ?>
                    </tbody></table>
            </div>
            <div class="panel-body no-padding" id="project">
                <table class="table table-condensed contact-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Name</th>
                        <th>Contact Name</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Value</th>
                        <th>Expected Completion</th>
                        <th>Owner</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $sql=mysql_query("select * from contact_data where name like '%$search%'");
                    while($data=mysql_fetch_array($sql)){
                        $output ='';
                        $sqlx=mysql_query("select * from project_data,company_data WHERE project_data.Contact_ID='$data[Contact_ID]' and company_data.Company_ID ='$data[Company_ID]'");
                        $datax=mysql_fetch_assoc($sqlx);
                        $comp = mysql_query("select * from company_data,user_data WHERE company_data.Company_ID='$datax[Company_ID]' and user_data.User_ID='$datax[Project_Owner_ID]'");
                        $compdata=mysql_fetch_assoc($comp);
                        $output.='<tr data-href="contact-detail.php?id='.$data['Contact_ID'].'">
                                    <td>'.$datax['Project_Name'].'</td>
                                    <td>'.$data['Name'].'</td>
                                    <td>'.$compdata['Company_Name'].'</td>
                                    <td>'.$datax['Type'].'</td>
                                    <td>'.$datax['Status'].'</td>
                                    <td>'.$datax['Source'].'</td>
                                    <td>'.$datax['Value'].'</td>
                                    <td>'.$datax['Expected_Completion'].'</td>
                                    <td>'.$compdata['username'].'</td>
                                    <td class="action"><a href=contact.php?cid='.$data['Contact_ID'].'#editContact><img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                        echo $output;
                    }
                    ?>
                    </tbody></table>
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