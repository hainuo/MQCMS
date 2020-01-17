<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Logic\Api\PostLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * Class PostController
 * @package App\Controller\Api\V1
 */
class PostController extends BaseController
{
    /**
     * @Inject()
     * @var PostLogic
     */
    public $logic;

    /**
     * 帖子列表分页
     * @param RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RequestInterface $request)
    {
        $this->validateParam($request, [
            'type' => 'nullable|string',
        ]);

        return $this->logic->index($request);
    }

    /**
     * 新增
     * @param RequestInterface $request
     * @return mixed
     */
    public function store(RequestInterface $request)
    {
        $this->validateParam($request, [
            'post_content' => 'required',
            'label_type' => 'required',
            'address' => 'required',
            'addr_lat' => 'required',
            'addr_lng' => 'required',
            'attach_urls' => 'required',
            'attach_ids' => 'required',
            'is_publish' => 'required|integer',
        ]);
        return $this->logic->store($request);
    }

    /**
     * 点赞帖子
     * @param RequestInterface $request
     * @return mixed
     */
    public function like(RequestInterface $request)
    {
        $this->validateParam($request, [
            'id' => 'required|integer'
        ]);

        return $this->logic->like($request);
    }

    /**
     * 取消点赞帖子
     * @param RequestInterface $request
     * @return mixed
     */
    public function cancelLike(RequestInterface $request)
    {
        $this->validateParam($request, [
            'id' => 'required|integer'
        ]);

        return $this->logic->cancelLike($request);
    }

    /**
     * 收藏帖子
     * @param RequestInterface $request
     * @return mixed
     */
    public function favorite(RequestInterface $request)
    {
        $this->validateParam($request, [
            'id' => 'required|integer'
        ]);

        return $this->logic->favorite($request);
    }

    /**
     * 取消收藏帖子
     * @param RequestInterface $request
     * @return mixed
     */
    public function cancelFavorite(RequestInterface $request)
    {
        $this->validateParam($request, [
            'id' => 'required|integer'
        ]);

        return $this->logic->cancelFavorite($request);
    }
}