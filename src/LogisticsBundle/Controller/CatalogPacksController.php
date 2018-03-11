<?php

namespace LogisticsBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use LogisticsBundle\Entity\CatalogPack;
use LogisticsBundle\Entity\CatalogPackDetail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CatalogPacksController
 * @package LogisticsBundle\Controller
 */
class CatalogPacksController extends Controller
{
    public function postCatalogpacksAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $itemRepository = $em->getRepository('LogisticsBundle:CatalogItem');
        $content = $request->getContent();

        $data = json_decode($content, true);

        foreach ($data as $packToCreate)
        {
            $pack = new CatalogPack();
            $pack->setRefName($packToCreate['refName']);
            $pack->setDescription($packToCreate['description']);
            $pack->setEan($packToCreate['ean']);
            foreach ($packToCreate['content'] as $packDetailToCreate) {
                if (!empty($packDetailToCreate)) {
                    $packDetail = new CatalogPackDetail();
                    $item = $itemRepository->findOneByEan($packDetailToCreate['ean']);
                    $packDetail->setCatalogItem($item);
                    $packDetail->setQuantity($packDetailToCreate['quantity']);
                    $pack->addCatalogPackDetail($packDetail);
                }
            }
            $em->persist($pack);
        }
        $em->flush();
        $em->clear();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_CREATED);
        return $response;
    }

    public function getCatalogpacksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $packRepository = $em->getRepository('LogisticsBundle:CatalogPack');

        $packs = $packRepository->findAll();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($packs, 'json'));

        return $response;
    }
}