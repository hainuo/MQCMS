<?php
declare(strict_types=1);

namespace App\Logic\Admin;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Service\Admin\TagService;
use App\Service\Common\TagPostRelationService;
use App\Service\Common\UserTagService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class TagLogic extends BaseLogic
{
    /**
     * @Inject()
     * @var TagService
     */
    public $service;

    /**
     * @Inject()
     * @var UserTagService
     */
    public $userTagService;

    /**
     * @Inject()
     * @var TagPostRelationService
     */
    public $tagPostRelationService;

    /**
     * @param RequestInterface $data
     * @return int
     */
    public function store($data): int
    {
        $tagName = trim($data['tag_name']);
        $this->service->select = ['id'];
        $this->service->condition = ['tag_name' => $tagName];
        $tagInfo = $this->service->show();
        if (!empty($tagInfo)) {
            throw new BusinessException(ErrorCode::BAD_REQUEST, '标签名已经存在');
        }

        $this->service->data = [
            'tag_name' => $tagName,
            'is_hot' => 0,
            'status' => 1,
            'first_create_user_id' => $data['uid'],
        ];
        return $this->service->store();
    }

    /**
     * @param RequestInterface $data
     * @return array
     */
    public function show($data): array
    {
        $this->service->select = ['id', 'tag_name', 'is_hot', 'tag_type', 'used_count'];
        $this->service->condition = [
            ['id', '=', $data['id']],
            ['status', '=', 1],
        ];
        $tagInfo = $this->service->show();
        if (empty($tagInfo)) {
            throw new BusinessException(ErrorCode::BAD_REQUEST, '标签不存在');
        }
        $tagInfo['is_follow'] = 0;
        if ($data['uid']) {
            // 查询是否关注
            $this->userTagService->condition = [
                ['user_id', '=', $data['uid']],
                ['tag_id', '=', $data['id']]
            ];
            $exist = $this->userTagService->multiTableJoinQueryBuilder()->exists();
            if ($exist) {
                $tagInfo['is_follow'] = 1;
            }
        }

        $condition = ['tag_id' => $data['id']];
        //标签下帖子数
        $this->tagPostRelationService->condition = $condition;
        $postNum = $this->tagPostRelationService->multiTableJoinQueryBuilder()->count();
        $tagInfo['post_num'] = $postNum;

        //标签关注人数
        $this->userTagService->condition = $condition;
        $followedNum = $this->userTagService->multiTableJoinQueryBuilder()->count();
        $tagInfo['followed_num'] = $followedNum;
        return $tagInfo;
    }

    /**
     * @param $data
     * @return int
     */
    public function update($data): int
    {
        $this->service->condition = ['id' => $data['id']];
        $this->service->data = [
            'tag_name'      => trim($data['tag_name']),
            'tag_title'     => trim($data['tag_title']),
            'tag_desc'      => trim($data['tag_desc']),
            'tag_keyword'   => trim($data['tag_keyword']),
            'is_hot'        => $data['is_hot'],
            'status'        => $data['status'],
            'tag_type'      => $data['tag_type'],
        ];
        return $this->service->update();
    }

}