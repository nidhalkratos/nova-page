<?php

namespace Whitecube\NovaPage\Http\Controllers\Option;

use Whitecube\NovaPage\Pages\Manager;
use Laravel\Nova\Http\Requests\ResourceUpdateOrUpdateAttachedRequest;
use Whitecube\NovaPage\Http\Controllers\ResourceUpdateFieldsController;

class UpdateFieldsController extends ResourceUpdateFieldsController
{
    /**
     * Find the resource by key
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceUpdateOrUpdateAttachedRequest $request
     * @param  \Whitecube\NovaPage\Pages\Manager $manager
     * @param  string $resourceId
     * @return mixed
     */
    protected function findResource(ResourceUpdateOrUpdateAttachedRequest $request, Manager $manager, string $resourceId)
    {
        return $manager->findResourceByKey($resourceId, 'option');
    }
}
