<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use mysql_xdevapi\Exception;


class ImageController extends Controller
{
    const MAX_IMAGE_SIZE = 2048000;
    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload(Request $request)
    {
        $path = $request->file('image')->store('uploads', 'not_public');

        // отправляем имя файла в API
        $imagePath = storage_path() . '/app/not_public/' . $path;

        echo json_encode($this->postApi($imagePath));
        exit;
    }

    /**
     * @param string $imagePath
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function postApi(string $imagePath)
    {
        try {

            $client = new \GuzzleHttp\Client();
            $url = "http://race-and-sex-prediction-dev.us-east-2.elasticbeanstalk.com/predict";
            $fileSize = filesize($imagePath);
            if ($fileSize > self::MAX_IMAGE_SIZE) {
                $message = sprintf('Larger image file size %s kb!', round(self::MAX_IMAGE_SIZE / 1000));
                throw new \Exception($message);
            }
            $fileContent = \File::get($imagePath);

            //Удаляем использованный файл
            unlink($imagePath);

            $imageName = basename($imagePath);

            $res = $client->request( 'POST',$url,
                    ['multipart' => [
                        [
                            'name'      => 'image',
                            'contents'  => $fileContent,
                            'filename'  => $imageName
                        ],
                    ]
                ]
            );

            if($res->getStatusCode() != 200) {
                $message = sprintf('Error %s while receiving data from the API service', $res->getStatusCode());
                throw new \Exception($message);

            } else {
                $response = json_decode($res->getBody());
                if (!empty($response)) {
                    return array('status' => 'success', 'result' => $response);
                } else {
                    $message = sprintf('I can not process this image. Try uploading another image!');
                    throw new \Exception($message);
                }
            }
        } catch (\Exception $e) {
            // Логируем ошибки
            $message = $e->getMessage();
            \Log::error($message);
            return array('status'=>'error', 'error'=>$message);
        }

    }
}
