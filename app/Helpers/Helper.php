<?php

namespace App\Helpers;

use App\Models\Barang;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenggunaan;
use App\Models\HistoryPembelianDetail;

class Helper
{
    public static function updateStok($id, $data, $opr, $page = null)
    {
        // dd($opr);
        try{
            $barang = Barang::find($data['barang_id']);
            $subtotal = 0;
            
            if($opr == 'add') {
                $barang = $barang->update([
                    'stok' => (int)$barang['stok'] + (int)$data['jumlah']
                ]);
                
                if($page != 'penggunaan') {
                    $subtotal = (int)$data['jumlah'] * (int)$data['harga_satuan'];
                    $pembelian = HistoryPembelian::find($id);
                    $pembelian = $pembelian->update([
                        'harga_total' => $pembelian['harga_total'] + $subtotal
                    ]);
                } else {
                    $penggunaan = HistoryPenggunaan::find($id);
                    $penggunaan = $penggunaan->update([
                        'total_barang' => $penggunaan['total_barang'] + $data['jumlah']
                    ]);
                }
            } else if($opr == 'sub') {
                $barang = $barang->update([
                    'stok' => (int)$barang['stok'] - (int)$data['jumlah']
                ]);
                
                if($page != 'penggunaan') {
                    $pembelian = HistoryPembelian::find($id);
                    $pembelian = $pembelian->update([
                        'harga_total' => $pembelian['harga_total'] - $subtotal
                    ]);
                } else {
                    $penggunaan = HistoryPenggunaan::find($id);
                    $penggunaan = $penggunaan->update([
                        'total_barang' => $penggunaan['total_barang'] - $data['jumlah']
                    ]);
                }
            } else if($opr == 'edit') {
                $stok = (int)$data['jumlah'] - (int)$data['jumlah_before'];

                $barang = $barang->update([
                    'stok' => (int)$barang['stok'] + (int)$stok
                ]);
                
                if($page != 'penggunaan') {
                    $subtotal = (int)$data['jumlah'] * (int)$data['harga_satuan'];
                    $subtotalEdit = $subtotal - $data['subtotal_before'];
                    
                    $pembelian = HistoryPembelian::find($id);
                    $pembelian = $pembelian->update([
                        'harga_total' => $pembelian['harga_total'] + $subtotalEdit
                    ]);
                } else {
                    $penggunaan = HistoryPenggunaan::find($id);
                    $penggunaan = $penggunaan->update([
                        'total_barang' => $penggunaan['total_barang'] + $stok
                    ]);
                }
            }

            return [
                'status' => true,
                'subtotal' => $subtotal
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
        
    }
    
}
