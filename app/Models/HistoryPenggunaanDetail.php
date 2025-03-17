<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\HistoryPenggunaan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryPenggunaanDetail extends Model
{
    use HasFactory;

    /**
     * Get the penggunaan that owns the HistoryPenggunaanDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penggunaan()
    {
        return $this->belongsTo(HistoryPenggunaan::class, 'penggunaan_id', 'id');
    }

    /**
     * Get the penggunaan that owns the HistoryPenggunaanDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}
