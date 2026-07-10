<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $templatePath;

    public $templateFile;

    public function __construct()
    {

        $this->templatePath = env('APP_THEME', 'frontend');

        $this->templateFile = env('APP_THEME', 'frontend');
    }
}
