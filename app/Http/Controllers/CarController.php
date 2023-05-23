<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Http\Resources\PartnerResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index()
    {
        return Inertia::render('Cars/Index');
    }

    public function create(Request $request)
    {
        return Inertia::render('Cars/Create', $this->props());
    }

    public function store(CarRequest $request)
    {
        $this->save(new Car(), $request);
        return redirect()->route('cars.index')->with('success', __('Successfully created'));
    }

    private function save(Car $car, CarRequest $request): Car
    {
        $validated = $request->validated();
        if ($validated['partner_id'] != null) {
            $partner = Partner::find($validated['partner_id']);
            $partner->name = $validated['name'];
            $partner->zip = $validated['zip'];
            $partner->city = $validated['city'];
            $partner->address = $validated['address'];
            $partner->tax_number = $validated['tax_number'];
            $partner->communal_tax_number = $validated['communal_tax_number'];
            $partner->save();
        } else {
            $partner = Partner::create([
                'name' => $validated['name'],
                'zip' => $validated['zip'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'tax_number' => $validated['tax_number'],
                'communal_tax_number' => $validated['communal_tax_number'],
                'created_by' => Auth::user()->id
            ]);
        }
        $car->partner_id = $partner->id;
        $car->created_by = $validated['created_by'];
        $car->brand = $validated['brand'];
        $car->type = $validated['type'];
        $car->license_plate = $validated['license_plate'];
        $car->mileage = $validated['mileage'];
        $car->chassis_number = $validated['chassis_number'];
        $car->motor_number = $validated['motor_number'];
        $car->vintage = $validated['vintage'];
        $car->ti_validity = $validated['ti_validity'];

        $car->save();
        return $car;
    }

    private function props(?Car $car = null): array
    {
        $partners = Partner::query()->orderBy('name', 'DESC')->get();

        return [
            'car' => $car ? CarResource::make($car) : null,
            'partners' => PartnerResource::collection($partners),
        ];
    }

    public function edit(Request $request, Car $car)
    {
        return Inertia::render('Cars/Edit', $this->props($car));
    }

    public function getTableData(Request $request)
    {

        $perPage = $request->input('per_page');
        $page = $request->input('page');

        $cars = Car::query()
            ->with(['partner'])
            ->withTrashed()
            ->search($request)
            ->orderBy('id', 'DESC');

        $cars = $cars
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => CarResource::collection($cars),
            'total' => ceil($cars->count() / $request->input('per_page'))
        ]);
    }


}
