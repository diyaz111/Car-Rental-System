<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DataCarsContoller extends Controller
{
    public function search(Request $request)
    {
        $cacheKey = 'car_search_' . md5(json_encode($request->all()));

        //simpan data ke dalam key yg telah dibuat
        $cars = Cache::get($cacheKey);

        // apabila key sudah expired atau tidak ada, create new data for insert to the key
        if (!$cars) {
            $query = Cars::query();

            if ($request->brand && !empty($request->brand)) {
                $query->where('brand', 'like', '%' . $request->brand . '%');
            }

            if ($request->price_range && $request->has('price_range')) {
                $priceRange = explode('-', $request->price_range);
                $query->whereBetween('price_per_day', [$priceRange[0], $priceRange[1]]);
            }

            if ($request->availability_status && !empty($request->availability_status)) {
                $query->where('availability_status', $request->availability_status);
            }

            $query->select('id', 'nama', 'brand', 'price_per_day', 'availability_status');

            $cars = $query->paginate(15);

            // simpat cache dengan key selama 10 menit
            Cache::put($cacheKey, $cars, now()->addMinutes(10));
        }

        if ($cars->isEmpty()) {
            return response()->json([
                'data' => [
                    'message' => 'No cars found matching your filters.',
                ]
            ], 404);
        }

        return response()->json([
            'message' => 'Cars retrieved successfully.',
            'data' => $cars->toArray(),
        ], 200);
    }
}
