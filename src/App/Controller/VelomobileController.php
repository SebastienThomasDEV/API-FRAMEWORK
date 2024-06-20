<?php

namespace Sthom\Back\App\Controller;

use Sthom\Back\App\Entity\TechnicalSheet;
use Sthom\Back\App\Repository\TechnicalSheetRepository;
use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Routing\Route;
use Sthom\Back\Kernel\Framework\Services\Request;

class VelomobileController extends AbstractController
{

    #[Route(path: '/velomobiles', requestType: 'GET', guarded: false)]
    public final function showAll(TechnicalSheetRepository $technicalSheetRepository): array
    {
        $technicalSheets = $technicalSheetRepository->findAll();
        return $this->send($technicalSheets);
    }

    #[Route(path: '/velomobiles/{id}', requestType: 'GET', guarded: false)]
    public final function show(TechnicalSheetRepository $technicalSheetRepository, Request $request): array
    {
        $id = $request->getAttribute('id');
        $technicalSheet = $technicalSheetRepository->find($id);
        return $this->send($technicalSheet);
    }

    #[Route(path: '/velomobiles', requestType: 'POST', guarded: true)]
    public final function store(TechnicalSheetRepository $technicalSheetRepository, Request $request): array
    {
        $data = $request->getBody();
        $technicalSheet = new TechnicalSheet();
        $technicalSheet->setName($data['name']);
        $technicalSheet->setDescription($data['description']);
        $technicalSheet->setWeight($data['weight']);
        $technicalSheet->setImage($data['image']);
        $technicalSheetRepository->save($technicalSheet);
        return $this->send([
            'message' => 'TechnicalSheet created',
        ]);
    }

    #[Route(path: '/velomobiles/{id}', requestType: 'PUT', guarded: true)]
    public final function update(TechnicalSheetRepository $technicalSheetRepository, Request $request): array
    {
        $id = $request->getAttribute('id');
        $data = $request->getBody();
        $technicalSheet = $technicalSheetRepository->find($id);
        $technicalSheet->setName($data['model']);
        $technicalSheet->setDescription($data['description']);
        $technicalSheet->setWeight($data['weight']);
        $technicalSheet->setImage($data['photo']);
        $technicalSheetRepository->save($technicalSheet);
        return $this->send([
            'message' => 'TechnicalSheet updated',
        ]);
    }

    #[Route(path: '/velomobiles/{id}', requestType: 'DELETE', guarded: true)]
    public final function delete(TechnicalSheetRepository $technicalSheetRepository, Request $request): array
    {
        $id = $request->getAttribute('id');
        $technicalSheetRepository->delete($id);
        return $this->send([
            'message' => 'TechnicalSheet deleted',
        ]);
    }












}