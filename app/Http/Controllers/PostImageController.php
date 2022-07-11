<?php

namespace App\Http\Controllers;

use App\Models\PostContent;

class PostImageController extends Controller
{
    public function show(int $id_image)
    {
        $file_name = PostContent::select('value')
            ->findOrFail($id_image)
            ->value;
        $filepath = storage_path("app/public/$file_name");
        return response()->file($filepath);
    }
}
