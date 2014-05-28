<?php namespace KevBaldwyn\Image\SaveHandlers;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Guzzle\Http\EntityBody;

class AmazonS3 implements SaveHandlerInterface {

	private $client;
	private $bucket;
	private $basePath;


	public function __construct(S3Client $client, $bucket, $basePath)
	{
		$this->client = $client;
		$this->client->registerStreamWrapper();

		$this->bucket   = $bucket;
		$this->basePath = trim($basePath, '/');
	}


	public function getPublicPath()
	{
		return 'https://' . $this->bucket . '.s3.amazonaws.com/' . $this->basePath;
	}


	public function exists($filename)
	{
		$filePath = 's3://' . $this->bucket . '/'. $this->basePath . '/' . $filename;
		return file_exists($filePath);
	}


	public function save($filename, array $data)
	{
		$this->client->putObject(array(
		    'Bucket' => $this->bucket,
		    'ACL'    => 'public-read',
		    'Key'    => $this->basePath . '/' . $filename,
		    'Body'   => EntityBody::factory($data['data'])
		));
	}

}