<?php

namespace Likewares\Sortable\Tests\Models;

use Likewares\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use Sortable;

    public $sortable = [
        'phone',
        'address',
        'composite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function compositeSortable($query, $direction)
    {
        return $query->orderBy('phone', $direction)->orderBy('address', $direction);
    }
}
