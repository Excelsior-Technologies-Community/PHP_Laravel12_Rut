<?php

namespace App\Http\Controllers;

use App\Imports\CitizensImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function create()
    {
        return view('citizens.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        try {
            Excel::import(new CitizensImport, $request->file('file'));

            return back()->with('success', 'Citizens imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->withErrors($e->errors())->with('error', 'Some rows failed validation.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $fileName = 'citizens-import-template.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'rut']);
            fputcsv($file, ['John Doe', 'john@example.com', '18.765.432-1']);
            fputcsv($file, ['Jane Smith', 'jane@example.com', '12.345.678-9']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
