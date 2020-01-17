<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Model\Entity\Post;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;

class PostService extends BaseService
{
    /**
     * @Inject()
     * @var Post
     */
    public $model;

    /**
     * @param int $page
     * @param int $limit
     * @param array $search
     * @return \Hyperf\Contract\PaginatorInterface|mixed
     */
    public function index(int $page = 1, int $limit = 10, $search = [])
    {
        $this->with = ['userInfo' => ['id', 'uuid', 'user_name']];
        $this->condition = ['status' => 1];

        return parent::index($page, $limit, $search);
    }
}