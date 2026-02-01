<?php

namespace Whitecube\NovaPage\Http\Controllers;

use Illuminate\Routing\Controller;
use Whitecube\NovaPage\Pages\Manager;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Laravel\Nova\Fields\FieldCollection;

abstract class ResourceDetailController extends Controller
{
    /**
     * Get the resource by key
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceDetailRequest $request
     * @param  \Whitecube\NovaPage\Pages\Manager $manager
     * @param  string $resourceId
     * @return mixed
     */
    abstract protected function findResource(
        ResourceDetailRequest $request,
        Manager $manager,
        string $resourceId,
    );

    /**
     * Get the resource detail.
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceDetailRequest  $request
     * @param  \Whitecube\NovaPage\Pages\Manager  $manager
     * @param  string  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function handle(
        ResourceDetailRequest $request,
        Manager $manager,
        string $resourceId,
    ) {
        $resource = $this->findResource($request, $manager, $resourceId);

        if (!$resource) {
            abort(404);
        }

        $payload = $resource->serializeForDetail($request, $resource);

        /** @var \Laravel\Nova\Fields\FieldCollection $fields */
        $fields = new FieldCollection($payload["fields"] ?? []);

        return response()->json([
            "title" => (string) $resource->title(),
            "panels" => $resource->availablePanelsForDetail(
                $request,
                $resource,
                $fields,
            ),
            "resource" => $payload,
        ]);
    }
}
