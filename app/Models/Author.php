<?php

namespace App\Models;

use App\Libs\AuthorsConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model implements AuthorsConstants
{
    use HasFactory;
    protected $table = 'authors';

    /**
     * Relations
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
