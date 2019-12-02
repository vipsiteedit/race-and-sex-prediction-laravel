<?php

namespace App\Http\Controllers;

use App\Face;
use Illuminate\Http\Request;
use App\Jobs\SendApiImage;


class ImageController extends Controller
{
    const MAX_IMAGE_SIZE = 2048000;

    /**
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     */
    public function upload(Request $request)
    {
        try {
            $path = $request->file('image')->store('uploads', 'not_public');
            // отправляем имя файла в API
            $pathImage = storage_path() . '/app/not_public/' . $path;

            // Создаем запись в таблице процессов и получаеи ID для дальнейшей идентификации и получения результата
            $token = md5($pathImage);
            $process =  (new Face);
            $process->name = 'test';
            $process->photo = basename($pathImage);
            $process->token = $token;
            $process->save();
            if ($process->id && file_exists($pathImage)) {
                // Добавляем обработчик в очередь
                SendApiImage::dispatch($pathImage);
                return json_encode(['status' => 'success', 'result' => $token]);
            } else {
                return json_encode(['status' => 'error', 'error' => 'Что-то пошло не так, не сформирован ']);
            }

        } catch(\Exception $e) {
            info($e->getMessage());
            return json_encode(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @param $idProcess
     * @return false|string
     */
    public function checkProcess($token)
    {
        $process = JobsProcess::where('token', $token)->first();
        if (empty($process)) {
            $massage  = sprintf('Не найден процесс "%s" для работы с API', $idProcess);
            //throw new \Exception($massage);
            info($massage);
            return json_encode(['status' => 'error', 'error' => $massage], JSON_UNESCAPED_UNICODE);
        } else {
            if ($process->getCompleted()) {
                return json_encode(['status'=>'success', 'result'=>$process->getResult()], JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(['status'=>'process']);
            }
        }
    }
}
