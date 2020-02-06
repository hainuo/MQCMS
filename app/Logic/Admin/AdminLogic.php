<?php
declare(strict_types=1);

namespace App\Logic\Admin;

use App\Service\Admin\AdminService;
use App\Utils\Common;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class AdminLogic extends BaseLogic
{
    /**
     * @Inject()
     * @var AdminService
     */
    public $service;

    /**
     * @param RequestInterface $request
     * @return int
     * @throws \Exception
     */
    public function update(RequestInterface $request): int
    {
        $salt = Common::generateSalt();
        $this->service->condition = ['id' => $request->input('id')];

        $this->service->data = [
            'account'   => trim($request->input('account')),
            'real_name' => trim($request->input('real_name')),
            'phone'     => trim($request->input('phone')),
            'status'    => $request->input('status', 0),
            'salt'      => $salt,
            'password'  => Common::generatePasswordHash(trim($request->input('password')), $salt)
        ];
        return $this->service->update();
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function store(RequestInterface $request): int
    {
        $salt = Common::generateSalt();
        $ip = $request->getServerParams()['remote_addr'];
        $this->service->data = [
            'uuid'          => Common::generateSnowId(),
            'account'       => trim($request->input('account')),
            'real_name'     => trim($request->input('real_name')),
            'phone'         => trim($request->input('phone')),
            'status'        => $request->input('status', 0),
            'salt'          => $salt,
            'password'      => Common::generatePasswordHash(trim($request->input('password')), $salt),
            'login_ip'      => $request->getServerParams()['remote_addr'],
            'register_time' => time(),
            'register_ip'   => $ip,
            'login_time'    => time(),
            'login_ip'      => $ip,
        ];
        return $this->service->store();
    }

    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function module(RequestInterface $request)
    {
        $module = $request->input('module');
        return $this->service->getModuleTbleList($module);
    }
}