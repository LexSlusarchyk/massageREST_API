<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;



//$config['db']['host']   = 'localhost';
//$config['db']['user']   = 'root';
//$config['db']['pass']   = '';
//$config['db']['dbname'] = 'massage';

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name =$request->getAttribute('name');
    $response->getBody()->write("Hello", $name);

    return $response;
});

//Procedures Routes
require '../src/routes/procedures.php';

//Employees Routes
require '../src/routes/employees.php';

//Uploads Routes
require '../src/routes/uploads.php';

// Run app
$app->run();
