<?php
namespace udokmeci\yii2techfilestorage\storage;

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use yii\base\InvalidConfigException;
use yii2tech\filestorage\BaseStorage;

class Minio extends \yii2tech\filestorage\amazon\Storage
{
    public $enableV4=false;
    private $_amazonS3;
    public $endpoint='http://0.0.0.0:9000';
    public $bucketClassName = 'udokmeci\yii2techfilestorage\storage\Bucket';
    public $use_path_style_endpoint = false;


    /**
       * @var string Amazon Bucket Region
       */
    public $region = 'eu-west-1';

    /**
     * @var string Amazon Version
     */
    public $version='latest';


    /**
      * @return S3Client Amazon S3 client instance.
      */
    public function getAmazonS3()
    {
        if (!is_object($this->_amazonS3)) {
            $this->_amazonS3 = $this->createMinio();
        }
        return $this->_amazonS3;
    }

    /**
     * Initializes the instance of the Amazon S3 service gateway.
     * @return S3Client Amazon S3 client instance.
     */
    protected function createMinio()
    {
        $credentials = new Credentials($this->awsKey, $this->awsSecretKey);
        
        $settings=[
            'version' =>  $this->version,
            'region'  => $this->region,
            'endpoint' => $this->endpoint,
            'use_path_style_endpoint' => $this->use_path_style_endpoint,
            'credentials' => [
                'key'    => $this->awsKey,
                'secret' => $this->awsSecretKey,
            ],
        ];
        if ($this->enableV4) {
            $settings['signature_version']='v4';
        }

        
        return new S3Client($settings);
    }
}
