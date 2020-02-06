<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Entity\Admin;
use App\Model\Model;
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

    /**
     * @param $module
     * @return mixed
     */
    public function getModuleTbleList($module)
    {
        try {
            $moduleClass = 'App\\Model\\' . ucfirst($module);
            $reflectionClass = new \ReflectionClass($moduleClass);
            if (!$reflectionClass->isInstantiable()) {
                throw new BusinessException(ErrorCode::BAD_REQUEST, '当前类不可实例化');
            }
            $model = new $moduleClass();
            if (!($model instanceof Model)) {
                throw new BusinessException(ErrorCode::SERVER_ERROR);
            }
            return $model->getFillable();

        } catch (\Exception $e) {
            throw new BusinessException(ErrorCode::BAD_REQUEST, $e->getMessage());
        }
    }
}