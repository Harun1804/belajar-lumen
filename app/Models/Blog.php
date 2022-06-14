<?php

Namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
