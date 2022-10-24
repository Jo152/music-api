<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../modelsTracksModel.php';

// Callback for HTTP GET /artists
//-- Supported filtering operation: by artist name.
function getFilteredTracksFromAlbumsAndArtist(Request $request, Response $response, array $args)
{
    $tracks = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $track_model = new TrackModel();

    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["genre"]) && isset($filter_params["mediaType"])) {

        // Fetch the list of tracks matching the provided genre and type.
        $tracks = $track_model->getWhereLikeGenreAndType($filter_params["genre"], $filter_params["mediaType"]);

    } else if(isset($filter_params["genre"])){

        $tracks = $track_model->getWhereLikeGenre($filter_params["genre"]);

    }else if(isset($filter_params["mediaType"])){

        $tracks = $track_model->getWhereLikeMediaType($filter_params["mediaType"]);

    }
    else {
        // No filtering by artist name detected.
        $tracks = $track_model->getAll();
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($tracks, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

