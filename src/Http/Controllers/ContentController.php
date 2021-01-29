<?php

namespace Ogecut\ContentApi\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Ogecut\ContentApi\Models\ContentBlock;

class ContentController extends Controller
{
    public function showBlock(Request $request, string $code): JsonResource
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1',
            'search' => 'nullable|string',
        ]);
        
        /** @var ContentBlock $block */
        $block = ContentBlock::query()
            ->with([
                'items' => function (HasMany $query) use ($request) {
                    if ($limit = $request->input('limit')) {
                        $query->limit($limit);
                    }
                    
                    if ($search = $request->input('search')) {
                        $query->where(function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                            $query->orWhere('content', 'like', "%{$search}%");
                        });
                    }
                    
                    $query->with([
                        'media' => function (MorphMany $query) {
                            $query->select([
                                'id',
                                'model_type',
                                'model_id',
                                'uuid',
                                'name',
                                'file_name',
                                'mime_type',
                                'disk',
                                'size',
                                'order_column',
                            ]);
                        },
                    ]);
                },
            ])
            ->select(['id', 'name', 'code', 'description'])
            ->where('code', $code)
            ->firstOrFail();
        
        return new JsonResource([
            'data' => $block->getData(),
        ]);
    }
}
