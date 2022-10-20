<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/ArtistModel.php';

// Callback for HTTP GET /artists
//-- Supported filtering operation: by artist name.
function handleGetAllArtists(Request $request, Response $response, array $args)
{
    $artists = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $artist_model = new ArtistModel();

    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["name"])) {
        // Fetch the list of artists matching the provided name.
        $artists = $artist_model->getWhereLike($filter_params["name"]);
    } else {
        // No filtering by artist name detected.
        $artists = $artist_model->getAll();
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($artists, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleGetArtistById(Request $request, Response $response, array $args)
{
    $artist_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $artist_model = new ArtistModel();

    // Retreive the artist from the request's URI.
    $artist_id = $args["artist_id"];
    if (isset($artist_id)) {
        // Fetch the info about the specified artist.
        $artist_info = $artist_model->getArtistById($artist_id);
        if (!$artist_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified artist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($artist_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleCreateArtists(Request $request, Response $response, array $args)
{
    $data = $request->getParsedBody();
    //-- Go over elements stored in the $data array
    //-- In a for/each loop
    $artist_model = new  ArtistModel();
    for ($index = 0; $index < count($data); $index++) {
        $single_artist = $data[$index];
        $artistId = $single_artist["ArtistId"];
        $artistName = $single_artist["Name"];

        $new_artist_record = array(
            "ArtistId" => $artistId,
            "Name" => $artistName
        );

        //-- We retrieve the key and its value
        //-- We perform an UPDATE/CREATE SQL statement
        $artist_model->createArtists($new_artist_record);
    }

    $html = var_export($data, true);
    $response->getBody()->write($html);
    return $response;
}

function handleUpdateArtist(Request $request, Response $response, array $args)
{
    $data = $request->getParsedBody();
    //-- Go over elements stored in the $data array
    //-- In a for/each loop
    $artist_model = new  ArtistModel();

    //-- We retrieve the key and its value
    for ($index = 0; $index < count($data); $index++) {

        $single_artist = $data[$index];
        //$artistId = $single_artist["ArtistId"];
        $artistName = $single_artist["Name"];

        
        //-- We perform an CREATE SQL statement
        $existing_artist_record = array(
            //"ArtistId"=> 277,
            "Name" => $artistName
        );
        $artist_model->updateArtists($existing_artist_record, array("Name" => $artistName));
    }


    $html = var_export($data, true);
    $response->getBody()->write($html);
    return $response;
}

function handleGetAllAlbumsFromArtist(Request $request, Response $response, array $args)
{
    $album_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $album_model = new ArtistModel();

    // Retreive the artist if from the request's URI.
    $artist_id = $args["artist_id"];
    if (isset($artist_id)) {
        // Fetch the info about the specified artist.
        $album_info = $album_model->getAlbumByArtistId($artist_id);
        if (!$album_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified artist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($album_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}


function handleGetTracksFromAlbumsAndArtist(Request $request, Response $response, array $args)
{
    $album_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $album_model = new ArtistModel();

    // Retreive the artist if from the request's URI.
    $artist_id = $args["artist_id"];
    $album_id = $args["album_id"];
    if (isset($artist_id)) {
        // Fetch the info about the specified artist.
        $album_info = $album_model->getTracksByArtistIdAndAlbumId($album_id, $artist_id);
        if (!$album_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified artist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($album_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}
