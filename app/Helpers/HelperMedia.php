<?php


namespace App\Helpers;


use App\Models\Media;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HelperMedia
{
    public static function delete($mediaId)
    {
        $media = Media::find($mediaId);
        if ($media == null)
            return;

        if (($media->thumbnail_id != null) && ($media->thumbnail_id != $mediaId))
            self::delete($media->thumbnail_id);

        if (!is_null($media->path))
            Storage::disk('local')->delete($media->path);

        $media->delete();
    }

    public static function uploadPicture(UploadedFile $file, $path, $width = null, $height = null, $uploaded = false, Media $replace = null, $thumbnail = true): ?int
    {
        $filename = (($replace != null) && (strlen($replace->filename) > 0)) ? $replace->filename : Carbon::now()->format('Y-m-d') . '_' . time() . '.png';
        try {
            $imageResize = Image::make($file);
            if ($width != null && $height != null)
                $imageResize->resize($width, $height);

            $imageResize->encode('png', 100);

            $imagePath = 'public/' . $path . $filename;
            Storage::disk('local')->put($imagePath, $imageResize);

            $thumbResize = Image::make($file)->widen(100)->encode('png', 70);
            $thumbPath = 'public/' . $path . 'thumbnails/' . $filename;
            Storage::disk('local')->put($thumbPath, $thumbResize);

            DB::beginTransaction();

            if($thumbnail) {
                $thumb = (($replace != null) && ($replace->thumbnail != null)) ? $replace->thumbnail : new Media();
                $thumb->mime_type = 'image/png';
                $thumb->size = $thumbResize->filesize();
                $thumb->filename = $filename;
                $thumb->path = $thumbPath;
                $thumb->save();
            }

            $media = ($replace != null) ? $replace : new Media();
            $media->mime_type = 'image/png';
            $media->size = $imageResize->filesize();
            $media->filename = $filename;
            $media->path = $imagePath;
            $media->thumbnail_id = $thumbnail ? $thumb->id : null;
            $media->uploaded = $uploaded;
            $media->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Helper Media -> ' . $exception->getMessage());

            if (isset($imagePath))
                Storage::disk('local')->delete($imagePath);

            if (isset($thumbPath))
                Storage::disk('local')->delete($thumbPath);

            throw new ErrorException('Upload has been failed.', 504);
        }

        return $media->id;
    }

    public static function uploadFile(UploadedFile $file, $path, Media $replace = null): Media
    {
        try {
            $path = 'public/' . $path;
            $uploadedPath = Storage::disk('local')->put($path, $file);

            $media = ($replace != null) ? $replace : new Media();
            $media->mime_type = $file->getClientMimeType();
            $media->size = $file->getSize();
            $media->filename = $file->getClientOriginalName();
            $media->path = $uploadedPath;
            $media->uploaded = true;
            $media->save();

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Helper Media -> ' . $exception->getMessage());

            if (isset($path))
                Storage::disk('local')->delete($path);

            throw new ErrorException('Upload has been failed.', 504);
        }

        return $media;
    }

    public static function remove($path)
    {
        Storage::disk('local')->delete($path);
    }

    public static function humanFileSize($size, $unit = "")
    {
        if ((!$unit && $size >= 1 << 30) || $unit == "GB")
            return number_format($size / (1 << 30), 2) . " GB";
        if ((!$unit && $size >= 1 << 20) || $unit == "MB")
            return number_format($size / (1 << 20), 2) . " MB";
        if ((!$unit && $size >= 1 << 10) || $unit == "KB")
            return number_format($size / (1 << 10), 2) . " KB";
        return number_format($size) . " bytes";
    }
}
