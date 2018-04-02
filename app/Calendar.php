<?php

namespace App;

use App\User;

class Calendar
{
    private $user;

    public function __construct($userId=0) {
        $this->user = null;
        if ($userId > 0)
            $this->user = User::find($userId);
    }

    public function make($date) {
    }
}