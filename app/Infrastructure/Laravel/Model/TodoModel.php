<?php

namespace App\Infrastructure\Laravel\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TodoModel extends Authenticatable
{
    use HasUuids;

    protected $fillable = [
        'title',
        'content',
        'due',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'todos';
}
