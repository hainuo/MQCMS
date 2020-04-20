<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Logic\Frontend\HomeLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;

/**
 * @Controller()
 * Class HomeController
 * @package App\Controller\Frontend
 */
class HomeController extends BaseController
{
    /**
     * @Inject()
     * @var HomeLogic
     */
    public $logic;
}