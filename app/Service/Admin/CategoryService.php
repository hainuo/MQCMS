<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Entity\Category;
use App\Utils\Common;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class CategoryService extends \App\Service\Common\CategoryService
{
    /**
     * @Inject()
     * @var Category
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
        // 搜索
        if (!empty($search)) {
            $this->multiSingleTableSearchCondition($search);
        }
        $query = $this->multiTableJoinQueryBuilder();
        $total = $query->count();
        $list = $query->get()->toArray();
        foreach ($list as $key => &$value) {
            $value['created_at'] = date('Y-m-d H:i:s', (int) $value['created_at']);
            $value['updated_at'] = date('Y-m-d H:i:s', (int) $value['updated_at']);
        }
        $pagination['total'] = $total;
        $pagination['data'] = Common::sonTree($list, 'cate_name');
        return $pagination;
    }

    public function save($data)
    {
        $id = isset($data['id']) ? $data['id'] : '';
        if ($id) {
            $this->service->condition = ['id' => $id];
            $menuInfo = $this->service->multiTableJoinQueryBuilder()->first();
            if (!$menuInfo) {
                $menuInfo = new $this->service->model;
            }
        } else {
            $this->service->condition = ['alias_name' => $data['alias_name']];
            $menuInfo = $this->service->multiTableJoinQueryBuilder()->first();
            if ($menuInfo) {
                throw new BusinessException(ErrorCode::BAD_REQUEST, '菜单别名不能重复');
            }
            $menuInfo = new $this->service->model;
        }
        $menuInfo->title         = $data['title'];
        $menuInfo->alias_title   = $data['alias_title'];
        $menuInfo->desc          = $data['desc'];
        $menuInfo->frontend_url  = isset($data['frontend_url']) ? $data['frontend_url'] : '';
        $menuInfo->backend_url   = $data['backend_url'];
        $menuInfo->custom        = $data['custom'];
        $menuInfo->parent_id     = $data['parent_id'];
        $menuInfo->menu_type     = $data['menu_type'];
        $menuInfo->status        = $data['status'];
        $menuInfo->header        = $data['header'];
        $menuInfo->sort_order    = $data['sort_order'];

        Db::beginTransaction();
        try {
            if (!$menuInfo->save()) {
                throw new BusinessException(ErrorCode::BAD_REQUEST, '保存失败：10000');
            }
            $id = $menuInfo->id;
            $this->service->condition = ['id' => $menuInfo->parent_id];
            $parentMenuInfo = $this->service->show();
            $path = $parentMenuInfo ? $parentMenuInfo['path'] . '-' : '';

            $menuInfo->path = $data['parent_id'] == 0 ? $id : $path . $id;
            if (!$menuInfo->save()) {
                throw new BusinessException(ErrorCode::BAD_REQUEST, '保存失败：10001');
            }
            Db::commit();
            return true;

        } catch (\Exception $e) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::BAD_REQUEST, $e->getMessage());
        }
    }
}