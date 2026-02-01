<?php

namespace Whitecube\NovaPage\Http\Controllers\Page;

use Whitecube\NovaPage\Pages\Manager;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Whitecube\NovaPage\Http\Controllers\ResourceDetailController;

class DetailController extends ResourceDetailController
{
    /**
     * Find the resource by key
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceDetailRequest $request
     * @param  \Whitecube\NovaPage\Pages\Manager $manager
     * @param  string $resourceId
     * @return mixed
     */
    protected function findResource(
        ResourceDetailRequest $request,
        Manager $manager,
        string $resourceId,
    ) {
        return $manager->findResourceByKey($resourceId, "route");
    }
}
