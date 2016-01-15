<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Listener
{
    protected $requestStack;
    protected $whiteOctoberBreadcrumbsBundle;
    protected $router;

    /**
     * Listener constructor.
     * @param \Twig_Environment $twig
     * @param RequestStack $requestStack
     */
    public function __construct(
        RequestStack $requestStack,
        Breadcrumbs $whiteOctoberBreadcrumbsBundle,
        Router $router)
    {
        $this->requestStack = $requestStack;
        $this->whiteOctoberBreadcrumbsBundle = $whiteOctoberBreadcrumbsBundle;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $route = $this->requestStack->getCurrentRequest()
            ->getPathInfo();
        $breadcrumbs = $this->whiteOctoberBreadcrumbsBundle;
        $breadcrumbs->addItem("Home", $this->router->generate("home"));
        $route = ltrim($route,'/');
        $breadcrumbsEnd = str_replace('/',' » ',$route);
        $breadcrumbsEnd = ucwords($breadcrumbsEnd);
        $breadcrumbs->addItem($breadcrumbsEnd);
    }
}