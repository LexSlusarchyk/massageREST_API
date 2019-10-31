<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get All Employees
$app->get('/api/employees', function (Request $request, Response $response) {
    $sql = "SELECT * FROM Employees ORDER BY id DESC";

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

//Get Employee
$app->get('/api/employees/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM Employees WHERE id = $id ";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedure = $stmt->fetchObject();
        $db = null;
        echo json_encode($procedure);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Create Employee
$app->post('/api/admin/employees/add', function (Request $request, Response $response) {
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $image = $request->getParam('image');

    $sql = "INSERT INTO Employees (title, titleEn, text, textEn, image) VALUES (:title, :titleEn, :text, :textEn, :image)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Added"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Update Employee
$app->put('/api/admin/employees/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $image = $request->getParam('image');


    $sql = "UPDATE Employees SET
                title = :title,
                titleEn = :titleEn,
                text = :text,
                textEn = :textEn,
                image = :image
            WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
        $db = null;

        echo '{"notice": {"text": "Updated"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Delete Employee
$app->delete('/api/admin/employees/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM Employees WHERE id = $id";

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
