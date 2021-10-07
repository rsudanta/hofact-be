<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'id_user',
        'id_jawaban',
    ];

    public function jawaban()
    {
        return $this->hasOne(Pertanyaan::class, 'id', 'id_jawaban');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
