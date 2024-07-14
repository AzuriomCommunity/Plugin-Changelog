<?php

namespace Azuriom\Plugin\Changelog\Controllers\Api;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Changelog\Models\Update;
use Azuriom\Plugin\Changelog\Resources\UpdateResource;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $updates = Update::with('category')->latest()->paginate();

        return UpdateResource::collection($updates);
    }
}
