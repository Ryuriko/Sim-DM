<?php
    $harga = 0;
    $total = 0;  
?>
<table style="border: 5px">
    <thead>
    <tr>
        <td rowspan="3" colspan="8" bgcolor="#00FF00" align="center" valign="center" style="font-size: 15px; font-weight: bold">
            Laporan Penjualan Tiket Waterboom Bulan {{ Carbon\Carbon::createFromDate(null, $month, 1)->translatedFormat('F') }} Tahun {{ $year }}
        </td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <th bgcolor="#00FF00" width="90px" style="font-weight: bold">Order ID</th>
        <th bgcolor="#00FF00" width="90px" style="font-weight: bold">Nama</th>
        <th bgcolor="#00FF00" width="80px" style="font-weight: bold">Jumlah Tiket</th>
        <th bgcolor="#00FF00" width="90px" style="font-weight: bold">Tanggal</th>
        <th bgcolor="#00FF00" width="90px" style="font-weight: bold">Status</th>
        <th bgcolor="#00FF00" width="130px" style="font-weight: bold">Dibayar</th>
        <th bgcolor="#00FF00" width="130px" style="font-weight: bold">Digunakan</th>
        <th bgcolor="#00FF00" width="90px" style="font-weight: bold">Total Harga</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
            <td align="center">{{ $data->orderId }}</td>
            <td align="center">{{ $data->user->name }}</td>
            <td align="center">{{ $data->qty }}</td>
            <td align="center">{{ $data->date }}</td>
            <td align="center">{{ $data->status }}</td>
            <td align="center">{{ $data->paid_at }}</td>
            <td align="center">{{ $data->used_at }}</td>
            <?php
                $harga = (int)$data->qty * 35000;
                
                $total = $total + ($data->status == 'paid' ? $harga : 0);
            ?>
            <td align="center">Rp. {{number_format($harga, 0, ',', '.')}}</td>
        </tr>
    @endforeach
        <tr>
            <td colspan="6" align="center" style="font-weight: bold; font-size: 13px">Total (Paid)</td>
            <td colspan="2" align="center" style="font-weight: bold; font-size: 13px">Rp. {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>