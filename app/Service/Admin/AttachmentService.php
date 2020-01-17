<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Entity\Attachment;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;

class AttachmentService extends BaseService
{
    /**
     * @Inject()
     * @var Attachment
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
            $value['attach_full_url'] = env('APP_UPLOAD_HOST_URL', '') . $value['attach_url'];
        }
        return $data;
    }

    /**
     * @return array|int
     */
    public function store()
    {
        $data = $this->data;
        $res = parent::store();
        if (!$res) {
            throw new BusinessException(ErrorCode::BAD_REQUEST, '上传失败');
        }
        $data['attach_full_url'] = env('APP_UPLOAD_HOST_URL', '') . $data['attach_url'];
        return $data;
    }
}