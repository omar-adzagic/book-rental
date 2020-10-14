<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $guarded = [];
    protected $appends = ['my_book', 'rented', 'rented_by_me'];

    /**
     * Accessors
     */
    public function getMyBookAttribute($value)
    {
        return ! empty(Auth::user()) ? $this->user_id == Auth::user()->id : null;
    }
    public function getRentedAttribute($value)
    {
        return ! empty(Auth::user()) ? !empty($this->bookRental) : null;
    }
    public function getRentedByMeAttribute($value)
    {
        return ! empty(Auth::user()) && ! empty($this->bookRental) ? $this->bookRental->tenant_id == Auth::user()->id : null;
    }

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookRental()
    {
        return $this->hasOne(BookRental::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
