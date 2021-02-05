<?php

namespace Ogecut\ContentApi\Admin\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Ogecut\ContentApi\Models\ContentBlockGroup;

class GroupFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        if (!empty($value)) {
            $query->where('group_id', $value);
        }
        
        return $query;
    }
    
    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return ContentBlockGroup::get()->pluck('id', 'name')->toArray();
    }
}
