<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $table = 'simpanan';
    protected $primaryKey = 'id_simpanan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_simpanan', 'id_anggota', 'jumlah', 'tanggal', 'jenis_simpanan'];
       // tambahkan 'status' ke $appends agar selalu muncul di array/json
    protected $appends = ['status'];
    

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function getStatusAttribute()
    {
        // Jika relasi 'anggota' sudah eager-loaded atau ada, ambil status-nya
        return $this->anggota ? $this->anggota->status : null;
    }

}