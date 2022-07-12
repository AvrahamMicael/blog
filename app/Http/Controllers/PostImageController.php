<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class PostImageController extends Controller
{
    public function show(string $filename)
    {
        abort_if(Storage::missing($filename), 404);
        $filepath = storage_path("app/public/$filename");
        return response()->file($filepath);
    }
}
