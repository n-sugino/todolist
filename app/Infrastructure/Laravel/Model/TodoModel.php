<?php

namespace App\Infrastructure\Laravel\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TodoModel extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'content',
        'due',
    ];

    protected $table = 'todos';
}
