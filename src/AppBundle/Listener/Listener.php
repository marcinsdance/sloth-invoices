<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Request;

class Listener
{
    protected $twig;
    protected $request;

    /**
     * Listener constructor.
     * @param \Twig_Environment $twig
     * @param Request $request
     */
    public function __construct(\Twig_Environment $twig, Request $request)
    {
        $this->twig = $twig;
        $this->request = $request;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $route = $this->request->getRequestUri();
        $this->twig->addGlobal('myvar', $route);
    }
}