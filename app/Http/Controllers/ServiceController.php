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

    public function getTableData(Request $request){

        $perPage = $request->input('per_page');
        $page = $request->input('page');

        $services = Service::query()
            ->with('tags')
            ->withTrashed()
            ->search($request)
            ->orderBy('id', 'DESC');

        $invoices = $invoices
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => ServiceResource::collection($services)
        ]);
    }
}
