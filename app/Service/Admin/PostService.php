<?php
declare(strict_types=1);

namespace App\Service\Admin;

class PostService extends \App\Service\Common\PostService
{
    /**
     * @param int $page
     * @param int $limit
     * @param array $search
     * @return \Hyperf\Contract\PaginatorInterface|mixed
     */
    public function index(int $page = 1, int $limit = 10, $search = [])
    {
        $this->with = [
            'adminInfo' => ['id', 'uuid', 'account'],
            'cateInfo' => ['id', 'cate_name']
        ];
        $this->condition = ['status' => 1];
        return parent::index($page, $limit, $search);
    }
}