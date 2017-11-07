<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomepageController
{
    public function index(): Response
    {
        return new Response('OK');
    }
}
