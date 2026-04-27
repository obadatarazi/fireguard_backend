<?php

namespace App\Controller\Api\Admin;

use App\Controller\RestController;
use App\Service\DeviceService;
use App\Service\ForestService;
use App\Service\RestHelperService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/filter-values')]
class AdminFilterValuesController extends RestController
{
    public function __construct(
        RestHelperService $rest,
        EntityManagerInterface $em,
        private readonly ForestService $forestService,
        private readonly DeviceService $deviceService
    )
    {
        parent::__construct($rest, $em);
    }

    #[Route('/forests', methods: ['GET'])]
    #[IsGranted('ROLE_FOREST_LIST')]
    #[OA\Tag(name: 'Filter Values')]
    #[OA\Response(response: 200, description: 'Returns forests options', content: new OA\JsonContent(properties: [
        new OA\Property(property: "success", type: "boolean"),
        new OA\Property(property: "data", type: "array", items: new OA\Items(properties: [
            new OA\Property(property: "id", type: "integer", example: 1),
            new OA\Property(property: "name", type: "string", example: "forest name"),
        ])),
    ]))]
    #[OA\Parameter(ref: "#components/parameters/locale")]
    #[Security(name: "Bearer")]
    public function forests(): Response
    {
        $items = $this->forestService->getOptions();

        $this->rest->succeeded()->setData($items);

        return $this->viewResponse($this->rest->getResponse(), Response::HTTP_OK);
    }

    #[Route('/devices', methods: ['GET'])]
    #[IsGranted('ROLE_DEVICE_LIST')]
    #[OA\Tag(name: 'Filter Values')]
    #[OA\Response(response: 200, description: 'Returns devices options', content: new OA\JsonContent(properties: [
        new OA\Property(property: "success", type: "boolean"),
        new OA\Property(property: "data", type: "array", items: new OA\Items(properties: [
            new OA\Property(property: "id", type: "integer", example: 1),
            new OA\Property(property: "name", type: "string", example: "device name"),
        ])),
    ]))]
    #[OA\Parameter(ref: "#components/parameters/locale")]
    #[OA\Parameter(name: "forest", description: "Filter devices by forest id.", in: "query",
        schema: new OA\Schema(type: "integer", example: 1),
    )]
    #[Security(name: "Bearer")]
    public function devices(Request $request): Response
    {
        $forestId = $request->query->getInt('forest');
        $items = $this->deviceService->getOptions($forestId > 0 ? $forestId : null);

        $this->rest->succeeded()->setData($items);

        return $this->viewResponse($this->rest->getResponse(), Response::HTTP_OK);
    }
}
