<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require_once './includes/app_constants.php';
require_once './includes/helpers/helper_functions.php';

//--Step 1) Instantiate App.
$app = AppFactory::create();
//-- Step 2) Add routing middleware.
$app->addRoutingMiddleware();
//Adding Slim's body parsing 
$app->addBodyParsingMiddleware();
//-- Step 3) Add error handling middleware.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//-- Step 4)
// TODO: change the name of the sub directory here. You also need to change it in .htaccess
$app->setBasePath("/music-api");

//-- Step 5) Include the files containing the definitions of the callbacks.
require_once './includes/routes/artists_routes.php';
require_once './includes/routes/customers_routes.php';

//-- Step 6)
//define app routes.

// -- GET Request
// #1 Get list of Artists
$app->get("/artists", "handleGetAllArtists");

// #2 Get the details of given artist
$app->get("/artists/{artist_id}", "handleGetArtistById");

// #3 Get List of Albums of a given artist
$app->get("/artists/{artist_id}/albums", "handleGetAllAlbumsFromArtist");

// #4 Get the list of tracks for the specified album and artist 
$app->get("/artists/{artist_id}/albums/{album_id}/tracks", "handleGetTracksFromAlbumsAndArtist");

// #5 Get the list of all tracks purchased by a given customer
$app->get("/customers/{customer_id}/invoices", "handleGetAllPurchasedTracksFromCustomer");

// #6 Get List of customers
//$app->get("/comments", "handleGetComments");



// Get list of comments from remote API
$app->get("/customers", "handleGetAllCustomers");

// -- POST Request
//Creating artists
$app->post("/artists", "handleCreateArtists");

// -- PUT Request
//Update artists
$app->put("/artists", "handleUpdateArtist");

// -- DELETE Request
//Delete artist
$app->delete("/artists/{artist_id}", "handleDeleteArtist");

//Delete customer
$app->delete("/customers/{customer_id}", "handleDeleteCustomer");

// -- Filtering operations





// Define app routes.
$app->get('/hello/{your_name}', function (Request $request, Response $response, $args) {
    //var_dump($args);
    $response->getBody()->write("Hello!" . $args["your_name"]);
    return $response;
});

// Run the app.
$app->run();
