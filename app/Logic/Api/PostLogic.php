<?php
declare(strict_types=1);

namespace App\Logic\Api;

use App\Service\PostService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class PostLogic extends BaseLogic
{
    /**
     * @Inject()
     * @var PostService
     */
    public $service;

    /**
     * 获取帖子列表
     * @param RequestInterface $request
     * @return array
     */
    public function index(RequestInterface $request): array
    {
        // 类型： recommend: 推荐 default: 默认
        $type = trim($request->input('type', 'default'));
        $page = intval($request->input('page', 1));
        $limit = intval($request->input('limit', 10));
        $page < 1 && $page = 1;
        $limit > 100 && $limit = 100;
        $searchForm = $request->has('search') ? $request->input('search') : [];
        $post = compact('type');

        return $this->service->getList($page, $limit, $searchForm, $post);
    }

    /**
     * 更新帖子
     * @param RequestInterface $request
     * @return int
     * @throws \Exception
     */
    public function update(RequestInterface $request): int
    {
        $this->service->condition = ['id' => $request->input('id')];
        $this->service->data = [
            'user_id'        => $request->getAttribute('uid'),
            'post_content'   => trim($request->input('post_content')),
            'link_url'       => trim($request->input('link_url')),
            'label_type'     => $request->input('label_type', 0),
            'is_good'        => $request->input('is_good', 0),
            'relation_tags'  => $request->input('relation_tags', ''),
            'address'        => $request->input('address', ''),
            'addr_lat'       => $request->input('addr_lat', ''),
            'addr_lng'       => $request->input('addr_lng', ''),
            'attach_urls'    => $request->input('attach_urls', ''),
            'is_publish'     => $request->input('is_publish', 0),
            'status'         => $request->input('status', 0),
            'is_recommand'   => $request->input('is_recommand', 0),
            'like_total'     => $request->input('like_total', 0),
            'favorite_total' => $request->input('favorite_total', 0),
            'comment_total'  => $request->input('comment_total', 0),
        ];
        return $this->service->update();
    }

    /**
     * 发帖
     * @param RequestInterface $request
     * @return int
     */
    public function store(RequestInterface $request): int
    {
        $relationTagIds = explode(',', $request->input('relation_tag_ids', ''));
        $uid = $request->getAttribute('uid');
        $isPublish = $request->input('is_publish', 1);
        $data = [
            'user_id'       => $uid,
            'post_content'  => trim($request->input('post_content')),
            'link_url'      => trim($request->input('link_url', '')),
            'label_type'    => trim($request->input('label_type', 1)),
            'is_good'       => $request->has('link_url') ? 1 : 0,
            'relation_tags' => trim($request->input('relation_tags', '')),
            'address'       => trim($request->input('address', '')),
            'addr_lat'      => trim($request->input('addr_lat', '')),
            'addr_lng'      => trim($request->input('addr_lng', '')),
            'attach_urls'   => trim($request->input('attach_urls', '')),
            'attach_ids'    => trim($request->input('attach_ids', '')),
            'is_publish'    => $isPublish,
        ];
        $post = compact('relationTagIds', 'isPublish', 'uid');
        return $this->service->publish($post, $data);
    }

    /**
     * 点赞
     * @param RequestInterface $request
     * @return mixed
     */
    public function like(RequestInterface $request)
    {
        $uid = $request->getAttribute('uid');
        $id = $request->input('id');
        return $this->service->like($uid, $id);
    }

    /**
     * 取消点赞
     * @param RequestInterface $request
     * @return mixed
     */
    public function cancelLike(RequestInterface $request)
    {
        $uid = $request->getAttribute('uid');
        $id = $request->input('id');
        return $this->service->cancelLike($uid, $id);
    }

    /**
     *收藏次
     * @param RequestInterface $request
     * @return mixed
     */
    public function favorite(RequestInterface $request)
    {
        $uid = $request->getAttribute('uid');
        $id = $request->input('id');
        return $this->service->favorite($uid, $id);
    }

    /**
     * 取消收藏
     * @param RequestInterface $request
     * @return mixed
     */
    public function cancelFavorite(RequestInterface $request)
    {
        $uid = $request->getAttribute('uid');
        $id = $request->input('id');
        return $this->service->cancelFavorite($uid, $id);
    }
}