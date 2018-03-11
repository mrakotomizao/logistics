<?php

namespace LogisticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UsersController
 * @package LogisticsBundle\Controller
 */
class UsersController extends Controller
{

    /**
     * GET the user profile whose token is currently in use including
     * related distributor information and user details
     *
     * @return JsonResponse
     *
     * @ApiDoc(
     *     resource="Operations on user profiles.",
     *     resourceDescription="Operations on user profiles.",
     *     section="Users",
     *     description="Retrieve current user profile information.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function getProfileAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $serializer = $this->get("jms_serializer");

        return new Response($serializer->serialize($user, 'json'));

    }

}