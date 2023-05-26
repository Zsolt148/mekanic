<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServiceTableController extends Controller
{
    public function __invoke(Request $request)
    {
        $perPage = $request->input('per_page');
        $page = $request->input('page');
        $search = $request->input('search');

		$name = $request->input('name');
		$id = $request->input('id');

        $services = Activity::query()
			->when($name && $id, function (Builder $query) use ($name, $id) {
				return $query
					->where('name', $name)
					->where('id', $id);
			});

            if ($search) {
            $services->where(function (Builder $query) use ($search) {
				$query
					->where('name', 'LIKE', '%' . $search . '%')
					->orWhere('created_at', 'LIKE', '%' . $search . '%')
					->orWhere('description', 'LIKE', '%' . $search . '%');
            });
            }

        $services = $services
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => ServiceResource::collection($services),
            'total' => ceil($services->total() / $request->input('per_page'))
        ]);
    }
}