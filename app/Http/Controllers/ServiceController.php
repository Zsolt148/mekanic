<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

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

    public function create(Request $request)
    {
        return Inertia::render('Services/Create', $this->props());
    }

    private function props(?Service $service = null): array
    {
        $services = Service::query()->orderBy('name', 'DESC')->get();

        return [
            'service' => $service ? ServiceResource::make($service) : null
        ];
    }

    public function store(ServiceRequest $request)
    {
		$this->save(new Service(), $request);

        return redirect()->route('services.index')->with('success', __('Successfully created'));

    }

    public function edit(Request $request, Service $service)
    {
        return Inertia::render('Services/Edit', $this->props($service));
    }

    public function update(InvoiceRequest $request, Service $service)
    {
        $this->save($service, $request);

        return redirect()->route('services.index')->with('success', __('Successfully updated'));

    }

    public function forceDelete(Request $request, Service $service)
    {
        $service->forceDelete();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully deleted')]);
        }

        return redirect()->route('invoices.index')->with('success', __('Successfully deleted'));
    }

    private function save(Service $service, ServiceRequest $request): Service
    {
        $validated = $request->validated();

        $service->name = $validated['name'];
        $service->price = $validated['price'];

        $service->save();


        return $service;
    }
}
