<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $messages = [
        'required' => 'The :attribute field is required.',
        'email'    => 'The :attribute field must be an email',
        'string'   => 'The :attribute field must be a string.',
        'min'      => 'The :attribute field must be at least :min characters long.',
    ];
}
