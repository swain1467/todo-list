<?php
include("utility/cdn_link.php");
require_once("utility/error_report.php");
include("utility/check_login.php");
require_once("utility/db_connection.php");
checkSession();
$pdo = pdo_connect();
$msg = '';
if(isset($_POST['btnSubmit']))
{
    $txtUserName = isset($_POST['txtUserName']) ? $_POST['txtUserName'] : '';
    $txtEmailAddress = isset($_POST['txtEmailAddress']) ? $_POST['txtEmailAddress'] : '';
    $txtPassword = isset($_POST['txtPassword']) ? $_POST['txtPassword'] : '';
    $txtFirstName = isset($_POST['txtFirstName']) ? $_POST['txtFirstName'] : '';
    $txtLastName = isset($_POST['txtLastName']) ? $_POST['txtLastName'] : '';
    $txtContactNo = isset($_POST['txtContactNo']) ? $_POST['txtContactNo'] : '';
    
    if($txtUserName !='' && $txtEmailAddress !='' && $txtPassword != ''
        && $txtFirstName != '' && $txtLastName != '' && $txtContactNo != '')
    {
        $txtEmailAddress = $_POST["txtEmailAddress"];
        if (!filter_var($txtEmailAddress, FILTER_VALIDATE_EMAIL)) {
            $msg = "Invalid email format"; 
        }
        else
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
                    $msg = 'Oops duplicate user name please try another user name';
                }
                else
                {
                    $encPassword = password_hash($txtPassword,PASSWORD_DEFAULT);
                    $data = [
                        'user_name' => $txtUserName,
                        'mail_address' => $txtEmailAddress,
                        'enc_password' => $encPassword,
                        'created' => date("Y-m-d H:i:s"),
                        'updated' => date("Y-m-d H:i:s"),
                        'status' => 1,
                        'first_name' => $txtFirstName,
                        'last_name' => $txtLastName,
                        'contact_no' => $txtContactNo
                    ];
            
                    $inserQuery = "INSERT INTO user_details(user_name, mail_address, enc_password, created, updated, status,
                                    first_name, last_name, contact_no)
                    VALUES (:user_name, :mail_address, :enc_password, :created, :updated, :status,
                            :first_name, :last_name, :contact_no)";
                    $stmt= $pdo->prepare($inserQuery);
                    if($stmt->execute($data))
                    {
            
                        $_SESSION['user_name'] = $txtUserName;
                        header('location:/user_dashboard.php');
                    }
                    else
                    {
                        $msg = $stmt->errorInfo();
                    }
                }
            }
            else
            {
                $msg = $stmt->errorInfo();
            }
        }
    }
    else
    {
        $msg='Invalid input Details all fields are required';
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
    <title>Register Page</title>
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
                    <form class="p-3 mt-3" id="frmRegister" name="frmRegister" method="POST">
                        <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-user"></span>
                            <input type="text" name="txtFirstName" id="txtFirstName" placeholder="Enter first name" autocomplete="off"/>
                        </div>
                        <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-user"></span>
                            <input type="text" name="txtLastName" id="txtLastName" placeholder="Enter last name" autocomplete="off"/>
                        </div>
                        <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-user"></span>
                            <input type="text" name="txtUserName" id="txtUserName" placeholder="Enter user name" autocomplete="off"/>
                        </div>
                        <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-phone"></span>
                            <input type="text" name="txtContactNo" id="txtContactNo" placeholder="Enter contact No" autocomplete="off"/>
                        </div>
                        <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-envelope"></span>
                            <input type="text" name="txtEmailAddress" id="txtEmailAddress" placeholder="Enter mail address" autocomplete="off"/>
                        </div>
                        <div class="form-field d-flex align-items-center form-group">
                            <span class="fa fa-key"></span>
                            <input type="password" name="txtPassword" id="txtPassword" placeholder="Enter password" autocomplete="off"/>
                        </div>
                        <button type="submit" id="btnSubmit" name="btnSubmit" value="btnSubmit" class="btn mt-3">Register <i class="fa fa-arrow-circle-right fa-lg"></i></button>
                    </form>
                    <span style="color:red; font-size: 14px; font-weight: bold;">
                        <?php print_r($msg);?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php jsLink(); ?>
    <script src="asset/js/custom/signup.js"></script>
</body>
</html>