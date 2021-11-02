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
        'total_vote',
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

    public function vote()
    {
        return $this->hasMany(Vote::class, 'id_jawaban', 'id');
    }
    
    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function getStatusAttribute()
    {
        return $this->vote;
    }

    protected $appends = ['name','status'];

}
