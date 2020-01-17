<?php
declare(strict_types=1);

namespace App\Logic\Api;

use App\Service\TagService;
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
     * @param RequestInterface $request
     * @return int
     */
    public function update(RequestInterface $request): int
    {
        $this->service->condition = ['id' => $request->input('id')];
        $this->service->data = [
            'tag_name'    => trim($request->input('tag_name')),
            'tag_title'   => trim($request->input('tag_title')),
            'tag_desc'    => trim($request->input('tag_desc')),
            'tag_keyword' => trim($request->input('tag_keyword')),
            'is_hot'      => $request->input('is_hot', 0),
            'status'      => $request->input('status', 0),
            'tag_type'    => $request->input('tag_type', 1),
        ];
        return $this->service->update();
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function store(RequestInterface $request): int
    {
        $this->service->data = [
            'tag_name'              => trim($request->input('tag_name')),
            'tag_title'             => trim($request->input('tag_title')),
            'tag_desc'              => trim($request->input('tag_desc')),
            'tag_keyword'           => trim($request->input('tag_keyword')),
            'is_hot'                => $request->input('is_hot', 0),
            'status'                => $request->input('status', 0),
            'first_create_user_id'  => $request->getAttribute('uid'),
            'tag_type'              => 1,
        ];
        return $this->service->store();
    }

    /**
     * 标签下帖子列表
     * @param RequestInterface $request
     * @return mixed
     */
    public function postList(RequestInterface $request)
    {
        $id = $request->input('id');
        $type = $request->input('type', 1);
        $post = compact('type');
        return $this->service->postList($id, $post);
    }
}