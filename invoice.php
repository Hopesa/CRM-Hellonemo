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
if (isset($_GET['aid'])){
$aid = $_GET['aid'];
}
$sql=mysql_query("select * from account_data WHERE Account_ID ='$aid' ");
$data=mysql_fetch_assoc($sql);
$sqlx=mysql_query("SELECT * FROM contact_data,user_data WHERE contact_data.contact_ID= '$data[Contact_ID]'
and user_data.user_ID='$data[Account_Creator_ID]'");
$datax = mysql_fetch_assoc($sqlx);
$cdata = mysql_fetch_assoc(mysql_query("SELECT * FROM company_data WHERE company_ID='$data[Company_ID]'")); //lel
?>
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="utf-8">-->
<!--    <title>Example 1</title>-->
<!--    <link rel="stylesheet" href="css/invoice.css" media="all" />-->
<!--</head>-->
<!--<body>-->
<!--<header class="clearfix">-->
<!--    <div id="logo">-->
<!--        <img src="images/hellonemo-logo.png">-->
<!--    </div>-->
<!--    <h1>INVOICE</h1>-->
<!--    <div id="company" class="clearfix">-->
<!--        <div>--><?php //echo $cdata['Company_Name'] ?><!--</div>-->
<!--        <div>--><?php //echo $datax['Telephone'] ?><!--</div>-->
<!--        <div>--><?php //echo $cdata['Email']?><!--</div>-->
<!--    </div>-->
<!--    <div id="project">-->
<!--        <div><span>CLIENT</span>--><?php //echo $datax['Name'] ?><!--</div>-->
<!--        <div><span>ADDRESS</span>--><?php //echo $cdata['Address'] ?><!--</div>-->
<!--        <div><span>EMAIL</span>--><?php //echo $datax['Email'] ?><!--</div>-->
<!--        <div><span>DATE</span>--><?php //$current_date = date('D d F Y');echo $current_date; ?><!--</div>-->
<!--    </div>-->
<!--</header>-->
<!--<main>-->
<!--    <table>-->
<!--        <thead>-->
<!--        <tr>-->
<!---->
<!--            <th>No</th>-->
<!--            <th class="service">SERVICE</th>-->
<!--            <th class="desc">DESCRIPTION</th>-->
<!--            <th>TYPE</th>-->
<!--            <th>COST</th>-->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        --><?php
//        $total = '';  //init total cost/value
//        $sqla=mysql_query("select * from project_data where Account_ID = $aid and status = 'done'");
//        $i = 1;
//        while($dataa=mysql_fetch_array($sqla)){
//            $output ='';
//            $output.='<tr>    <td class="service">'.$i.'</td>
//                                                        <td class="service">'.$dataa['Project_Name'].'</td>
//                                                        <td class="desc">'.$dataa['Detail'].'</td>
//                                                        <td class="unit">'.$dataa['Type'].'</td>
//                                                        <td class="total">'.$dataa['Value'].' IDR</td>
//                                                        </tr>
//                                                        ';
//            $total = $total + $dataa['Value'];
//            $i++;
//            echo $output;
//        }
//        ?>
<!--        <tr>-->
<!--            <td colspan="4" class="grand total">Total</td>-->
<!--            <td class="grand total">--><?php //echo $total?><!--</td>-->
<!--        </tr>-->
<!--        </tbody>-->
<!--    </table>-->
<!--    <div id="notices">-->
<!--        <div>NOTICE:</div>-->
<!--        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>-->
<!--    </div>-->
<!--</main>-->
<!--<footer>-->
<!--    Invoice was created on a computer and is valid without the signature and seal.-->
<!--</footer>-->
<!--</body>-->
<!--</html>-->
<?php $total = '';  //init total cost/value
$sqla=mysql_query("select * from project_data where Account_ID = $aid and status = 'done'");
$i = 1;
while($dataa=mysql_fetch_array($sqla)){
    $output.='<t?>    <td class="service" style="text-align: left; vertical-align: top; padding: 20px;" align="left" valign="top">'.$i.'</td>
                                                        <td class="service" style="text-align: left; vertical-align: top; padding: 20px;" align="left" valign="top">'.$dataa['Project_Name'].'</td>
                                                        <td class="desc" style="text-align: left; vertical-align: top; padding: 20px;" align="left" valign="top">'.$dataa['Detail'].'</td>
                                                        <td class="unit" style="text-align: right; font-size: 1.2em; padding: 20px;" align="right">'.$dataa['Type'].'</td>
                                                        <td class="total" style="text-align: right; font-size: 1.2em; padding: 20px;" align="right">'.$dataa['Value'].' IDR</td>
                                                        </tr>
                                                        ';
    $total = $total + $dataa['Value'];
    $i++;
}
$body = '';
$current_date = date('D d F Y');
$body.='
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Example 1</title>

</head>
<body style="position: relative; width: 21cm; height: 29.7cm; color: #001028; background-color: #FFFFFF; font-size: 12px; font-family: Arial; margin: 0 auto;" bgcolor="#FFFFFF">
<header class="clearfix" style="margin-bottom: 30px; padding: 10px 0;">
    <div id="logo" style="text-align: center; margin-bottom: 10px;" align="center">
        <img src="http://hopesa.github.io/images/hellonemo-logo.png" style="width: 90px;" />
    </div>
    <h1 style="margin: 0 0 20px; border-top-style: solid; border-top-width: 1px; border-top-color: #5D6975; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #5D6975; color: #5D6975; font-size: 2.4em; line-height: 1.4em; font-weight: normal; text-align: center; background-image: url("http://hopesa.github.io/images/dimension.png");" align="center">INVOICE</h1>
    <div id="company" class="clearfix" style="float: right; text-align: right;" align="right">
        <div style="white-space: nowrap;">'.$cdata['Company_Name'].'</div>
        <div style="white-space: nowrap;">'.$datax['Telephone'].'</div>
        <div style="white-space: nowrap;">'.$cdata['Email'].'</div>
    </div>
    <div id="project" style="float: left;">
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">CLIENT</span>'.$datax['Name'].'</div>
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">ADDRESS</span>'.$cdata['Address'].'</div>
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">EMAIL</span>'.$datax['Email'].'</div>
        <div style="white-space: nowrap;"><span style="color: #5D6975; text-align: right; width: 52px; margin-right: 10px; display: inline-block; font-size: 0.8em;">DATE</span>'.$current_date.'</div>
    </div>
</header>
<main>
    <table style="width: 100%; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;">
        <thead>
        <tr>

            <th style="text-align: center; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="center">No</th>
            <th class="service" style="text-align: left; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="left">SERVICE</th>
            <th class="desc" style="text-align: left; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="left">DESCRIPTION</th>
            <th style="text-align: center; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="center">TYPE</th>
            <th style="text-align: center; color: #5D6975; border-bottom-color: #C1CED9; border-bottom-width: 1px; border-bottom-style: solid; white-space: nowrap; font-weight: normal; padding: 5px 20px;" align="center">COST</th>
        </tr>
        </thead>
        <tbody>
        '.$output.'
        <tr>
            <td colspan="4" class="grand total" style="text-align: right; font-size: 1.2em; border-top-width: 1px; border-top-color: #5D6975; border-top-style: solid; padding: 20px;" align="right">Total</td>
            <td class="grand total" style="text-align: right; font-size: 1.2em; border-top-width: 1px; border-top-color: #5D6975; border-top-style: solid; padding: 20px;" align="right">$total</td>
        </tr>
        </tbody>
    </table>
    <div id="notices">
        <div>NOTICE:</div>
        <div class="notice" style="color: #5D6975; font-size: 1.2em;">Please Confirm to us after you made your payment</div>
    </div>
</main>
<footer style="color: #5D6975; width: 100%; height: 30px; position: absolute; bottom: 0; border-top-style: solid; border-top-width: 1px; border-top-color: #C1CED9; text-align: center; padding: 8px 0;">
    Invoice was created on a computer and is valid without the signature and seal. &copy Hellonemo Digital Agency 2013-2016
</footer>

<style type="text/css">
    .clearfix:after { content: "" !important; display: table !important; clear: both !important; }
    body { position: relative !important; width: 21cm !important; height: 29.7cm !important; margin: 0 auto !important; color: #001028 !important; background: #FFFFFF !important; font-size: 12px !important; font-family: Arial !important; }
</style>
</body>
</html>';
echo $body;
?>
