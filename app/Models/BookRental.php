<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRental extends Model
{
    use HasFactory;

    protected $table = 'book_rental';
    protected $guarded = [];

    /**
     * Relations
     */
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
