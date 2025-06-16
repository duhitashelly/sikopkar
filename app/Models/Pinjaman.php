<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';
    protected $primaryKey = 'id_pinjaman';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pinjaman',
        'id_anggota',
        'jumlah',
        'tenor',
        'tanggal_pinjaman',
        'status'
    ];

    // Relasi ke model Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'id_pinjaman', 'id_pinjaman');
    }
}