<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Table name
     */
    protected $table = 'categories';

    /**
     * Primary key
     */
    protected $primaryKey = 'id';

    /**
     * Array column name which allow to store
     */
    protected $fillable = [
        'name',
        'sub_category',
        'service',
        'keywords',
        'embedding'
    ];
}
