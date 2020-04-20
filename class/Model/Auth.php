<?php

namespace App\Model;

use App\Core\App;
use App\Core\Model;

class Auth extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $email =
        $query = '
            SELECT id
            FROM workers
            WHERE email = "'.$this->quote($email).'"
        ';
        $res = mysqli_query($this->db, $query);
        $row = mysqli_fetch_assoc($res);
        return $row;
    }

    public function checkReqParams()
    {

    }
}