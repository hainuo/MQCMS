<?php
declare(strict_types=1);

namespace App\Utils;

use AlibabaCloud\Client\AlibabaCloud;

class Aliyun
{
    public $accessKeyId = 'LTAI4Fhm742Bnr7K8KUdE2Nb';

    public $accessKeySecret = 'xu1MrxlgdGDDE8zeo2e1zKZPhvVUWk';

    public function __construct()
    {
        $this->getClient();
    }

    public function getClient()
    {
        return AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId('cn-shanghai')
            ->asDefaultClient();
    }
}