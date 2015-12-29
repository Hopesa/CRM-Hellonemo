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
    <link rel="stylesheet" href="http://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="http://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
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

        function blank(){
            window.location.href="#";
            $('#flag').html('<div class="warn error-flag">Error</div>');
            setTimeout(function(){
                $('#flag').html('');
            }, 1500);
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
    <div class="search"><form>
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
        <center><p>Nashihuddin</p></center>
        <center><button>Logout</button></center>
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
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>

        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>
        <li>
            <a href="">
                <img src="images/Forma-2.png"> <span>Contacts</span>
            </a>
        </li>
    </ul>
</div>
<div class="main">
    <div id="flag"></div>
    <div class="task">
        <h1>Recent Task
            <a class="button" href="#addTask">New Task</a></h1>
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
            <h1>Schedule Events<button>New Event</button><button> Switch to Meetings</button></h1>
        </div>
        <div class="calendar"></div>
        <div class="panel">
            <div class="panel-body no-padding">
                <table class="table table-condensed event-table">
                    <tbody>
                    <tr class="panel-heading">
                        <th>Today</th>
                        <th>Started At</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td class="subject">Software Update</td>
                        <td>
                            11.00 - 12.00
                        </td>
                        <td class="action"><img src="images/Edit-Icon.png"><img src="images/Delete-Icon.png"</td>
                    </tr>


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
                    <label>Username</label><input type="text" placeholder="Title" id="taskTitle" required><br>
                    <label>Role</label><input type="datetime-local" placeholder="Due Date" id="taskdue" required><br>
                    <button type="button" class="button" id="addTaskButton">Save</button>
                    <a class="button" href="#">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
</body>
</html>