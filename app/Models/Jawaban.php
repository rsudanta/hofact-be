<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jawaban extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'isi_jawaban',
        'file',
        'vote',
        'is_terverifikasi',
        'id_user',
        'id_pertanyaan',
    ];

    public function pertanyaan()
    {
        return $this->hasOne(Pertanyaan::class, 'id', 'id_pertanyaan');
    }
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
