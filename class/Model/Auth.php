<?php

namespace App\Model;

use App\Core\Model;

class Auth extends Model
{
    /** @return self */
    public static function i($data = null)
    {
        return parent::i($data);
    }

    public function login()
    {
        $this->checkReqParams(['email', 'password']);

        $params = $this->toArray($this->params);
        $email = $params['email'] ?? '';
        $password = $params['password'] ?? '';

        $query = '
            SELECT id
            FROM workers
            WHERE email = '.$this->quote($email).'
            AND password = '.$this->quote($password).'
            AND active = 1
        ';
        $res = mysqli_query($this->db, $query);
        $worker = $res ? mysqli_fetch_assoc($res) : [];

        if (empty($worker['id'])) {
            $this->app->sendError(200, 'Auth error');
        }

        $hash = hash('md5', $worker['id'].' '.$email.' '.$password);
        if (empty($hash)) {
            $this->app->sendError(200, 'Auth error');
        }

        $query = '
            UPDATE workers
            SET hash = '.$this->quote($hash).'
            WHERE id = '.intval($worker['id']).'
        ';
        mysqli_query($this->db, $query);

        return $hash;
    }

    public function checkAuth()
    {
        $this->checkReqParams(['token']);

        $params = $this->toArray($this->params);
        $token = $params['token'];

        $query = '
            SELECT id
            FROM workers
            WHERE hash = '.$this->quote($token).'
            AND active = 1
        ';
        $res = mysqli_query($this->db, $query);
        $worker = $res ? mysqli_fetch_assoc($res) : [];

        if (empty($worker['id'])) {
            $this->app->sendError(200, 'Auth error');
        }

        return true;
    }

    public function checkAdmin()
    {
        $this->checkReqParams(['token']);

        $params = $this->toArray($this->params);
        $token = $params['token'];

        $query = '
            SELECT id
            FROM workers
            WHERE hash = '.$this->quote($token).'
            AND active = 1
            AND admin = 1
        ';
        $res = mysqli_query($this->db, $query);
        $worker = $res ? mysqli_fetch_assoc($res) : [];

        if (empty($worker['id'])) {
            $this->app->sendError(200, 'Auth error');
        }

        return true;
    }
}