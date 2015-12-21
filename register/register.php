<?php
require($_SERVER['DOCUMENT_ROOT'].'/crm-native/includes/config.php');

if (isset($_GET['action'])) {
    switch (strtolower($_GET['action'])) {
        case 'register':
            // If the form was submitted lets try to create the account.
            if (isset($_POST['username']) && isset($_POST['pass'])) {
                if (createAccount($_POST['username'], $_POST['pass'])) {
                    $sOutput .= '<html><script>
           window.alert("Account Created")
           window.location.href="login.php";
       </SCRIPT></html>';
                } else {
                    // unset the action to display the registration form.
                    unset($_GET['action']);
                }
            } else {
                echo "Username and or Password was not supplied.";
                unset($_GET['action']);
            }
            break;
    }
}
else {
    $sError = "";
    if (isset($_SESSION['error'])) {
        $sError = '<span id="error">error</span><br />';
        echo $sError;
    }
}