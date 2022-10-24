<?php

class TrackModel extends BaseModel {

    protected $table_name = "artist";

    /**
     * A model class for the `artist` database table.
     * It exposes operations that can be performed on artists records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all tracks from the `track` table.
     * @return array A list of tracks. 
     */
    public function getAll() {
        $sql = "SELECT * FROM track";
        $data = $this->rows($sql);
        return $data;
    }

    public function getWhereLikeGenreAndType($genre, $mediaType) {
        $sql = "SELECT * FROM track, genre, mediatype WHERE track.GenreId = genre.GenreId AND track.MediaTypeId = mediatype.MediaTypeId 
            AND genre.GenreId = ? AND mediatype.MediaTypeId = ?";
        $data = $this->run($sql, [$genre, $mediaType])->fetchAll();
        return $data;
    }


    public function getWhereLikeGenre($genre) {
        $sql = "SELECT * FROM artist WHERE Name LIKE :genre";
        $data = $this->run($sql, [":genre" =>$genre . "%"])->fetchAll();
        return $data;
    }

    public function getWhereLikeMediaType($media) {
        $sql = "SELECT * FROM artist WHERE Name LIKE :media";
        $data = $this->run($sql, [":media" =>$media . "%"])->fetchAll();
        return $data;
    }
}