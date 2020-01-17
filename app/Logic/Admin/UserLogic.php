<?php
declare(strict_types=1);

namespace App\Logic\Admin;

use App\Service\Admin\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class UserLogic extends BaseLogic
{
    /**
     * @Inject()
     * @var UserService
     */
    public $service;

    /**
     * @param RequestInterface $request
     * @return int
     * @throws \Exception
     */
    public function store(RequestInterface $request): int
    {
        $userName = $request->input('user_name');
        $realName = $request->input('real_name');
        $phone = $request->input('phone');
        $status = $request->input('status', 1);
        $ip = $request->getServerParams()['remote_addr'];

        $post = compact('userName', 'realName', 'phone', 'status', 'ip');
        return $this->service->createUserInfo($post);
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function update(RequestInterface $request): int
    {
        $this->service->condition = ['id' => $request->input('id')];
        $this->service->data = [
            'user_name' => trim($request->input('user_name')),
            'real_name' => trim($request->input('real_name')),
            'nick_name' => trim($request->input('user_name')) . generate_random_string(6),
            'phone'     => trim($request->input('phone')),
            'status'    => $request->input('status', 1)
        ];
        return $this->service->update();
    }
}