<?php
namespace App\Http\Controllers;

use Aws\S3\S3Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;

class UploadController extends Controller
{
    private S3Client $client;
    public function __construct()
    {
        $this->client = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'endpoint' => env('SPACES_ENDPOINT'),
            'use_path_style_endpoint' => false, 
            'credentials' => [
                'key'    => env('SPACES_KEY'),
                'secret' => env('SPACES_SECRET'),
            ],
        ]);        
    }
   
    public function uploadImage(ImageUploadRequest $request)
    {     
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();        

        // upload de arquivo
        $nameFile = date('Y-m-d_H-i-s') . '.' . $extension;        
        $result = $this->client->putObject([
            'Bucket' =>  env('SPACES_BUCKET'),
            'Key'    => $nameFile,
            'SourceFile' => $request->file('image')->path(),
            'ACL'    => 'public-read',
        ]);
        
        $ret = [
            'url' => $result->get('ObjectURL')
        ];
        
        return response()->json($ret, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);       
    }
}
