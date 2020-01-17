<?php
declare(strict_types=1);

namespace App\Logic\Api;

use App\Logic\AbstractLogic;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class BaseLogic extends AbstractLogic
{
    /**
     * @Inject()
     * @var BaseService
     */
    public $service;

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function index(RequestInterface $request): array
    {
        $page = intval($request->input('page', 1));
        $limit = intval($request->input('limit', 10));
        $page < 1 && $page = 1;
        $limit > 100 && $limit = 100;
        $searchForm = $request->has('search') ? $request->input('search') : [];

        return $this->service->index($page, $limit, $searchForm);
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function store(RequestInterface $request): int
    {
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function show(RequestInterface $request): array
    {
        $this->service->condition = ['id' => $request->input('id')];
        return $this->service->show();
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function delete(RequestInterface $request): int
    {
        $this->service->condition = ['id' => $request->input('id')];
        return $this->service->delete();
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    public function update(RequestInterface $request): int
    {
    }
}