<?php
declare(strict_types=1);

namespace App\Logic\Api;

use App\Service\AuthService;
use App\Utils\JWT;
use App\Utils\Redis;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class AuthLogic extends BaseLogic
{
    /**
     * @Inject()
     * @var AuthService
     */
    public $service;

    /**
     * @param RequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    public function register(RequestInterface $request)
    {
        $userName = trim($request->input('user_name'));
        $password = trim($request->input('password'));
        $ip = $request->getServerParams()['remote_addr'];

        $post = compact('userName', 'password', 'ip');

        list($lastInsertId, $uuid) = $this->service->register($post);
        $token = $this->createAuthToken(['id' => $lastInsertId, 'uuid' => $uuid], $request);
        return $this->response->json([
            'token' => $token,
            'expire_time' => JWT::$leeway,
            'uuid' => $uuid,
            'info' => [
                'name' => $userName,
                'avatar' => '',
                'access' => []
            ]
        ]);
    }

    /**
     * @param RequestInterface $request
     * @return \Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|object|null
     */
    public function login(RequestInterface $request)
    {
        $userName = trim($request->input('user_name'));
        $password = trim($request->input('password'));
        $ip = $request->getServerParams()['remote_addr'];

        $post = compact('userName', 'password', 'ip');

        $userInfo = $this->service->login($post);

        $token = $this->createAuthToken(['id' => $userInfo['id'], 'uuid' => $userInfo['uuid']], $request);
        Redis::getContainer()->set('api:token:' . $userInfo['uuid'], $token);

        return $this->response->json([
            'token' => $token,
            'expire_time' => JWT::$leeway,
            'uuid' => $userInfo['uuid'],
            'info' => [
                'name' => $userName,
                'avatar' => $userInfo['avatar'],
                'access' => []
            ]
        ]);
    }

    public function miniProgram(RequestInterface $request)
    {
        return 'miniProgram';
    }
}