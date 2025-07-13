<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CsvImportController extends Controller {
    public function import(Request $request) {
        $request->validate([
            'csv-files' => 'required|array',
            'csv-files.*' => 'mimes:csv,txt'
        ]);
        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($request['csv-files'] as $file) {
            $path = $file->store('csv-uploads', 'public');
        }
        return redirect()->back()->with(['message' => 'File Saved Successfully']);
    }
}
