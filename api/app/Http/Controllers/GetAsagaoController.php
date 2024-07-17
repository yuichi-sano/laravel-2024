<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GetAsagaoService;

class GetAsagaoController extends Controller
{
    public function getAsagao(){
        $service = new GetAsagaoService;
        $asagaos = GetAsagaoService::getAsagao();
        return $asagaos
    }
}