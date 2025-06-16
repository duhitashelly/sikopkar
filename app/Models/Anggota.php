<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'id_anggota',
        'nama',
        'alamat',
        'kontak',
        'status',
        'jenis_anggota',
        'tanggal_daftar'
    ];

    // Relasi ke tabel pinjaman
    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class, 'id_anggota', 'id_anggota');
    }

    // Relasi ke tabel simpanan
    public function simpanan()
    {
        // Sesuaikan 'id_anggota' jika nama kolom foreign key di tabel 'simpanan' berbeda
        // Parameter pertama adalah Model tujuan (Simpanan::class)
        // Parameter kedua adalah foreign key di tabel 'simpanan' yang merujuk ke 'anggota'
        // Parameter ketiga adalah local key di tabel 'anggota' (primary key Anggota)
        return $this->hasMany(Simpanan::class, 'id_anggota', 'id_anggota');
    }
}
