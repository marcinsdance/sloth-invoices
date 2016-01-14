<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Listener
{
    protected $twig;
    protected $requestStack;
    protected $whiteOctoberBreadcrumbsBundle;
    protected $router;

    /**
     * Listener constructor.
     * @param \Twig_Environment $twig
     * @param RequestStack $requestStack
     */
    public function __construct(
        \Twig_Environment $twig,
        RequestStack $requestStack,
        Breadcrumbs $whiteOctoberBreadcrumbsBundle,
        Router $router)
    {
        $this->twig = $twig;
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
        $breadcrumbsEnd = str_replace('/','',$route);
        $breadcrumbsEnd = ucfirst($breadcrumbsEnd);
        $breadcrumbs->addItem($breadcrumbsEnd);

        $this->twig->addGlobal('myvar', $route);
    }
}