<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get All Enrollment
$app->get('/api/enrollment', function (Request $request, Response $response) {
    $sql = "SELECT * FROM Enrollment ORDER BY id DESC";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedures = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($procedures);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Get Enrollment
$app->get('/api/enrollment/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM Enrollment WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedure = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($procedure);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Create Enrollment
$app->post('/api/enrollment/add', function (Request $request, Response $response) {
    $name = $request->getParam('name');
    $phone = $request->getParam('phone');
    $subject = $request->getParam('subject');
    $message = $request->getParam('message');

    $sql = "INSERT INTO Enrollment (name, phone, subject, message) VALUES (:name, :phone, :subject, :message)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Added"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }

    // message to admin
    $msg = "
        <html>
        <head>
            <style>
                table {
                  font-family: arial, sans-serif;
                  border-collapse: collapse;
                  width: 100%;
                }        
                td, th {
                  border: 1px solid #dddddd;
                  text-align: left;
                  padding: 8px;
                }
                </style>
        </head>
        <body>
            <p>Новий запис!</p>
            <table>
            <tr>
            <th>Ім'я</th>
            <th>Телефон</th>
            <th>Тема</th>
            <th>Повідомлення</th>
            </tr>
            <tr>
            <td>$name</td>
            <td>$phone</td>
            <td>$subject</td>
            <td>$message</td>
            </tr>
            </table>
        </body>
        </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <admin-delight-body@http://spa-delight.lviv.ua/>' . "\r\n";
    mail('lexslusarchyk@gmail.com',$subject,$msg,$headers);
});


//Delete Enrollment
$app->delete('/api/enrollment/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM Enrollment WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->execute();
        $db = null;

        echo '{"notice": {"text": "Deleted"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
