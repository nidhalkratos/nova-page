<?php

namespace Whitecube\NovaPage\Http\Controllers;

use Illuminate\Routing\Controller;
use Whitecube\NovaPage\Pages\Manager;
use Laravel\Nova\Http\Requests\ResourceUpdateOrUpdateAttachedRequest;

abstract class ResourceUpdateFieldsController extends Controller
{
    /**
     * Get the resource by key
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceUpdateOrUpdateAttachedRequest $request
     * @param  \Whitecube\NovaPage\Pages\Manager $manager
     * @param  string $resourceId
     * @return mixed
     */
    abstract protected function findResource(
        ResourceUpdateOrUpdateAttachedRequest $request,
        Manager $manager,
        string $resourceId,
    );

    /**
     * Get the update fields for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceUpdateOrUpdateAttachedRequest  $request
     * @param  \Whitecube\NovaPage\Pages\Manager  $manager
     * @param  string  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function handle(
        ResourceUpdateOrUpdateAttachedRequest $request,
        Manager $manager,
        string $resourceId,
    ) {
        $resource = $this->findResource($request, $manager, $resourceId);

        if (!$resource) {
            abort(404);
        }

        $fields = $resource->updateFieldsWithinPanels($request, $resource)
            ->applyDependsOnWithDefaultValues($request);

        return response()->json([
            'title' => (string) $resource->title(),
            'fields' => $fields,
            'panels' => $resource->availablePanelsForUpdate($request, $resource, $fields),
        ]);
    }
}
