<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
    ];

    /**
     * Rlation between action and user.
     *
     * @var object
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
