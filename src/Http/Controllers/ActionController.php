<?php

namespace Whitecube\NovaPage\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Nova\Http\Controllers\ActionController as Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class ActionController extends Controller
{
    /**
     * List the actions for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(NovaRequest $request): JsonResponse
    {
        return response()->json([
            "actions" => [],
            "pivotActions" => [
                "name" => Nova::humanize($request->pivotName()),
                "actions" => [],
            ],
            "counts" => [
                "sole" => 0,
                "standalone" => 0,
                "resource" => 0,
            ],
        ]);
    }
}
