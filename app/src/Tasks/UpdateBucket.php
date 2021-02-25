<?php
use SilverStripe\Dev\BuildTask;
use GuzzleHttp\Promise;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
use SilverStripe\Security\Security;
use SilverStripe\Core\Environment;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class UpdateBucket extends BuildTask
{

    protected $checkAdmin = true;

    public function run($request)
    {
        $logger = Injector::inst()->get(LoggerInterface::class);

        $client = new S3Client([
            'region'  => Environment::getEnv('AWS_REGION_NAME'),
            'version' => '2006-03-01',
        ]);

        $source = Environment::getEnv('STATIC_CONTENT');
        $bucket = Environment::getEnv('STATIC_BUCKET');
        $dest = sprintf("s3://%s/",$bucket);

        $manager = new \Aws\S3\Transfer($client, $source, $dest);
        $manager->transfer();
        $source = BASE_PATH.'/themes/';
        $destination = $dest.'themes';
        $manager = new \Aws\S3\Transfer($client,$source,$dest);
        $manager->transfer();
        $source = BASE_PATH.'/public/_resources/';
        $destination = $dest.'_resources';
        $manager = new \Aws\S3\Transfer($client,$source,$dest);
        $manager->transfer();
    }
}
