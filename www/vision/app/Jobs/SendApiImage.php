<?php

namespace App\Jobs;

use App\Facades\ApiClient;
use App\Http\oVision\PredictApiClient;
use App\Http\Services\ApiClient\ApiClientService;
use App\Face;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendApiImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;  //число попыток

    private $pathImage;
    private $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pathImage)
    {
        $this->pathImage = $pathImage;
        $this->token = md5($this->pathImage);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $token = md5($this->pathImage);
            $result = json_encode(ApiClientService::post(new PredictApiClient('/predict', $this->pathImage)));
            $process = Face::where('token', $this->token)->first();
            if (!empty($process)) {
                $process->setResult($result);
                $process->setCompleted(true);
                $process->save();
                //Удаляем использованный файл
                //if (file_exists($this->pathImage))
                //    unlink($this->pathImage);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function failed(\Exception $exception)
    {
        $message = __CLASS__ . ": Ошибка выполнения";
        info($message);
        $process = Face::where('token', $this->token)->first();
        if (!empty($process)) {
            $process->setError($message);
            $process->save();
        }

        // Отправляем сообщение клиенту об ошибке
    }
}
