<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Model\Entity\Admin;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;

class AdminService extends BaseService
{
    /**
     * @Inject()
     * @var Admin
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
        $data = parent::index($page, $limit, $search);

        foreach ($data['data'] as $key => &$value) {
            $value['register_time'] = $value['register_time'] ? date('Y-m-d H:i:s', (int)$value['register_time']) : '';
            $value['login_time'] = $value['login_time'] ? date('Y-m-d H:i:s', (int)$value['login_time']) : '';
        }
        return $data;
    }
}