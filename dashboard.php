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
$uid=$_SESSION['userid'];
?>
<html>
<head>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/popup.css" media="all" />
    <link rel="stylesheet" href="http://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.js"></script>
<!--    <script> Graph Failed -->
<!---->
<!--        new Chartist.Line('.ct-chart', {-->
<!--            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],-->
<!--            series: [-->
<!--                [12, 9, 7, 8, 5],-->
<!--                [2, 1, 3.5, 7, 3],-->
<!--                [1, 3, 4, 5, 6]-->
<!--            ]-->
<!--        }, {-->
<!--            fullWidth: true,-->
<!--            chartPadding: {-->
<!--                right: 40-->
<!--            }-->
<!--        });-->
<!--    </script>-->
    <script>
        $(document).ready(function() {
            //when button is clicked
            $('#addTaskButton').click(function(){
                addTask();
            });
            $('#addEventButton').click(function(){
                addEvent();
            });
        });
        function addTask(){
            var uid = '<?php echo $uid ?>';
            var date = $('#taskdue').val();
            var detail = $('#taskTitle').val();
            $.post("function_caller.php",{ action: 'addtask',uid:uid, detail:detail, date:date},
                function(result){
                    if(result == 1){
                        window.location.href="#";
                        $('#flag').html('<div class="warn success-flag" >Task Successfully Added</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                    else{
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >Task Failed to Add</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                });
        }
        function addEvent(){
            
            var uid = '<?php echo $uid ?>';
            var date = $('#eventdate').val();
            var start = $('#eventstart').val();
            var end = $('#eventend').val();
            var detail = $('#eventTitle').val();
            $.post("function_caller.php",{ action: 'addevent', uid:uid, detail:detail, date:date, start:start, end:end},
                function(result){
                    if(result == 1){
                        window.location.href="#";
                        $('#flag').html('<div class="warn success-flag" >Task Successfully Added</div>');
                        setTimeout(function(){
                            $('#flag').html('');
                        }, 1500);
                    }
                    else{
                        window.location.href="#";
                        $('#flag').html('<div class="warn error-flag" >Task Failed to Add</div>');
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
    <style>
        body{
            display: block;
            background-color: #f7f7f7;
            padding: 0;
        }
    </style>
</head>
<body>
<?php
//notification
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
<div class="topbar">
    <img class="logo" src="images/hellonemo-logo-small.png">
	<a href="#footer_nav" onclick="toggleNav(); return false;"><img class="menu_button" src="images/menu.png"></a>
	<div class="notif">
            <img src="images/icon1.png">
            <img src="images/icon2.png">
            <a href="#footer_nav" onclick="toggleNotif(); return false;"><?php if($num > 0) {echo'<img src="images/icon3-red.png">';} else {echo '<img src="images/icon3.png">';} ?></a></a>
			<div class="container_popup">
<div class="arrow_up"></div>
<div class="popupnotif" id="popupnotif">
<div class="popup_count">You have <?php echo $num ?> unread notifications</div>
<table border-spacing=0>


            <?php
            while($data=mysql_fetch_array($sqlnotif)){
                $output ='';
                $output.='
<tr>
		<td class="popup_img">
		<img src="images/notif/notif1.png" />
		</td>
                <td class="popup_desc">
        '.$data['detail'].'
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
            <a href="">
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
        <li>
            <a href="#">
                <img src="images/Forma-7.png"> <span>Reports</span>
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
            <a href="">
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
        <li>
            <a href="#">
                <img src="images/Forma-7.png"> <span>Reports</span>
            </a>
        </li>
        </ul>
<div class="main">
    <div id="flag"></div>
    <div class="task">
        <h1>Recent Task
            <div class="d-button"><a class="button" href="#addTask">New Task</a></div></h1>
        <div class="panel">
            <div class="panel-body no-padding">
                <table class="table table-condensed task-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Subject</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>

                        <?php
                        $sql=mysql_query("select * from task_data where User_ID = $uid");
                        while($data=mysql_fetch_array($sql)){
                            $output ='';
                            $output.='<tr>
                                    <td>'.$data['detail'].'</td>
                                    <td>'.$data['due_date'].'</td>
                                    <td class="action"><a href="#"<img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                            echo $output;
                        }
                        ?>



                    </tbody></table>
            </div><!-- /.panel-body -->
        </div>
    </div>
    <div class="event">
        <div>
            <h1>Schedule Events<div class="d-button"><a class="button" href="#addEvent">New Event</a></div></h1>
        </div>
        <div class="panel">
            <div class="panel-body no-padding">
                <table class="table table-condensed event-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Today</th>
                        <th>Time for Event</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                     <?php
                        $sql=mysql_query("select * from event_data");
                        while($data=mysql_fetch_array($sql)){
                            $output ='';
                            $output.='<tr>
                                    <td>'.$data['detail'].'</td>
                                    <td>'.$data['start'].' to '.$data['end'].'</td>
                                    <td>'.$data['due_date'].'</td>
                                    <td class="action"><a href="#"<img src="images/Edit-Icon.png"></a><img src="images/Delete-Icon.png"></td>
                                </tr>';
                            echo $output;
                        }
                        ?>


                    </tbody></table></div></div>
    </div>
    </div>
<div id="addTask" class="overlay">
    <div class="popup-small">
        <div class="red-header">
            <h2>New<span> Task</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form">
                <div class="column">
                    <label>Task Detail</label><input type="text" placeholder="Title" id="taskTitle" required><br>
                    <label>Due Time</label><input type="datetime-local" placeholder="Due Date" id="taskdue" required><br>
                    <button type="button" class="button" id="addTaskButton">Save</button>
                    <a class="button" href="#">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    </div>
    <div id="addEvent" class="overlay">
    <div class="popup-small">
        <div class="red-header">
            <h2>New<span> Event</span></h2>
            <a class="close" href="#">&times;</a>
        </div>
        <div class="content-pop">
            <form class="contact-form">
                <div class="column">
                    <label>Event Title</label><input type="text" placeholder="Title" id="eventTitle" required><br>
                    <label>From</label><input type="time" placeholder="Start" id="eventstart" required> To 
                    <label>Until</label><input type="time" placeholder="Over" id="eventend" required><br>
                    <label>Date</label><input type="date" placeholder="Due Date" id="eventdate" required>
                    <button type="button" class="button" id="addEventButton">Save</button>
                    <a class="button" href="#">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
</body>


<script>
//Notification/ Notification have 4 status type (Pending, Unread, Read, Done)
//var myVar = setInterval(reload, 1000);  //refresh notification every 1s
//
//function reload(){
//            var container = document.getElementById("notifx").contentWindow.location.reload(true);
//        }
</script>
</html>
Status 