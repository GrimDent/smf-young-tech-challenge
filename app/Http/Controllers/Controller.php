<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(title: 'Young Tech Challenge API', version: '1.0.0', description: 'API do faktur')]
#[OA\Server(url: 'http://localhost:8000', description: 'Serwer lokalny')]
class Controller {}
