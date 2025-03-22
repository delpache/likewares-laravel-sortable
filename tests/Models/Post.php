<?php

namespace Likewares\Sortable\Tests\Models;

use Likewares\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sortable;
}
