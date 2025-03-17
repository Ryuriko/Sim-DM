<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryPenggunaan extends Model
{
    use HasFactory;

    /**
     * Get the barang that owns the HistoryPembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}
