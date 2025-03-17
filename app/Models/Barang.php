<?php

namespace App\Models;

use App\Models\Supplier;
use App\Models\KategoriBarang;
use App\Models\HistoryPembelianDetail;
use App\Models\HistoryPenggunaanDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    /**
     * Get the kategori that owns the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'id');
    }

    /**
     * Get the kategori that owns the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kategori_id', 'id');
    }

    /**
     * Get all of the historyPembelians for the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historyPembelianDetails()
    {
        return $this->hasMany(HistoryPembelianDetail::class, 'barang_id', 'id');
    }

    /**
     * Get all of the historyPembelians for the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historyPenggunaanDetails()
    {
        return $this->hasMany(HistoryPenggunaanDetail::class, 'barang_id', 'id');
    }
}
