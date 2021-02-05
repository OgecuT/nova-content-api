<?php

namespace Ogecut\ContentApi\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @package Ogecut\ContentApi\Models
 * @mixin Eloquent
 *
 * @property int $id
 * @property string|null $name
 *
 * @method static | ContentBlock find(int $id)
 * @method static | ContentBlock first()
 */
class ContentBlockGroup extends Model
{
    protected $table = 'content_block_groups';
    
    public $timestamps = false;
}
