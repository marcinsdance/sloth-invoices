<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Profile;

class VoicenProfileController extends Controller
{
    /**
     * @Route("/profile/edit/{profileId}", name="edit-profile")
     * @Security("has_role('ROLE_USER')")
     */
    public function itemProfileAction(Request $request, $profileId)
    {
        $em = $this->getDoctrine()->getManager();
        $profile = $em->getRepository('AppBundle:Profile')->find($profileId);
        if (! $profile) {
            throw $this->createNotFoundException('No profile found for id ' . $profileId);
        }
        $form = $this->createForm($this->get('form_profile_type'), $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();
            $this->addFlash(
                'success',
                'Profile has been changed.'
            );
        }

        return $this->render('default/edit-profile.html.twig', array(
            'form' => $form->createView(),
            'profile' => $profile
        ));
    }

    /**
     * @Route("/profile/delete/{id}", name="delete-profile")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteProfileAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $profile = $em->getRepository('AppBundle:Profile')->find($id);
        if (! $profile) {
            throw $this->createNotFoundException('No profile found for id ' . $id);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($profile);
        $em->flush();
        $this->addFlash(
            'success',
            'Profile has been Removed.'
        );

        return $this->redirectToRoute('profiles');
    }

    /**
     * @Route("/profiles", name="profiles")
     * @Security("has_role('ROLE_USER')")
     */
    public function profilesAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $profiles = $this->getDoctrine()
            ->getRepository('AppBundle:Profile')
            ->findBy(
                array('user' => $user)
            );

        return $this->render('default/profiles.html.twig', array(
            'profiles' => $profiles
        ));
    }

    /**
     * @Route("/profile/new", name="new-profile")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @return Response
     */
    public function newProfileAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $profile = new Profile();
        $profile->setUser($user->getId());
        $form = $this->createForm($this->get('form_profile_type'), $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();
            $this->addFlash('success', 'Profile has been added.');
            return $this->redirectToRoute('profiles');
        }

        return $this->render('default/new-profile.html.twig', array(
            'form' => $form->createView()
        ));
    }

}