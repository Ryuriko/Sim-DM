<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\HistoryPembelian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryPembelianDetail extends Model
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

    /**
     * Get the barang that owns the HistoryPembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /**
     * Get the pembelian that owns the HistoryPembelianDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pembelian()
    {
        return $this->belongsTo(HistoryPembelian::class, 'pembelian_id', 'id');
    }
}
