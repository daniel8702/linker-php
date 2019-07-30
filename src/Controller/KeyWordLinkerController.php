<?php

declare(strict_types=1);

namespace App\Controller;

use App\Linker\AdvanceLinker;
use App\Linker\FilterLinker;
use App\Linker\SimpleLinker;
use GuzzleHttp\Exception\RequestException as InvalidURLException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KeyWordLinkerController extends AbstractController
{
    /**
     * @Route("key_word_linker/", name="key_word_linker")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function status(Request $request)
    {
        $url = $request->get('link');

        if (!$url) {
            return $this->render('linker/key_word.html.twig', []);
        }

        $urlArray = explode(",", $url);

        if (empty($urlArray[0]) || empty($urlArray[1]) || empty($urlArray[2])) {
            throw new \InvalidArgumentException('Invalid one or more arguments');
        }

        try {
            $simpleLinker = new SimpleLinker('GET', $urlArray[0]);
            $simpleLinker->request();
            $simpleLinker->getStatusCode();
            $filter = new FilterLinker('a', $urlArray[2], $urlArray[1]);
            $advanceLinker = new AdvanceLinker($simpleLinker, $filter);
            $advanceLinker->request();

            if (empty($response = $advanceLinker->filter())) {
                return $this->json('Not found!', Response::HTTP_NOT_FOUND);
            }
            return $this->json($response);

        } catch (InvalidURLException $error) {
            return $this->json('Invalid URL address', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $error) {
            return $this->json('Internal application error ' . $error->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
