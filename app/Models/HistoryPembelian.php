<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\HistoryPembelianDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryPembelian extends Model
{
    use HasFactory;

    /**
     * Get all of the detail for the HistoryPembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail()
    {
        return $this->hasMany(HistoryPembelianDetail::class, 'pembelian_id', 'id');
    }
}
