<?php

namespace Ogecut\ContentApi\Resources;

use App\Nova\Resource;
use App\Shop\Catalog\Entities\Product\Product;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ogecut\ContentApi\Models\ContentBlock;
use Ogecut\ContentApi\Models\ContentBlockItem;
use Techouse\SelectAutoComplete\SelectAutoComplete;

/**
 * Class ContentBlockItemResource
 *
 * @package App\Nova\Content
 * @property-read ContentBlockItem $resource
 */
class ContentBlockItemResource extends Resource
{
    public static $group = 'Контент';
    
    public static $displayInNavigation = false;
    
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = ContentBlockItem::class;
    
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'id';
    
    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id',
    ];
    
    public static $with = ['block'];
    
    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return 'Элементы';
    }
    
    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Элементы');
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
        parse_str(parse_url($request->headers->get('referer'), PHP_URL_QUERY), $params);
        // Хак, потомучто nova не передает GET параметры в свои компоненты
        if (!empty($this->resource->block_id)) {
            $block = $this->resource->block;
        } elseif (isset($params['block_id'])) {
            $blockId = $params['block_id'];
            $block = ContentBlock::find($blockId);
        } else {
            $block = null;
        }
        
        $fields = [
            Text::make(__('Id'), 'id')->exceptOnForms(),
            Text::make('name')->required()->rules(['required']),
            Number::make('Сортировка', 'sort')->default(1),
            Boolean::make('Активность', 'visible'),
        ];
        
        if ($block) {
            $fields[] = Hidden::make('block_id')->default($block->id);
            
            if ($r = $block->getRelationField()) {
                if ($r['visible'] && !empty($r['type'])) {
                    $fields[] = Heading::make('Настройка связи');
                    
                    $fields[] = Hidden::make('relation_type')->default($r['type']);
                    $fields[] = SelectAutoComplete::make($r['name'] ?? 'Связь', 'relation_id')
                        ->options(
                            forward_static_call([$r['type'], 'get'])->mapWithKeys(function ($t) {
                                return [$t->id => $t->name ?? $t->title];
                            })
                        )
                        ->displayUsingLabels();
                }
            }
            
            $fields[] = Heading::make('Кастомные поля');
            foreach ($block->getFields() as $field) {
                /** @var Field $instance */
                $instance = forward_static_call([$field['type'], 'make'], $field['name'], "content__{$field['code']}");
                $instance->rules($field['validators']);
                
                if ($field['required']) {
                    $instance->required();
                }
                
                if (!$field['show_on_index']) {
                    $instance->hideFromIndex();
                }
                
                if (!empty($field['placeholder'])) {
                    $instance->placeholder($field['placeholder']);
                }
                
                if (!empty($field['default_value'])) {
                    $instance->default(static fn() => $field['default_value']);
                }
                
                if (!empty($field['option_list']) && in_array($field['type'], [Select::class, BooleanGroup::class], true)) {
                    $instance->options(array_combine($field['option_list'], $field['option_list']));
                }
                
                $fields[] = $instance;
            }
        }
        
        
        return $fields;
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
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        parse_str(parse_url($request->headers->get('referer'), PHP_URL_QUERY), $params);
        
        if (isset($params['block_id'])) {
            $query->where('block_id', $params['block_id']);
        } else {
            $query->where('block_id', 0);
        }
        
        return parent::indexQuery($request, $query);
    }
}
