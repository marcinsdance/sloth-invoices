<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class RequestListener
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
        $invoiceId = $clientId = $profileId = $itemId = '';
        $route = $this->requestStack->getCurrentRequest()
            ->getPathInfo();
        $routeName = $this->requestStack->getCurrentRequest()
            ->get('_route');
        if (strpos($route, '/invoice/') !== false) {
            $invoiceId = explode('/',$route);
            $invoiceId = $invoiceId[2];
        } else if (strpos($route, '/client/') !== false) {
            $clientId = explode('/',$route);
            $clientId = $clientId[2];
        } else if (strpos($route, '/profile/') !== false) {
            $profileId = explode('/',$route);
            $profileId = $profileId[2];
        }

        if ($route === 'preview' || $route === '/' || $route === 'pd') {
            return;
        }
        $breadcrumbs = $this->whiteOctoberBreadcrumbsBundle;
        $breadcrumbs->addItem("Home", $this->router->generate("home"));
        if ( $routeName === 'new-client' ||  $routeName === 'edit-client') {
            $breadcrumbs->addItem("Clients", $this->router->generate("clients"));
        } else if ($routeName === 'new-invoice' || $routeName === 'email') {
            $breadcrumbs->addItem("Invoices", $this->router->generate("invoices"));
        } else if ($routeName === 'edit-invoice' ||  $routeName === 'invoice') {
            $breadcrumbs->addItem("Invoice", $this->router->generate("invoice", array('invoiceId' => $invoiceId)));
        } else if ($routeName === 'new-profile' ||  $routeName === 'edit-profile' ||  $routeName === 'delete-profile') {
            $breadcrumbs->addItem("Profiles", $this->router->generate("profiles"));
        } else if ($routeName === 'new-item' ||  $routeName === 'edit-item') {
            $breadcrumbs->addItem("Invoice", $this->router->generate("invoice", array('invoiceId' => $invoiceId)));
        } else if (
            $routeName === 'fos_user_security_login' ||
            $routeName === 'fos_user_security_check' ||
            $routeName === 'fos_user_security_logout' ||
            $routeName === 'fos_user_profile_edit' ||
            $routeName === 'fos_user_registration_register' ||
            $routeName === 'fos_user_registration_check_email' ||
            $routeName === 'fos_user_registration_confirm' ||
            $routeName === 'fos_user_registration_confirmed' ||
            $routeName === 'fos_user_resetting_request' ||
            $routeName === 'fos_user_resetting_send_email' ||
            $routeName === 'fos_user_resetting_check_email' ||
            $routeName === 'fos_user_resetting_reset' ||
            $routeName === 'fos_user_change_password'
        ) {
            $breadcrumbs->addItem("Profile", $this->router->generate("fos_user_profile_show"));
        }
        $breadcrumbsEnd = '';
        switch($routeName) {
            case 'invoice' :
                $breadcrumbsEnd = 'Items';
                break;
            case 'invoices' :
                $breadcrumbsEnd = 'Invoices';
                break;
            case 'clients' :
                $breadcrumbsEnd = 'Clients';
                break;
            case 'profiles' :
                $breadcrumbsEnd = 'Profiles';
                break;
            case 'new-invoice' :
                $breadcrumbsEnd = 'New Invoice';
                break;
            case 'edit-invoice' :
                $breadcrumbsEnd = 'Edit Invoice';
                break;
            case 'delete-invoice' :
                $breadcrumbsEnd = 'Delete Invoice';
                break;
            case 'new-client' :
                $breadcrumbsEnd = 'New Client';
                break;
            case 'edit-client' :
                $breadcrumbsEnd = 'Edit Client';
                break;
            case 'delete-client' :
                $breadcrumbsEnd = 'Delete Client';
                break;
            case 'new-profile' :
                $breadcrumbsEnd = 'New Profile';
                break;
            case 'edit-profile' :
                $breadcrumbsEnd = 'Edit Profile';
                break;
            case 'delete-profile' :
                $breadcrumbsEnd = 'Delete Profile';
                break;
            case 'new-item' :
                $breadcrumbsEnd = 'New Item';
                break;
            case 'edit-item' :
                $breadcrumbsEnd = 'Edit Item';
                break;
            case 'delete-item' :
                $breadcrumbsEnd = 'Delete Item';
                break;
            case 'email' :
                $breadcrumbsEnd = 'Email';
                break;
            case 'fos_user_profile_show' :
                $breadcrumbsEnd = 'Profile';
                break;
            case 'fos_user_security_login' :
                $breadcrumbsEnd = 'Login';
                break;
            case 'fos_user_profile_edit' :
                $breadcrumbsEnd = 'Edit';
                break;
            case 'fos_user_registration_register' :
                $breadcrumbsEnd = 'Register';
                break;
            case 'fos_user_resetting_reset' :
                $breadcrumbsEnd = 'Reset';
                break;
            case 'fos_user_change_password' :
                $breadcrumbsEnd = 'Change Password';
                break;
        }
        if ($breadcrumbsEnd !== '') {
            $breadcrumbs->addItem($breadcrumbsEnd);
        }
    }
}