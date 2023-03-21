<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Report;

class ReportsImport implements ToModel
{
    public function model(array $row)
    {
        return new Report([
            // 'tgl' => strtotime($row[0]),
            'tgl' => date("Y-m-d", strtotime($row[0])),
            'pelapor' => auth()->user()->id,
            'judul' => $row[1],
            'status' => 'open',
        ]);
    }
}