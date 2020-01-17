<?php
declare(strict_types=1);

namespace App\Logic\Api;

use App\Service\UserService;
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
     * 用户信息（查看别人）
     * @param RequestInterface $request
     * @return array
     */
    public function show(RequestInterface $request): array
    {
        $uid = $request->getAttribute('uid');
        $id = $request->input('id');
        return $this->service->profile($uid, $id);
    }

    /**
     * 用户信息（查看自己）
     * @param RequestInterface $request
     * @return array
     */
    public function showSelf(RequestInterface $request): array
    {
        $uid = $request->getAttribute('uid');
        return $this->service->showSelf($uid);
    }

    /**
     * 用户帖子列表
     * @param RequestInterface $request
     * @return array
     */
    public function postList(RequestInterface $request): array
    {
        $id = $request->input('id');
        $type = $request->input('type', 1);
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $page < 1 && $page = 1;
        $limit > 100 && $limit = 100;
        $post = compact('id', 'type');

        return $this->service->postList($post, $page, $limit);
    }

    /**
     * 我的关注用户列表
     * @param RequestInterface $request
     * @return mixed
     */
    public function myFollowedUserList(RequestInterface $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $page < 1 && $page = 1;
        $limit > 100 && $limit = 100;
        $uid = $request->getAttribute('uid');

        return $this->service->myFollowedUserList($uid, $page, $limit);
    }

    /**
     * 我的关注标签列表
     * @param RequestInterface $request
     * @return mixed
     */
    public function myFollowedTagList(RequestInterface $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $page < 1 && $page = 1;
        $limit > 100 && $limit = 100;
        $uid = $request->getAttribute('uid');

        return $this->service->myFollowedTagList($uid, $page, $limit);
    }
}