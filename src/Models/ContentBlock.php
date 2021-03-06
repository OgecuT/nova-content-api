<?php

namespace Ogecut\ContentApi\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use JsonException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

/**
 * @package Ogecut\ContentApi\Models
 * @mixin Eloquent
 *
 * @property int $id
 * @property string|null $name
 *
 * @property-read  ContentBlockItem[]|Collection $items
 *
 * @method static | ContentBlock find(int $id)
 * @method static | ContentBlock first()
 */
class ContentBlock extends Model implements HasMedia
{
    use InteractsWithMedia, HasFlexible;
    
    protected $table = 'content_blocks';
    protected $casts = [
        'fields' => 'array',
        'relation' => 'array',
    ];
    protected $withCount = ['items'];
    
    /**
     * Связь с элементами блока
     */
    public function items(): HasMany
    {
        return $this->hasMany(ContentBlockItem::class, 'block_id', 'id')
            ->select(['id', 'block_id', 'name', 'content', 'relation_id', 'relation_type'])
            ->where('visible', 1)
            ->orderBy('sort');
    }
    
    /**
     * Связь с групой
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(ContentBlockGroup::class);
    }
    
    public function __set($key, $value)
    {
        if ($key === 'fields') {
            $fields = is_string($value) ? json_decode($value, true, 512, JSON_THROW_ON_ERROR) : $value;
            
            if (is_array($fields)) {
                foreach ($fields as $index => $field) {
                    $rowNum = $index + 1;
                    
                    if (empty($field['name'])) {
                        throw new \DomainException("В строке №{$rowNum} - \"Названия поля*\" не заполнено!");
                    }
                    
                    if (empty($field['code'])) {
                        throw new \DomainException("В строке №{$rowNum} - \"Код поля*\" не заполнено!");
                    }
                    
                    if (empty($field['type'])) {
                        throw new \DomainException("В строке №{$rowNum} - \"Тип поля*\" не заполнено!");
                    }
                }
            }
        }
        
        parent::__set($key, $value);
    }
    
    /**
     * Разбирает JSON и формирует данные динамических полей
     */
    public function getFields(): array
    {
        try {
            $fields = json_decode($this->attributes['fields'] ?? '[]', true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return [];
        }
        $fields = array_filter($fields, static fn(array $field) => (bool) $field['visible']);
        $fields = array_map(static function (array $item) {
            $item['sort'] = (int) $item['sort'];
            $item['visible'] = (bool) $item['visible'];
            $item['required'] = (bool) $item['required'];
            $item['show_on_index'] = (bool) $item['show_on_index'];
            
            $item['validators'] = Arr::pluck(
                json_decode($item['validators'] ?? '[]', true, 512, JSON_THROW_ON_ERROR), 'value'
            );
            $item['option_list'] = Arr::pluck(
                json_decode($item['option_list'] ?? '[]', true, 512, JSON_THROW_ON_ERROR), 'value'
            );
            
            if ($item['required'] && !in_array('required', $item['validators'], true)) {
                $item['validators'][] = 'required';
            }
            
            return $item;
        }, $fields);
        
        // Сортировка полей
        usort($fields, static function ($a, $b) {
            if ($a['sort'] === $b['sort']) {
                return 0;
            }
            
            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });
        
        return $fields;
    }
    
    public function getRelationField(): array
    {
        return $this->getAttribute('relation') ?? [];
    }
    
    /**
     * Формирует массив для api ответа
     */
    public function getData(): array
    {
        $data = $this->toArray();
        $data['items'] = array_map(static function (array $item) {
            if (!empty($item['content']) && is_array($item['content'])) {
                foreach ($item['content'] as $name => $value) {
                    $item[$name] = $value;
                }
            }
            
            if (!empty($item['media']) && is_array($item['media'])) {
                $item['media'] = array_map(static function (array $m) {
                    $m['path'] = "{$m['disk']}/{$m['file_name']}";
                    
                    unset($m['model_type'], $m['model_id'], $m['disk']);
                    return $m;
                }, $item['media']);
            }
            
            unset($item['content'], $item['relation_id'], $item['relation_type']);
            return $item;
        }, $data['items']);
        
        return $data;
    }
}
