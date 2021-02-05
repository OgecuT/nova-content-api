<?php

namespace Ogecut\ContentApi\Admin\Resources;

use App\Nova\Resource;
use Exception;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Ogecut\ContentApi\Models\ContentBlock;
use Ogecut\ContentApi\Models\ContentBlockGroup;


/**
 * Class ContentBlockResource
 *
 * @package App\Nova\Content
 * @property-read ContentBlock $resource
 */
class ContentGroupResource extends Resource
{
    public static $group = 'Контент';
    
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = ContentBlockGroup::class;
    
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'name';
    
    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id',
        'name',
    ];
    
    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return 'Групы';
    }
    
    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Група');
    }
    
    /**
     * Get the fields displayed by the resource.
     *
     * @param  Request  $request
     * @return  array
     * @throws Exception
     */
    public function fields(Request $request)
    {
        return [
            Text::make(__('Название блока'), 'name'),
        ];
    }
    
    /**
     * Get the cards available for the request.
     *
     * @param  Request  $request
     * @return  array
     */
    public function cards(Request $request)
    {
        return [];
    }
    
    /**
     * Get the filters available for the resource.
     *
     * @param  Request  $request
     * @return  array
     */
    public function filters(Request $request)
    {
        return [];
    }
    
    /**
     * Get the lenses available for the resource.
     *
     * @param  Request  $request
     * @return  array
     */
    public function lenses(Request $request)
    {
        return [];
    }
    
    /**
     * Get the actions available for the resource.
     *
     * @param  Request  $request
     * @return  array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
