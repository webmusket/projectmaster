<?php

namespace App\Nova;

use R64\NovaFields\JSON;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Log;
use App\Nova\Actions\ConfirmChanges;
use App\Nova\Actions\ConfirmUpdate;
use App\Nova\Actions\RejectRequest;
use Laravel\Nova\Fields\Image;

class WaitingApproval extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Confirmation';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'type'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('Resource ID','resource_id'),
            Text::make('Type','type'),
           
            KeyValue::make('Changes','data')->resolveUsing(function ($data) {
                
                return $data['changes'];
            }),
            KeyValue::make('Before','data')->resolveUsing(function ($data) {
                
                return $data['original'];
            }),
            KeyValue::make('Data','data')->rules('json'),
            Boolean::make('Confirm', 'is_checked'),
        ];
        // $data = [
        //     ID::make()->sortable(),
        //     Text::make('Type')->exceptOnForms()->sortable(),
        //     Image::make('Image')->exceptOnForms(),
        //     Boolean::make('Confirm', 'is_checked')
        //         ->exceptOnForms()
        //         ->sortable()
        // ];

        // if ($this->type === \App\Confirmation::SERVICE_EDIT) {
        //     $data[] = BelongsTo::make('Resource', 'resource', 'App\Nova\Service')->exceptOnForms();
        // }
        // if (in_array($this->type, [
        //     \App\Confirmation::SERVICE_EDIT,
        //     \App\Confirmation::OPTION_NEW,
        // ])) {
        //     $data[] = BelongsTo::make('Resource', 'resource', 'App\Nova\Service')->exceptOnForms();
        // } 
        // elseif ($this->type === \App\Confirmation::OPTION_EDIT) {
        //     // Log::debug('An informational message.'.'$resource');
        //     $data[] = BelongsTo::make('Resource', 'resource', 'App\Nova\ServiceOption')->exceptOnForms();
        // }
        // elseif (in_array($this->type, [
        //     \App\Confirmation::SERVICE_NEW,
        //     \App\Confirmation::PROVIDER_IMAGE,
        //     \App\Confirmation::PROVIDER_BACKGROUND
        // ])) {
        //     $data[] = BelongsTo::make('Resource', 'resource', 'App\Nova\Provider')->exceptOnForms();
        // }

        // if (in_array($this->type, [
        //     \App\Confirmation::SERVICE_NEW,
        //     \App\Confirmation::SERVICE_EDIT,
        // ])) {
        //     $data[] = JSON::make('Content', [
        //         Text::make('Title'),
        //         Number::make('Price'),
        //         Textarea::make('Description'),
        //     ], 'data')->exceptOnForms()->hideFromIndex();
        // }

        // if (in_array($this->type, [
        //     \App\Confirmation::OPTION_NEW,
        //     \App\Confirmation::OPTION_EDIT,
        // ])) {
        //     $data[] = JSON::make('Content', [
        //         Text::make('Title'),
        //         Number::make('Price'),
        //     ], 'data')->exceptOnForms()->hideFromIndex();
        // }

        // return $data;
    }

    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            
            new Actions\ConfirmUpdate,
            new Actions\RejectRequest
        ];
    }
}
