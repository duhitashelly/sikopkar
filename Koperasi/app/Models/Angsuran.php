<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $table = 'angsuran';
    protected $primaryKey = 'id_angsuran';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'id_angsuran',
        'id_anggota', 
        'id_pinjaman',
        'jumlah_angsuran',
        'tanggal',
    ];
    
    // Relasi ke model Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman', 'id_pinjaman');
    }

}