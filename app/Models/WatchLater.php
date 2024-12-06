<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchLater extends Model
{
    use HasFactory;

    public $timestamps = false;  // Menonaktifkan timestamps karena kolom date_added sudah menangani waktu.
    protected $table = 'watch_laters';  // Menentukan nama tabel
    protected $primaryKey = 'id';  // Menentukan primary key

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'id_content',
        'date_added',
    ];

    /**
     * Definisikan relasi dengan model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Definisikan relasi dengan model Content
     */
    public function content()
    {
        return $this->belongsTo(Contents::class, 'id_content');
    }
    
}
