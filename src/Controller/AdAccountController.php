<?php

namespace App\Controller;

use App\Entity\AdAccount;
use App\Entity\UserAccount;
use App\Form\AdAccountType;
use App\Repository\AdAccountRepository;
use App\Repository\UserAccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

#[Route('/ad/account')]
class AdAccountController extends AbstractController
{
    /**
     * Список кабинетов.
     * @param AdAccountRepository $adAccountRepository
     * @return Response
     * @Route("/list", name="ad_account_list", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Список доступных кабинетов",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\AdAccount::class)))
     *     )
     * )
     * @OA\Tag(name="Рекламные кабинеты")
     */
    public function list(AdAccountRepository $adAccountRepository, UserAccountRepository $repository): Response
    {
        if (array_search('ROLE_ADMIN',$this->getUser()->getRoles())) {
            return $this->json($adAccountRepository->findAll());
        }
        $userAccounts = $repository->findBy(['user' => $this->getUser()]);

        $accounts = [];
        foreach ($userAccounts as $account) {
            $accounts[] = $account->getAccount();
        }

        return $this->json($accounts);
    }

    /**
     * Создание кабинета.
     * @param Request $request
     * @return Response
     * @Route("/", name="ad_account_new", methods={"POST"})
     *
     * @OA\Parameter(
     *     name="payload",
     *     in="query",
     *     description="Данные кабинета",
     *     required=true,
     *     @Model(type=App\Form\AdAccountType::class)
     * )
     *
     * @OA\Response(
     *     response="200",
     *     description="Кабинет создан",
     *     @OA\Schema(ref=@Model(type=App\Entity\AdAccount::class))
     * )
     * @OA\Tag(name="Рекламные кабинеты")
     */
    public function new(Request $request): Response
    {
        $adAccount = new AdAccount();
        $form = $this->createForm(AdAccountType::class, $adAccount);
        $form->submit($request->query->all());

        if (!$form->isValid()) throw new BadRequestHttpException('Invalid form data');

        $this->getDoctrine()->getManager()->persist($adAccount);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($adAccount);
    }

    /**
     * Изменение кабинета.
     * @param Request $request
     * @return Response
     * @Route("/{id}/edit", name="ad_account_edit", methods={"POST"})
     *
     * @OA\Parameter(
     *     name="payload",
     *     in="query",
     *     description="Данные кабинета",
     *     required=true,
     *     @Model(type=App\Form\AdAccountType::class)
     * )
     * @OA\Parameter(name="id", in="path", description="ID кабинета", required=true)
     *
     * @OA\Response(
     *     response="200",
     *     description="Кабинет создан",
     *     @OA\Schema(ref=@Model(type=App\Entity\AdAccount::class))
     * )
     * @OA\Tag(name="Рекламные кабинеты")
     */
    public function edit(Request $request, AdAccount $adAccount): Response
    {
        $form = $this->createForm(AdAccountType::class, $adAccount);
        $form->submit($request->query->all());

        if (!$form->isValid()) throw new BadRequestHttpException('Invalid form data');

        $this->getDoctrine()->getManager()->persist($adAccount);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($adAccount);
    }

    /**
     * Удаление кабинета.
     * @param AdAccount $adAccount
     * @return Response
     * @Route("/{id}", name="ad_account_delete", methods={"POST"})
     *
     * @OA\Parameter(name="id", in="path", description="ID кабинета", required=true)
     *
     * @OA\Response(
     *     response="200",
     *     description="Кабинет успешно удален",
     * )
     * @OA\Tag(name="Рекламные кабинеты")
     */
    public function delete(AdAccount $adAccount): Response
    {
        $this->getDoctrine()->getManager()->remove($adAccount);
        $this->getDoctrine()->getManager()->flush();

        return new Response('');
    }
}
