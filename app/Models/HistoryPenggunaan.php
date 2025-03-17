<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\HistoryPenggunaanDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryPenggunaan extends Model
{
    use HasFactory;

    /**
     * Get all of the detail for the HistoryPenggunaan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail()
    {
        return $this->hasMany(HistoryPenggunaanDetail::class, 'penggunaan_id', 'id');
    }
}
