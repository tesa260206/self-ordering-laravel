<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableStatusController extends Controller
{
    public function index()
    {
        // Ambil semua meja beserta data pesanan aktifnya
        $tables = Table::with('activeOrder')->orderBy('table_number', 'asc')->get();
        
        $totalTables = $tables->count();
        $occupiedTables = $tables->where('status', 'occupied')->count();
        $availableTables = $totalTables - $occupiedTables;

        return view('admin.table-status.index', compact('tables', 'totalTables', 'occupiedTables', 'availableTables'));
    }
}