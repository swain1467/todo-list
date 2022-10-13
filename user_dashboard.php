<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
require_once("config.php");
require_once("GmailOAuth/gmail_config.php");
require_once(LIB_DIR."db/db_connection.php");
if(isset($_GET["code"])){
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
    if(!isset($token['error'])){
    $google_client->setAccessToken($token['access_token']);
    $google_service = new Google_Service_Oauth2($google_client);
    $data = $google_service->userinfo->get();
    $_SESSION['user_name'] = $data['id'];

    $pdo = pdo_connect();
    $user_data = [
        'user_name' => $data['id']
    ];
    $selectQuery = "SELECT user_name, enc_password FROM user_details WHERE user_name = :user_name";
    $stmt= $pdo->prepare($selectQuery);
        if($stmt->execute($user_data))
        {
            $result = $stmt->fetchAll();
            if(COUNT($result)==0)
            {
                $encPassword = password_hash($data['email'],PASSWORD_DEFAULT);
                $user_data = [
                    'user_name' => $data['id'],
                    'mail_address' => $data['email'],
                    'enc_password' => $encPassword,
                    'created' => date("Y-m-d H:i:s"),
                    'updated' => date("Y-m-d H:i:s"),
                    'status' => 1,
                    'first_name' => $data['givenName'],
                    'last_name' => $data['familyName']
                ];

                $inserQuery = "INSERT INTO user_details(user_name, mail_address, enc_password, created, updated, status,
                                first_name, last_name)
                VALUES (:user_name, :mail_address, :enc_password, :created, :updated, :status,
                        :first_name, :last_name)";
                $stmt= $pdo->prepare($inserQuery);
                $stmt->execute($user_data);
            }
        }
    }
}
require_once("config.php");
require_once(UTILITY_DIR."cdn_link.php");
require_once(UTILITY_DIR."check_login.php");
checkLogIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <?php cssLink(); ?>
    <link rel="stylesheet" href="asset/css/custom/style.css">
</head>
<body>
    <div class="container">
        <div class="row nav-bar">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a href="/logout.php"><i class="fa fa-power-off"></i></a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <select class="form-control" id="selMarkDone" name="selMarkDone">
                    <option value="">Select task status</option>
                    <option value="1">Marked as done</option>
                    <option value="0">Mark to be done</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <br>
                <table class="table table-bordered" id="dtblTask">
                    <thead>
                        <tr>
                            <th class="text-center">Task</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div id="modalTask" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                            <h5 class="modal-title" id="modalTaskHeader"></h5>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" id="frmTask">
                                    <input type="hidden" class="form-control" name="txtTaskId" id="txtTaskId" autocomplete="off"/>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-12 control-label" for="txtHeader">Header :&nbsp;<span class="required">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                    <input type="text" class="form-control" name="txtHeader" id="txtHeader" placeholder="Enter Header" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-12 control-label" for="txtContent">Content :&nbsp;<span class="required">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                    <textarea class="form-control" name="txtContent" id="txtContent" placeholder="Enter Content" autocomplete="off" rows="5"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
					    <button class="btn btn-primary btn-sm" id="btnSaveTask"><i class="fa fa-save"></i>&nbsp;Save</button>
                        <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php jsLink(); ?> 
    <script src="asset/js/custom/user_dashboard.js"></script>
</body>
</html>
