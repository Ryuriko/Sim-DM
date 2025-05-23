<?php

namespace App\Export;

use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TicketExport implements FromView
{
    protected $month;
    protected $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }


    public function view(): View
    {
        return view('custom.export.ticket', [
            'datas' => Ticket::whereMonth('date', $this->month)->whereYear('date', $this->year)->get(),
            'month' => $this->month,
            'year' => $this->year,
        ]);
    }
}