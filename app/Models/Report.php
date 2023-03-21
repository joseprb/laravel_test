<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'tgl',
        'pelapor',
        'judul',
        'solusi',
        'teknisi',
        'status',
    ];

    public function userPelapor() {
        return $this->belongsTo(User::class, 'pelapor');
    }

    public function userTeknisi() {
        return $this->belongsTo(User::class, 'teknisi');
    }
}
