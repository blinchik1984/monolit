<?php


namespace Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MyController extends Controller
{
    public function __construct()
    {
        $this->setContainer(\AdServer\Engine\Engine::getContainer());
    }

    public function foo(Request $request)
    {
        return $this->get('TargetService')->getTarget();
    }

    public function foobar()
    {
        return new Response('fooobale');
    }

    public function index(): Response
    {
        return $this->get('targetClientApi')->request(Request::create('/target/foo'));
    }
}