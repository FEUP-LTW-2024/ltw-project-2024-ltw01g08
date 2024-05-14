<?php

class User {
    private $id;
    private $firstName;
    private $lastName;
    private $username;
    private $email;
    private $password;
    private $address;
    private $profilePicture;

    // Constructor
    public function __construct($id, $firstName, $lastName, $username, $email, $password, $address, $profilePicture) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
        $this->profilePicture = $profilePicture;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getProfilePicture() {
        return $this->profilePicture;
    }

    // Setter for address
    public function setAddress($address) {
        $this->address = $address;
    }

    // Setter for profile picture
    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;
    }

    function save($db) {
        $stmt = $db->prepare('
            UPDATE User SET first_name = ?, last_name = ?, username = ?, email = ?, user_password = ?, user_address = ?, profile_picture = ?
            WHERE id = ?
        ');
        $stmt->execute(array(
            $this->firstName, 
            $this->lastName, 
            $this->username, 
            $this->email, 
            $this->password, 
            $this->address, 
            $this->profilePicture, 
            $this->id
        ));
    }

}

?>
