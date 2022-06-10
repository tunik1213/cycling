<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Intervention\Image\ImageManagerStatic;

class ImageController extends Controller
{
    public function uploadImage(request $request)
    {
        $result = ['success' => false];

        if (!empty($_FILES['filename']['tmp_name'])) {
            $img = new Image();
            $img->image = ImageManagerStatic::make($_FILES['filename']['tmp_name'])
                ->fit(500)
                ->encode('jpg', 75);
            $img->save();

            $result['url'] = $img->url;
            $result['success'] = true;
        }

        echo json_encode($result);

    }

    public function getImage($id)
    {
        $record = Image::find($id);
        if (empty($record))
            abort(404);

        $img =  $record->image;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
        exit;
    }
}
