<?php
require_once("config.php");
require_once(UTILITY_DIR."cdn_link.php");
require_once(UTILITY_DIR."check_login.php");
require_once(LIB_DIR."db/db_connection.php");
checkSession();
$pdo = pdo_connect();
$msg = '';
if(isset($_POST['btnSubmit']))
{
    $txtUserName = isset($_POST['txtUserName']) ? $_POST['txtUserName'] : '';
    $txtPassword = isset($_POST['txtPassword']) ? $_POST['txtPassword'] : '';
    if($txtUserName !='' && $txtPassword != '')
    {
        $data = [
            'user_name' => $txtUserName
        ];
        $selectQuery = "SELECT user_name, enc_password FROM user_details WHERE user_name = :user_name";
        $stmt= $pdo->prepare($selectQuery);
        if($stmt->execute($data))
        {
            $result = $stmt->fetchAll();
            if(COUNT($result)>0)
            {
                foreach($result as $row){
                    $sess_user_name = $row['user_name'];
                    $encPassword = $row['enc_password'];
                }
                $verify = password_verify($txtPassword, $encPassword);
                if($verify)
                {
                    session_start();
                    $_SESSION['user_name'] = $sess_user_name;
                    header('location:/user_dashboard.php');
                }
                else
                {
                    $msg = 'Invalid Password';
                }
            }
            else
            {
                $msg = 'Invalid User name';
            }
        }
        else
        {
            $msg = $stmt->errorInfo();
        }
    }
    else
    {
        $msg='Invalid Login Details';
    }
}
$pdo = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In Page</title>
    <?php cssLink(); ?>
    <link rel="stylesheet" href="/asset/css/custom/style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="wrapper">
                    <div class="logo">
                        <img src="/asset/img/logo.png" alt="Logo">
                    </div>
                    <div class="text-center mt-4 name">
                        T/D
                    </div>
                    <form class="p-3 mt-3" id="frmLogin" name="frmLogin" method="POST">
                         <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-user"></span>
                            <input type="text" name="txtUserName" id="txtUserName" placeholder="Enter user name" autocomplete="off"/>
                        </div>
                         <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-key"></span>
                            <input type="password" name="txtPassword" id="txtPassword" placeholder="Enter password" autocomplete="off"/>
                        </div>
                        <button type="submit" id="btnSubmit" name="btnSubmit" value="btnSubmit" class="btn mt-3">Login <i class="fa fa-arrow-circle-right fa-lg"></i></button>
                    </form>
                    <div class="text-center fs-6">
                        Don't have an account ? <a href="signup.php">Sign up</a>
                    </div>
                    <span style="color:red; font-size: 14px; font-weight: bold;">
                        <?php print_r($msg);?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php jsLink(); ?>    
    <script src="asset/js/custom/login.js"></script>
</body>
</html>