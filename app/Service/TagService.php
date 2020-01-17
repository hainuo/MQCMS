<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\BusinessException;
use App\Model\Entity\Tag;
use App\Utils\Common;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

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
     * @Inject()
     * @var PostService
     */
    public $postService;

    /**
     * 标签列表
     * @param int $page
     * @param int $limit
     * @param array $search
     * @return \Hyperf\Contract\PaginatorInterface|mixed
     */
    public function index(int $page = 1, int $limit = 10, $search = [])
    {
        $this->condition = ['status' => 1];
        $this->orderBy = 'is_hot DESC, id DESC';
        return parent::index($page, $limit, $search);
    }

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

    /**
     * 标签下帖子列表
     * @param RequestInterface $request
     * @return mixed
     */
    public function postList($id, $post=[])
    {
        try {
            $this->with = [
                'postIds'
            ];
            $this->condition = ['id' => $id];
            $tagInfo = parent::show();
            $postIds = $tagInfo['post_ids'];
            print_r($postIds);
            // $this->tagPostRelationService->condition = ['tag_id' => $id];
            // $postIds = $this->tagPostRelationService->multiTableJoinQueryBuilder()->pluck('post_id')->toArray();
            //
            // $this->postService->condition = [
            //     ['status', '=', 1],
            //     ['is_publish', '=', 1],
            // ];
            // //推荐的帖子
            // if ($post['type'] == 2) {
            //     $this->postService->orderBy = 'is_recommend DESC, id DESC';
            // }
            // $query = $this->postService->multiTableJoinQueryBuilder()->whereIn('id', $postIds);
            // $count = $query->count();
            // $pagination = $query->paginate((int)$limit, $this->select, 'page', (int)$page)->toArray();
            // $pagination['data'] = Common::calculateList($page, $limit, $pagination['data']);
            //
            // foreach ($pagination['data'] as $key => &$value) {
            //     $value['attach_urls'] = $value['attach_urls'] ? json_decode($value['attach_urls'], true) : [];
            //     $value['relation_tags_list'] = explode(',', $value['relation_tags']);
            // }
            // $pagination['total'] = $count;
            // return $pagination;

        } catch (\Exception $e) {
            throw new BusinessException((int)$e->getCode(), $e->getMessage());
        }
    }
}