<?php
declare(strict_types=1);

namespace App\Logic\Admin;

use App\Service\Admin\PostService;
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
     * @param RequestInterface $request
     * @return int
     * @throws \Exception
     */
    public function update(RequestInterface $request): int
    {
        $this->service->condition = ['id' => $request->input('id')];
        $this->service->data = [
            'user_id'           => $request->getAttribute('uid'),
            'post_content'      => trim($request->input('post_content')),
            'link_url'          => trim($request->input('link_url')),
            'label_type'        => $request->input('label_type', 0),
            'is_good'           => $request->input('is_good', 0),
            'relation_tags'     => $request->input('relation_tags', ''),
            'address'           => $request->input('address', ''),
            'addr_lat'          => $request->input('addr_lat', ''),
            'addr_lng'          => $request->input('addr_lng', ''),
            'attach_urls'       => $request->input('attach_urls', ''),
            'is_publish'        => $request->input('is_publish', 0),
            'status'            => $request->input('status', 0),
            'is_recommand'      => $request->input('is_recommand', 0),
            'like_total'        => $request->input('like_total', 0),
            'favorite_total'    => $request->input('favorite_total', 0),
            'comment_total'     => $request->input('comment_total', 0),
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
            'user_id'           => $request->getAttribute('uid'),
            'post_content'      => trim($request->input('post_content')),
            'link_url'          => trim($request->input('link_url')),
            'label_type'        => $request->input('label_type', 0),
            'is_good'           => $request->input('is_good', 0),
            'relation_tags'     => $request->input('relation_tags', ''),
            'address'           => $request->input('address', ''),
            'addr_lat'          => $request->input('addr_lat', ''),
            'addr_lng'          => $request->input('addr_lng', ''),
            'attach_urls'       => $request->input('attach_urls', ''),
            'is_publish'        => $request->input('is_publish', 0),
            'status'            => $request->input('status', 0),
            'is_recommand'      => $request->input('is_recommand', 0),
            'like_total'        => $request->input('like_total', 0),
            'favorite_total'    => $request->input('favorite_total', 0),
            'comment_total'     => $request->input('comment_total', 0),
        ];
        return $this->service->store();
    }
}