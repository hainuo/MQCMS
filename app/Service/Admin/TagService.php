<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Exception\BusinessException;
use App\Model\Entity\Tag;
use App\Service\BaseService;
use App\Service\TagPostRelationService;
use App\Service\UserTagService;
use Hyperf\Di\Annotation\Inject;

class TagService extends BaseService
{
    /**
     * @Inject()
     * @var Tag
     */
    public $model;

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
     * 标签详情
     * @param $uid
     * @param $id
     * @return \Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|object|null
     */
    public function getTagInfo($uid, $id)
    {
        try {
            $this->select = ['id', 'tag_name', 'is_hot', 'tag_type', 'used_count'];
            $this->condition = [
                ['id', '=', $id],
                ['status', '=', 1],
            ];
            $data = parent::show();

            $data['is_follow'] = 0;
            if ($uid) {
                // 查询是否关注
                $this->userTagService->condition = [
                    ['user_id', '=', $uid],
                    ['tag_id', '=', $id]
                ];
                $exist = $this->userTagService->multiTableJoinQueryBuilder()->exists();
                if ($exist) {
                    $data['is_follow'] = 1;
                }
            }

            //标签下帖子数
            $this->tagPostRelationService->condition = ['tag_id' => $id];
            $postNum = $this->tagPostRelationService->multiTableJoinQueryBuilder()->count();
            $data['post_num'] = $postNum;

            //标签关注人数
            $this->userTagService->condition = ['tag_id' => $id];
            $followedNum = $this->userTagService->multiTableJoinQueryBuilder()->count();
            $data['followed_num'] = $followedNum;
            return $data;

        } catch (\Exception $e) {
            throw new BusinessException((int)$e->getCode(), $e->getMessage());
        }
    }

}