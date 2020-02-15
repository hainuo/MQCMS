<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Utils\Common;
use App\Utils\Redis;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * Class TokenController
 * @package App\Controller\Api\V1
 */
class TokenController extends BaseController
{
    /**
     * 获取token信息
     * @return array|bool|object|string
     */
    public function index(RequestInterface $request)
    {
        return [
            'info' => $this->logic->getTokenInfo(),
            'token' => $this->logic->getAuthToken(),
            'uid' => $request->getAttribute('uid'),
            'uuid' => $request->getAttribute('uuid'),
            'current_action' => Common::getCurrentActionName($request, $this)
        ];
    }

    /**
     * 创建token
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function store(RequestInterface $request)
    {
        $token = $this->logic->createAuthToken([
            'id' => 1,
            'uuid' => 123,
            'name' => 'mqcms',
            'url' => 'http://www.mqcms.net',
            'from_module' => Common::getCurrentPath($request),
            'from_action' => Common::getCurrentActionName($request, $this)
        ], $request);

        Redis::getContainer()->set('api:token:123', $token);

        return [
            'token' => $token,
            'jwt_config' => $this->logic->getJwtConfig($request),
            'uid' => $request->getAttribute('uid'),
            'uuid' => $request->getAttribute('uuid'),
        ];
    }

}