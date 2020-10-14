<?php

namespace App\Models;

use App\Libs\GenresConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model implements GenresConstants
{
    use HasFactory;
    protected $table = 'genres';

    /**
     * Relations
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
