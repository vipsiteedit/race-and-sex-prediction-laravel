<?php


namespace App\Http\oVision;


use App\Http\Services\ApiClient\ApiClientInterface;

class PredictApiClient extends ApiClient
{
    const MAX_IMAGE_SIZE = 2048000;
    private $imagePath = '';

    public function __construct($urlPath, $imagePath)
    {
        $this->imagePath = $imagePath;
        $this->uri = $urlPath;
    }

    public function option(): array
    {
        $fileSize = filesize($this->imagePath);
        if ($fileSize > self::MAX_IMAGE_SIZE) {
            $message = sprintf('Larger image file size %s kb!', round(self::MAX_IMAGE_SIZE / 1000));
            throw new \Exception($message);
        }
        $fileContent = \File::get($this->imagePath);
        $imageName = basename($this->imagePath);

        return ['multipart' => [
            [
                'name'      => 'image',
                'contents'  => $fileContent,
                'filename'  => $imageName
            ],
        ]];
    }

    public function status(int $statusCode)
    {
        if ($statusCode >= 300 || $statusCode < 200) {
            $message = sprintf('Error %s while receiving data from the API service', $statusCode);
            $this->apiException($message);
        }
    }
}
