<?php

namespace Likewares\Sortable\Tests\Models;

use Likewares\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Sortable;

    public $sortable = [
        'body',
        'created_at',
        'updated_at',
    ];

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
