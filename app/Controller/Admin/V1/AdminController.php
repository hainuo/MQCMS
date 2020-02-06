<?php
declare(strict_types=1);

namespace App\Controller\Admin\V1;

use App\Logic\Admin\AdminLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\AuthMiddleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @Controller()
 * @Middleware(AuthMiddleware::class)
 * Class AdminController
 * @package App\Controller\Admin\V1
 */
class AdminController extends BaseController
{
    /**
     * @Inject()
     * @var AdminLogic
     */
    public $logic;

    /**
     * @RequestMapping(path="update", methods="post")
     * @param RequestInterface $request
     * @return int
     */
    public function update(RequestInterface $request)
    {
        $this->validateParam($request, [
            'account' => 'required',
            'real_name' => 'required',
            'phone' => 'required',
        ]);
        return $this->logic->update($request);
    }

    /**
     * @RequestMapping(path="store", methods="post")
     * @param RequestInterface $request
     * @return int
     */
    public function store(RequestInterface $request)
    {
        $this->validateParam($request, [
            'account' => 'required',
            'real_name' => 'required',
            'phone' => 'required',
            'password' => 'required'
        ]);
        return $this->logic->store($request);
    }

    /**
     * @RequestMapping(path="module", methods="get")
     * @param RequestInterface $request
     * @return int
     */
    public function module(RequestInterface $request)
    {
        $this->validateParam($request, [
           'module' => 'required'
        ]);
        return $this->logic->module($request);
    }
}