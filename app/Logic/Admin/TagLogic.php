<?php
declare(strict_types=1);

namespace App\Logic\Admin;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Service\Admin\TagService;
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
     * @return array
     */
    public function show(RequestInterface $request): array
    {
        $uid = $request->getAttribute('uid', 0);
        $id = $request->input('id');
        return $this->service->getTagInfo($uid, $id);
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function update(RequestInterface $request): int
    {
        $this->service->condition = ['id' => $request->input('id')];
        $this->service->data = [
            'tag_name'      => trim($request->input('tag_name')),
            'tag_title'     => trim($request->input('tag_title')),
            'tag_desc'      => trim($request->input('tag_desc')),
            'tag_keyword'   => trim($request->input('tag_keyword')),
            'is_hot'        => $request->input('is_hot', 0),
            'status'        => $request->input('status', 0),
            'tag_type'      => $request->input('tag_type', 1),
        ];
        return $this->service->update();
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function store(RequestInterface $request): int
    {
        $tagName = trim($request->input('tag_name'));
        $this->service->select = ['id'];
        $this->service->condition = ['tag_name' => $tagName];
        $tagInfo = $this->service->show();
        if ($tagInfo) {
            throw new BusinessException(ErrorCode::BAD_REQUEST, '标签名已经存在');
        }

        $this->service->data = [
            'tag_name' => $tagName,
            'is_hot' => 0,
            'status' => 1,
            'first_create_user_id' => $request->getAttribute('uid'),
        ];
        return $this->service->store();
    }

}