<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pertanyaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'judul_pertanyaan',
        'isi_pertanyaan',
        'id_user',
        'picture_path',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
