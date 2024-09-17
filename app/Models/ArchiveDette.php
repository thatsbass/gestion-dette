<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveDette extends Model
{
    use HasFactory;
    protected $table = "archived_dettes";
    protected $fillable = [
        "dette_id",
        "archived_at",
        "restored_at",
        "cloud_from",
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function dette()
    {
        return $this->belongsTo(Dette::class, "dette_id");
    }
}
