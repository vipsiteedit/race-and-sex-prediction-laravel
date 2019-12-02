<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaceFormRequest;
use App\Jobs\SendApiImage;
use Illuminate\Http\Request;
use App\Face;
use Intervention\Image\ImageManagerStatic as Image;

class FaceController extends Controller
{
    const MAX_IMAGE_SIZE = 2048000;
    private $storageImagePath;
    private $storagePrevPath;

    public function __construct()
    {
        $this->storageImagePath = storage_path() . '/app/not_public/uploads/';
        $this->storagePrevPath = storage_path() . '/app/public/prev/';
    }

    public function fetch(int $uid)
    {
        $prevPath = storage_path() . '/app/public/prev/';
        if (!is_dir($prevPath)) mkdir($prevPath);

        $faceList = Face::faceList($uid);
        foreach($faceList as &$face) {
            $this->addImagePrev($face);
            $this->resultJsonToArray($face);
        }
        return response(['status' => 'success', 'result' => $faceList]);
    }

    public function info(int $uid, $fid)
    {
        $faceItem = Face::faceItem($uid, $fid);
        $this->addImagePrev($faceItem);
        $this->resultJsonToArray($faceItem);
        if (empty($faceItem)) $faceItem = [];
        return response(['status' => 'success', 'result' => $faceItem]);
    }

    public function new(int $uid, FaceFormRequest $request)
    {
        try {

            $newPath = $request->file('image')->store('uploads', 'not_public');

            // отправляем имя файла в API
            $pathImage = storage_path() . '/app/not_public/' . $newPath;
            $token = md5($pathImage);

            // Создаем запись в таблице процессов и получаеи ID для дальнейшей идентификации и получения результата
            $face =  (new Face);
            $face->user_id = $uid;
            $face->name = $request->name;
            $face->photo = basename($pathImage);
            $face->k = $this->createImagePrev(basename($pathImage), 400); //Коэффициент между оригиналом и превью
            $face->token = $token;
            $face->save();
            if ($face->id && file_exists($pathImage)) {
                // Добавляем обработчик в очередь
                SendApiImage::dispatch($pathImage);
                return $this->info($uid, $face->id);
            } else {
                return response(['status' => 'error', 'error' => 'Что-то пошло не так, не сформирован ']);
            }
        } catch(\Exception $e) {
            info($e->getMessage());
            return response(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function delete(int $uid, $fid)
    {
        // Перед удаление записи удаляем все фото из хранилища
        $this->photosUnlink($uid, $fid);
        Face::faceDelete($uid, $fid);
        return $this->fetch($uid);
    }

    public function update(int $uid, $fid, FaceFormRequest $request)
    {
        $face = (new Face)->where('user_id', $uid)->where('id', $fid)->first();
        $face->name = $request->name;
        if ($face->save()) {
            return $this->info($uid, $fid);
        } else {
            return response(['status' => 'error', 'error' => 'Что-то пошло не так, не не могу изменить имя']);
        }
    }

    private function createImagePrev($imageName, $wPrev = 400)
    {
        if (!empty($imageName)) {
            //сделаем привью картинки
            $widthOriginal = 0;
            if (file_exists($this->storageImagePath . $imageName)) {
                $image = Image::make($this->storageImagePath . $imageName);
                $widthOriginal = $image->width();
                if (!file_exists($this->storagePrevPath . $imageName)) {
                    $image->resize($wPrev, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($this->storagePrevPath . $imageName, 90);
                }
                return ($wPrev / $widthOriginal);
            }
        }
    }

    private function addImagePrev(&$face)
    {
        if (file_exists($this->storagePrevPath . $face['photo'])) {
            $face['imagePrev'] = url('/') . '/storage/prev/' . $face['photo'];
        }
    }

    private function photosUnlink(int $uid, $fid)
    {
        $faceItem = Face::faceItem($uid, $fid);
        if (file_exists($this->storageImagePath . $faceItem['photo'])) {
            unlink($this->storageImagePath . $faceItem['photo']);
        }
        if (file_exists($this->storagePrevPath . $faceItem['photo'])) {
            unlink($this->storagePrevPath . $faceItem['photo']);
        }
    }

    private function resultJsonToArray(&$face) {
        if (!empty($face['result'])) {
            $face['result'] = json_decode($face['result'], true);
        }
    }

}
