<?php
declare(strict_types=1);

/**
 * auth控制器
 */
namespace App\Controller\Api\V1;

use App\Logic\Api\AuthLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @Controller
 * Class AuthController
 * @package App\Controller\Api\V1
 */
class AuthController extends BaseController
{
    /**
     * @Inject()
     * @var AuthLogic
     */
    public $logic;

    /**
     * 注册
     * @param RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function register(RequestInterface $request)
    {
        $this->validateParam($request, [
            'user_name' => 'required',
            'password' => 'required|max:100|min:6'
        ]);

        return $this->logic->register($request);
    }

    /**
     * 账号密码登录
     * @param RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(RequestInterface $request)
    {
        $this->validateParam($request, [
            'user_name' => 'required',
            'password' => 'required|max:100|min:6'
        ]);

        return $this->logic->login($request);
    }

    /**
     * 小程序登录
     * @param RequestInterface $request
     * @return \Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|object|null
     */
    public function miniProgram(RequestInterface $request)
    {
        $this->validateParam($request, [
            'code' => 'required'
        ]);
        return $this->logic->miniProgram($request);
    }
}