<?php

declare(strict_types=1);

namespace App\Controller;

use App\Linker\SimpleLinker;
use GuzzleHttp\Exception\RequestException as InvalidURLException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimpleLinkerController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function indexAction()
    {
        return $this->render('base.html.twig', []);
    }

    /**
     * @Route("simple_linker/", name="simple_linker")
     * @param Request $request
     * @return JsonResponse|Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function status(Request $request)
    {
        $url = $request->get('link');
        if (!$url) {
            return $this->render('linker/simple.html.twig', []);
        }

        try {
            $simpleLinker = new SimpleLinker('GET', $url);
            $simpleLinker->request();

            if ($simpleLinker->isRedirect()) {
                return $this->json(' Redirect from ' .
                    $simpleLinker->getRedirectFrom() . ' to ' .
                    $simpleLinker->getRedirectTo() . ' with code ' .
                    $simpleLinker->getStatusCode(),  $simpleLinker->getStatusCode());
            }

            return $this->json($simpleLinker->getStatusCode());

        } catch (InvalidURLException $error) {
            return $this->json('Invalid URL address', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $error) {
            return $this->json('Error with code ' . $error->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
