<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secret" => "supersecretkeyyoushouldnotcommittogithub",
    "path" => ["/api/admin"],
    "before" => function ($request, $arguments) {
        return $request->withHeader("Foo", "bar");
    }
]));

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

//Products Routes
require '../src/routes/products.php';

//News Routes
require '../src/routes/news.php';

//Gallery Routes
require '../src/routes/gallery.php';

//Categories Routes
require '../src/routes/categories.php';

//Enrollment Routes
require '../src/routes/enrollment.php';

//Uploads Routes
require '../src/routes/uploads.php';

//Login Routes
require '../src/routes/login.php';

// Run app
$app->run();
