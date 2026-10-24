<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

/** GET /api/services?per_page=3 — ակտիվ ծառայությունները */
class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min(50, $request->integer('per_page', 10)));    // -5 → 1, 1000 → 50

        return ServiceResource::collection(Service::active()->orderBy('price')->paginate($perPage)->withQueryString());
    }
}
