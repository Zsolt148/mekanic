<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Services/Index');
    }
}
