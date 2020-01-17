<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Entity\User;
use App\Service\BaseService;
use App\Service\UserInfoService;
use App\Utils\Common;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class UserService extends BaseService
{
    /**
     * @Inject()
     * @var User
     */
    public $model;

    /**
     * @Inject()
     * @var UserInfoService
     */
    public $userInfoService;

    /**
     * @param int $page
     * @param int $limit
     * @param array $search
     * @return \Hyperf\Contract\PaginatorInterface|mixed
     */
    public function index(int $page = 1, int $limit = 10, $search = [])
    {
        $this->with = [
            'userInfo' => ['user_id', 'intro', 'like_num', 'follow_num', 'fans_num', 'post_num', 'my_like_num']
        ];
        return parent::index($page, $limit, $search);
    }

    /**
     * @param $post
     * @return bool|int
     * @throws \Exception
     */
    public function createUserInfo($post)
    {
        $this->select = ['id'];
        $this->condition = [['user_name', '=', $post['userName']]];
        $userInfo = parent::show();
        if ($userInfo) {
            throw new BusinessException(ErrorCode::BAD_REQUEST, '用户名已经存在');
        }

        $salt = Common::generateSalt();
        $this->data = [
            'uuid'          => Common::generateSnowId(),
            'user_name'     => $post['userName'],
            'real_name'     => $post['realName'],
            'nick_name'     => $post['userName'] . generate_random_string(6),
            'phone'         => $post['phone'],
            'avatar'        => '',
            'password'      => Common::generatePasswordHash($post['phone'], $salt),
            'salt'          => $salt,
            'status'        => $post['status'],
            'register_time' => time(),
            'register_ip'   => $post['ip'],
            'login_time'    => time(),
            'login_ip'      => $post['ip'],
            'created_at'    => time(),
            'updated_at'    => time()
        ];
        Db::beginTransaction();
        try{
            $lastInsertId = parent::insert();
             $this->userInfoService->data = [
                'user_id' => $lastInsertId
            ];
            $this->userInfoService->store();
            Db::commit();
            return $lastInsertId;

        } catch(\Exception $e) {
            Db::rollBack();
            throw new BusinessException((int)$e->getCode(), '添加失败');
        }
    }

    public function getPostList($uid)
    {
        $this->with = ['postList'];
        $this->condition = ['id' => $uid];
        return parent::index();
    }
}