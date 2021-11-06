<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'judul_pertanyaan',
        'isi_pertanyaan',
        'id_user',
        'gambar_url'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'id_pertanyaan', 'id');
    }

    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function getIsiJawabanAttribute()
    {
        return $this->jawaban;
    }

    protected $appends = ['name', 'isi_jawaban'];
}
