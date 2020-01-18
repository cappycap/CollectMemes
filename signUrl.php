<?php
header('Content-Type: application/json');

require 'aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3Client = new S3Client([
    'version'     => 'latest',
    'region'      => 'us-east-2',
    'credentials' => [
        'key'    => 'AKIAI22RCWUQ5PUBB5WQ',
        'secret' => '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',
    ],
]);

//Creating a presigned URL
$cmd = $s3Client->getCommand('PutObject', [
    'Bucket' => $_GET['bucket'],
    'Key' => $_GET['path']
]);

$request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

// Get the actual presigned-url
$presignedUrl = (string)$request->getUri();

echo json_encode(array("\$jason"=>$presignedUrl), JSON_UNESCAPED_SLASHES);
 ?>
