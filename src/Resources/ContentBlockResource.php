<?php

namespace Ogecut\ContentApi\Resources;

use App\Nova\Resource;
use Benjaminhirsch\NovaSlugField\Slug;
use Benjaminhirsch\NovaSlugField\TextWithSlug;
use Exception;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Ogecut\ContentApi\Models\ContentBlock;
use OptimistDigital\NovaSimpleRepeatable\SimpleRepeatable;


/**
 * Class ContentBlockResource
 *
 * @package App\Nova\Content
 * @property-read ContentBlock $resource
 */
class ContentBlockResource extends Resource
{
    public static $group = 'Контент';
    
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = ContentBlock::class;
    
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
    
    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return 'Блоки';
    }
    
    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Блоки');
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
            Text::make(__('Id'), 'id')
                ->displayUsing(function () {
                    return "<a href='/nova/resources/content-block-item-resources?block_id={$this->resource->id}'>{$this->resource->id}</a>";
                })
                ->asHtml()
                ->exceptOnForms()
            ,
            
            // Только в списке
            Text::make(__('Название блока'), 'name')
                ->displayUsing(function () {
                    return "<a href='/nova/resources/content-block-item-resources?block_id={$this->resource->id}'>{$this->resource->name}</a>";
                })
                ->asHtml()
                ->onlyOnIndex()
            ,
            
            // На детальной и формах
            TextWithSlug::make(__('Название блока'), 'name')
                ->rules('required')
                ->slug('code')
                ->hideFromIndex()
            ,
            
            Slug::make(__('Код (Авто)'), 'code')
                ->rules('required')
                ->displayUsing(function () {
                    return "
                        <p class='mb-2'>{$this->resource->code}</p>
                        <a href='/api/content-api/blocks/{$this->resource->code}' target='_blank'>Смотреть API</a>
                    ";
                })
                ->asHtml()
            ,
            
            Number::make('Сортировка', 'sort')
                ->default(1)
            ,
    
            Text::make('Кол. элементов', 'items_count')
                ->onlyOnIndex()
            ,
            
            Trix::make('Описание', 'description')
            ,
            
            Heading::make('Динамические поля, <a href="https://laravel.com/docs/8.x/validation#available-validation-rules" target="_blank">Список валидаторов</a>')
                ->asHtml(),
            
            SimpleRepeatable::make('Поля', 'fields', [
                Text::make('Названия поля*', 'name')
                    ->help('<strong>Обязательное поле!</strong>')
                ,
                
                Text::make('Код поля*', 'code')
                    ->help('<strong>Обязательное поле!</strong>')
                ,
                
                Text::make('Значение по умолчанию', 'default_value')
                ,
                
                Text::make('Placeholder', 'placeholder')
                    ->help('Подсказка в поле при заполнении в админ панели')
                ,
                
                Select::make('Тип поля*', 'type')
                    ->options(config('content-api.available_fields'))
                    ->displayUsingLabels()
                    ->help('<strong>Обязательное поле!</strong>')
                ,
                
                SimpleRepeatable::make('Значения для Select,Radio', 'option_list', [
                    Text::make(__('Значения'), 'value'),
                ]),
                
                Boolean::make('Обяз.', 'required'),
                
                Boolean::make('Показать в списке?', 'show_on_index'),
                
                Boolean::make('Актив.', 'visible'),
                
                SimpleRepeatable::make('Правила валидации', 'validators', [
                    Text::make(__('Значения'), 'value'),
                ]),
                
                Number::make('Сорт.', 'sort'),
            ])
                ->stacked()
            ,
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
