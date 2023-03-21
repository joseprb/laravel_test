<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ReportsImport;

class ReportController extends Controller
{

    public function index() {
        $reports = Report::all();
        $users = User::where('role', 'teknisi')->get();

        return view('laporan', [
            "reports" => $reports,
            "users" => $users
        ]);
    }

    public function importExcel(Request $request)
    {

        $request->validate([
            'file'=>'required|mimes:xlsx'
        ]);

        $file = $request->file('file');
        $path = Storage::putFileAs('temp', $file, $file->getClientOriginalName());

        $import = new ReportsImport;

        // dd($import);
        Excel::import($import, $path);

        return redirect()->back()->with('success', 'Data imported successfully!');

    }

}
