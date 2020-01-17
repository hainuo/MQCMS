<?php
declare(strict_types=1);

namespace App\Logic\Admin;

use App\Service\Admin\AuthService;
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
     * @return array
     * @throws \Exception
     */
    public function register(RequestInterface $request)
    {
        $account = $request->input('account');
        $phone = $request->input('phone');
        $password = $request->input('password');
        $ip = $request->getServerParams()['remote_addr'];

        list($lastInsertId, $uuid) = $this->service->register($account, $phone, $password, $ip);

        $token = $this->createAuthToken(['id' => $lastInsertId, 'uuid' => $uuid], $request);
        return [
            'token' => $token,
            'expire_time' => JWT::$leeway,
            'uuid' => $uuid,
            'info' => [
                'name' => $account,
                'avatar' => '',
                'access' => []
            ]
        ];
    }

    /**
     * @param RequestInterface $request
     * @return \Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|object|null
     */
    public function login(RequestInterface $request)
    {
        $account = trim($request->input('account'));
        $password = trim($request->input('password'));

        $adminInfo = $this->service->login($account, $password);

        $token = $this->createAuthToken(['id' => $adminInfo['id'], 'uuid' => $adminInfo['uuid']], $request);
        Redis::getContainer()->set('admin:token:' . $adminInfo['uuid'], $token);

        return [
            'token' => $token,
            'expire_time' => JWT::$leeway,
            'uuid' => $adminInfo['uuid'],
            'info' => [
                'name' => $account,
                'avatar' => $adminInfo['avatar'],
                'access' => []
            ]
        ];
    }
}