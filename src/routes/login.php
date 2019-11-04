<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Login
$app->post('/api/login', function (Request $request, Response $response) {
    $email = $request->getParam('email');
    $password = $request->getParam('password');


    $sql = "SELECT * FROM Users WHERE email = '$email'";


    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->query($sql);
    $stmt->execute();
    $user = $stmt->fetchObject();
    $num = $stmt->rowCount();

    if($num > 0) {
        $id = $user->id;
        $email = $user->email;
        $password2 = $user->password;

        if ($password == $password2) {
            $secret_key = "supersecretkeyyoushouldnotcommittogithub";
            $issuer_claim = "THE_ISSUER"; // this can be the servername
            $audience_claim = "THE_AUDIENCE";
            $issuedat_claim = time(); // issued at
            $notbefore_claim = $issuedat_claim + 10; //not before in seconds
            $expire_claim = $issuedat_claim + 10557600; // expire time in seconds
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $id,
                    "email" => $email
                ));

            $jwt = JWT::encode($token, $secret_key);
            echo json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "email" => $email,
                    "expireAt" => $expire_claim
                ));
        } else {
            return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
            echo json_encode(array("message" => "Login failed.", "password" => $password));
        }
    }
    $db = null;
});

