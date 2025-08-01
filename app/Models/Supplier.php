<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\HistoryPembelianDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    /**
     * Get all of the barangs for the KategoriBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'supplier_id', 'id');
    }

    /**
     * Get all of the barangs for the KategoriBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historyPembelianDetails()
    {
        return $this->hasMany(HistoryPembelianDetail::class, 'supplier_id', 'id');
    }
    
}
