<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// $app = new \Slim\App;

//Get All Procedures
$app->get('/api/procedures', function (Request $request, Response $response) {
    $sql = "SELECT * FROM Procedures";

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

//Get Procedure
$app->get('/api/procedure/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM Procedures WHERE id = $id ";

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

//Create Procedure
$app->post('/api/procedures/add', function (Request $request, Response $response) {
    $title = $request->getParam('title');
    $text = $request->getParam('text');
    $image = $request->getParam('image');

    $sql = "INSERT INTO Procedures (title, text, image) VALUES (:title,:text, :image)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Added"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Update Procedure
$app->put('/api/procedures/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $title = $request->getParam('title');
    $text = $request->getParam('text');
    $image = $request->getParam('image');


    $sql = "UPDATE Procedures SET
                title = :title,
                text = :text,
                image = :image
            WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':image', $image);

        $stmt->execute();

        echo '{"notice": {"text": "Updated"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Delete Procedure
$app->delete('/api/procedure/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM Procedures WHERE id = $id";

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
