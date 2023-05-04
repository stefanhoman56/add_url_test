<?php

namespace App\Controller;

use App\Entity\Url;
use App\Form\UrlType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UrlRepository;

class UrlController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, UrlRepository $urlRepo): Response
    {
        $url = new Url();
        $form = $this->createForm(UrlType::class, $url);
        $message = '';
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existingUrl = $urlRepo->findOneByUrl($url); // Checking for existing url
            if (!$existingUrl) {
                $urlRepo->save($url, true);
                return $this->redirectToRoute('index');
            } else {
                $message = 'The URL already exists! Please enter a different URL.';
            }
        }

        return $this->render('url/index.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
}
