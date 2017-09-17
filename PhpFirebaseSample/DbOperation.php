<?php

class DbOperation
{
    //Database connection link
    private $con;

    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';

        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();

        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }

    //storing token in database 
    public function registerDevice($token)
    {
        if (!$this->isEmailExist($token)) {
            $stmt = $this->con->prepare("INSERT INTO gcm_users(gcm_regid,created_at) VALUES (?,NOW()) ");
            $stmt->bind_param("s", $token);
            if ($stmt->execute())
                return 0; //return 0 means success
            return 1; //return 1 means failure
        } else {
            return 2; //returning 2 means email already exist
        }
    }

    //the method will check if email already exist 
    private function isEmailexist($token)
    {
        $stmt = $this->con->prepare("SELECT gcm_regid FROM gcm_users WHERE gcm_regid = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    //getting all tokens to send push to all devices
    public function getAllTokens()
    {
        $stmt = $this->con->prepare("SELECT gcm_regid FROM gcm_users ORDER BY created_at");
        $stmt->execute();
        $result = $stmt->get_result();
        $tokens = array();
        while ($token = $result->fetch_assoc()) {
            array_push($tokens, $token['gcm_regid']);
        }
        return $tokens;
    }
   public function getAllUsers()
    {
        $stmt = $this->con->prepare("SELECT * FROM gcm_users");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    //getting a specified token to send push to selected device
    public function getTokenById($id)
    {
        $stmt = $this->con->prepare("SELECT gcm_regid FROM gcm_users WHERE gcm_regid = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return array($result['gcm_regid']);
    }

    //getting all the registered devices from database 
    public function getAllDevices()
    {
        $stmt = $this->con->prepare("SELECT * FROM gcm_users");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getAllFM()
    {
        $stmt = $this->con->prepare("SELECT * FROM fixmessages");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getAllFMDesc()
    {
        $stmt = $this->con->prepare("SELECT * FROM fixmessages ORDER BY  f_id DESC ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getSpecificToken($upper,$lower)
    {
            $stmt = $this->con->prepare("SELECT * FROM gcm_users LIMIT $upper,$lower");
            $stmt->execute();
            $result = $stmt->get_result();
            $tokens = array();
            while ($token = $result->fetch_assoc()) {
                array_push($tokens, $token['gcm_regid']);
            }
        return $tokens;
    }

   
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email)
    {
        $result = mysqli_query($this->con, "SELECT emailid from users WHERE emailid = '$email'");
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed
            return true;
        } else {
            // user not existed
            return false;
        }
    }

}