<?php

namespace App\Models;

use App\Company;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'icon',
        'title',
        'content',
        'topic',
        'topic_id',
        'is_read'
    ];

    public function candidate ()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function employer ()
    {
        return $this->belongsTo(Company::class, 'user_id', 'id');
    }
}
