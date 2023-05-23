<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorksheetRequest;
use App\Http\Resources\CarResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\WorksheetResource;
use App\Models\Car;
use App\Models\Partner;
use App\Models\Worksheet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorksheetController extends Controller
{
    public function index()
    {
        return Inertia::render('Worksheets/Index', $this->props());
    }

    public function create()
    {
        return Inertia::render('Worksheets/Create', $this->props());
    }

    public function store(WorksheetRequest $request)
    {
        $this->save($request, new Worksheet());

        return redirect()->route('worksheets.index')->with('success', trans('Successfully created'));
    }

    public function edit(Worksheet $worksheet)
    {
        return Inertia::render('Worksheets/Edit', $this->props($worksheet));
    }

    public function update(WorksheetRequest $request, Worksheet $worksheet)
    {
        $this->save($request, $worksheet);

        return redirect()->route('worksheets.index')->with('success', trans('Successfully updated'));
    }

    public function destroy(Worksheet $worksheet)
    {
        $worksheet->delete();

        return redirect()->route('worksheets.index')->with('success', trans('Successfully deleted'));
    }

    private function props(?Worksheet $worksheet = null): array
    {
        $worksheets = Worksheet::query()
            ->with('car', 'partner', 'admin')
            ->latest()
            ->get();

        $partners = Partner::query()
            ->orderBy('name', 'DESC')
            ->get();

        $cars = Car::query()
            ->get();

        return [
            'types' => mapForSelect(Worksheet::TYPES),
            'worksheet' => $worksheet ? WorksheetResource::make($worksheet) : null,
            'worksheets' => WorksheetResource::collection($worksheets),
            'cars' => CarResource::collection($cars),
            'partners' => PartnerResource::collection($partners),
        ];
    }

    private function save(Request $request, Worksheet $worksheet): Worksheet
    {
        $validated = $request->validated();

        $worksheet->admin()->associate($this->user());
        $worksheet->fill($validated);

        $worksheet->save();

        return $worksheet;
    }
}
