<?php
use SilverStripe\Dev\BuildTask;
use GuzzleHttp\Promise;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
use SilverStripe\Security\Security;
use SilverStripe\Core\Environment;

class UpdateBucket extends BuildTask
{

    protected $checkAdmin = true;

    public function run($request)
    {
        $logger = Injector::inst()->get(LoggerInterface::class);
        #$promises = [];
        $client = new \Aws\S3\S3Client([
            'region'  => 'us-west-2',
            'version' => '2006-03-01',
        ]);

        $source = sprintf("%s/%s/%s/",BASE_PATH,'assets','cache');
        $bucket = Environment::getEnv('STATIC_BUCKET');
        $dest = sprintf("s3://%s/",$bucket);

        $manager = new \Aws\S3\Transfer($client, $source, $dest);
        $promise = $manager->promise(); #start transfer
        $promise->then(function ($value) {
            echo 'Done! '.$value;
        });
        $promise->otherwise(function ($reason) use ($logger) {
            $logger->error(sprintf('failed to upload file %s',$reason));
        });
        #$metaPromise = \GuzzleHttp\Promise\all($promises)->wait();
    }
}
