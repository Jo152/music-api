<?php

class ArtistModel extends BaseModel {

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
     * Retrieve all artists from the `artist` table.
     * @return array A list of artists. 
     */
    public function getAll() {
        $sql = "SELECT * FROM artist";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Get a list of artists whose name matches or contains the provided value.       
     * @param string $artistName 
     * @return array An array containing the matches found.
     */
    public function getWhereLike($artistName) {
        $sql = "SELECT * FROM artist WHERE Name LIKE :name";
        $data = $this->run($sql, [":name" => $artistName . "%"])->fetchAll();
        return $data;
    }

    /**
     * Retrieve an artist by its id.
     * @param int $artist_id the id of the artist.
     * @return array an array containing information about a given artist.
     */
    public function getArtistById($artist_id) {
        $sql = "SELECT * FROM artist WHERE ArtistId = ?";
        $data = $this->run($sql, [$artist_id])->fetch();
        return $data;
    }
    //create artist
    public function createArtists($data) {
        $data = $this->insert($this->table_name, $data);
        return $data;
    }

    //update artist
    public function updateArtists($data, $where) {
        $data = $this->update($this->table_name, $data, $where);
        return $data;
    }
    /**
     * Retrieve an album by its artistId.
     * @param int $artist_id the id of the album.
     * @return array an array containing information about a given album.
     */
    public function getAlbumByArtistId($artist_id) {
        $sql = "SELECT * FROM album WHERE ArtistId = ?";
        $data = $this->rows($sql, [$artist_id]);//->fetch();
        return $data;
    }

    /**
     * Retrieve an album by its artistId.
     * @param int $artist_id the id of the album.
     * @return array an array containing information about a given album.
     */
    public function getTracksByArtistIdAndAlbumId($artist_id, $album_id) {
        //TO DO: Find proper sql statement
        $sql = "SELECT * FROM artist, album, track WHERE artist.ArtistId = album.ArtistId 
            AND album.albumId = track.AlbumId AND artist.ArtistId = ? AND album.AlbumId = ?";
        $data = $this->run($sql, [$artist_id, $album_id])->fetchAll();
        return $data;
    }

}
