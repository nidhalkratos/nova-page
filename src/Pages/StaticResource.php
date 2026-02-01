<?php

namespace Whitecube\NovaPage\Pages;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;

abstract class StaticResource extends Resource
{
    use Concerns\ResolvesResourceFields;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = "title";

    /**
     * Indicates if the resoruce should be globally searchable.
     * Disabled for now until there is a fix for issue #15
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ["title", "name"];
    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Create a new resource instance.
     */
    public function __construct(Manager|Template $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Determine if this resource uses soft deletes.
     *
     * @return bool
     */
    public static function softDeletes()
    {
        return false;
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->resource->getName();
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return $this->resource->getName();
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        return resolve(Manager::class);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return array_merge(
            $this->getFormIntroductionFields(),
            $this->getTemplateAttributesFields($request),
        );
    }

    /**
     * Get the base fields displayed at the top of the resource's form.
     *
     * @return array
     */
    abstract protected function getFormIntroductionFields();

    /**
     * Get the base attributes Nova Panel
     *
     * @return array
     */
    abstract protected function getIndexTableFields();

    /**
     * Get the fields displayed by the template.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    protected function getTemplateAttributesFields(NovaRequest $request)
    {
        return $this->resource->fields($request) ?? [];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return $this->resource->cards($request) ?? [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    /**
     * Determine if the resource is authorizable.
     *
     * Override to prevent Nova 5 from trying to resolve the model for authorization,
     * since this resource does not use an Eloquent model.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }

    /**
     * Determine if the current user can view any resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        return true;
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return true;
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return true;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can impersonate the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return bool
     */
    public function authorizedToImpersonate(NovaRequest $request)
    {
        return false;
    }

    /**
     * Determine if the current user can replicate the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->serializeWithId(
            $this->resolveFields(resolve(NovaRequest::class)),
        );
    }

    /**
     * Prepare the resource for JSON serialization using the given fields.
     *
     * @param  \Illuminate\Support\Collection  $fields
     * @return array
     */
    protected function serializeWithId(Collection $fields)
    {
        $idField = ID::make("id");
        $idField->resolve((object) ["id" => $this->getKey()]);

        return [
            "id" => $idField,
            "fields" => $fields->all(),
        ];
    }
}
