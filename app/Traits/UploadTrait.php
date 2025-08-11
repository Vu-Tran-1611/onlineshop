<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
trait UploadTrait{
    public function uploadImage(Request $request, $pathName, $inputName)
    {
        if ($request->hasFile($inputName)) {
            $image = $request->$inputName;

            // Create directory if it doesn't exist
            $uploadPath = public_path($pathName);
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Generate unique filename
            $extension = $image->getClientOriginalExtension();
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $imageName = date('Y-m-d') . "_" . time() . "_" . uniqid() . "_" . $originalName . "." . $extension;

            $path = $pathName . "/" . $imageName;
            $image->move($uploadPath, $imageName);
            return $path;
        }
        return null;
    }

    public function updateImage(Request $request, $oldAvatar, $pathName, $inputName)
    {
        if ($request->hasFile($inputName)) {
            // Delete old image if it exists
            if ($oldAvatar && File::exists(public_path($oldAvatar))) {
                File::delete(public_path($oldAvatar));
            }

            $image = $request->$inputName;

            // Create directory if it doesn't exist
            $uploadPath = public_path($pathName);
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Generate unique filename
            $extension = $image->getClientOriginalExtension();
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $imageName = date('Y-m-d') . "_" . time() . "_" . uniqid() . "_" . $originalName . "." . $extension;

            $path = $pathName . "/" . $imageName;
            $image->move($uploadPath, $imageName);
            return $path;
        }
        return null; // Return null if no new image uploaded, let controller handle the logic
    }
    public function uploadMultiImage(Request $request, $name, $pathName)
    {
        $paths = array();
        $images = $request->$name;

        // Create directory if it doesn't exist
        $uploadPath = public_path($pathName);
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        foreach ($images as $image) {
            // Generate unique filename to prevent conflicts
            $extension = $image->getClientOriginalExtension();
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $imageName = date("Y-m-d") . "_" . time() . "_" . uniqid() . "_" . $originalName . "." . $extension;

            // Move the file
            $image->move($uploadPath, $imageName);
            $path = $pathName . "/" . $imageName;
            $paths[] = $path;
        }
        return $paths;
    }
    public function deleteImage(string $path)
    {
        if (File::exists(public_path($path))) File::delete(public_path($path));
    }
}
