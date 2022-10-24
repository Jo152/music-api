<?php

class CustomerModel extends BaseModel {

    private $table_name = "customer";

    /**
     * A model class for the `customer` database table.
     * It exposes operations that can be performed on customer records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all customer from the `customer` table.
     * @return array A list of customer. 
     */
    public function getAll() {
        $sql = "SELECT * FROM customer";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Get a list of customer whose country matches or contains the provided value.       
     * @param string $customerName 
     * @return array An array containing the matches found.
     */
    public function getWhereLikeCountry($country) {
        $sql = "SELECT * FROM customer WHERE Country LIKE :country";
        $data = $this->run($sql, [":country" => $country . "%"])->fetchAll();
        return $data;
    }

    /**
     * Retrieve an customer by its id.
     * @param int $customer_id the id of the customer.
     * @return array an array containing information about a given customer.
     */
    public function getCustomertById($customer_id) {
        $sql = "SELECT * FROM customer WHERE CustomerId = ?";
        $data = $this->run($sql, [$customer_id])->fetch();
        return $data;
    }

    public function getPurchasedTracksByCustomerId($customer_id) {
        $sql = "SELECT * FROM invoice WHERE CustomerId = ?";
        $data = $this->rows($sql, [$customer_id])->fetch();//->fetch();
        return $data;
    }

    public function deleteCustomerById($artist_id) {
        $sql = "DELETE FROM customer WHERE CustomerId = ?";
        $data = $this->run($sql, [$artist_id]);//->fetch();
        return $data;
    }

}
