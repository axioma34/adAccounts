<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserAccount;
use App\Entity\UserGroup;
use App\Entity\UsersGroups;
use App\Form\UserType;
use App\Repository\AdAccountRepository;
use App\Repository\UserAccountRepository;
use App\Repository\UserGroupRepository;
use App\Repository\UserRepository;
use App\Repository\UsersGroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * Список пользователей.
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/list", name="user_list", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Список пользователей",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\User::class)))
     *     )
     * )
     * @OA\Tag(name="Пользователи")
     */
    public function list(UserRepository $userRepository): Response
    {
        return $this->json($userRepository->findAll());
    }


    /**
     * Создание пользователя.
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param AdAccountRepository $accountRepository
     * @param UserGroupRepository $groupRepository
     * @param UserAccountRepository $userAccountRepository
     * @param UsersGroupsRepository $groupsRepository
     * @return Response
     * @Route("/", name="user_new", methods={"POST"})
     *
     * @OA\Parameter(
     *     name="payload",
     *     in="query",
     *     description="Данные пользователя",
     *     required=true,
     *     @Model(type=App\Form\UserType::class)
     * )
     *
     * @OA\Response(
     *     response="200",
     *     description="Пользователь создан",
     *     @OA\Schema(ref=@Model(type=App\Entity\User::class))
     * )
     * @OA\Tag(name="Пользователи")
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder,
                        AdAccountRepository $accountRepository,
                        UserGroupRepository $groupRepository,
                        UserAccountRepository $userAccountRepository,
                        UsersGroupsRepository $groupsRepository): Response
    {
        $user = new User();
        $data = $request->toArray();

        $this->userFormHandler($user, $data, $accountRepository, $groupRepository, $encoder,
            $userAccountRepository, $groupsRepository);

        return $this->json($user);
    }


    /**
     * Редактирование пользователя.
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param AdAccountRepository $accountRepository
     * @param UserGroupRepository $groupRepository
     * @param UserAccountRepository $userAccountRepository
     * @param UsersGroupsRepository $groupsRepository
     * @return Response
     * @Route("/{id}", name="user_edit", methods={"POST"})
     *
     * @OA\Parameter(
     *     name="payload",
     *     in="query",
     *     description="Данные пользователя",
     *     required=true,
     *     @Model(type=App\Form\UserType::class)
     * )
     *
     * @OA\Response(
     *     response="200",
     *     description="Пользователь изменен",
     *     @OA\Schema(ref=@Model(type=App\Entity\User::class))
     * )
     * @OA\Tag(name="Пользователи")
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $encoder,
                         AdAccountRepository $accountRepository,
                         UserGroupRepository $groupRepository,
                         UserAccountRepository $userAccountRepository,
                         UsersGroupsRepository $groupsRepository): Response
    {

        $data = $request->toArray();
        if (array_key_exists('password', $data) && !$data['password']) {
            $data['password'] = $user->getPassword();
        }

        $this->userFormHandler($user, $data, $accountRepository, $groupRepository, $encoder,
            $userAccountRepository, $groupsRepository);

        return $this->json($user);
    }

    /**
     * Удаление пользователя.
     * @param User $user
     * @return Response
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     *
     * @OA\Parameter(name="id", in="path", description="ID пользователя", required=true)
     *
     * @OA\Response(
     *     response="200",
     *     description="Пользователь удален",
     * )
     * @OA\Tag(name="Пользователи")
     */
    public function delete(User $user): Response
    {
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('');
    }

    /**
     * @param User $user
     * @param array $data
     * @param AdAccountRepository $accountRepository
     * @param UserGroupRepository $groupRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param UserAccountRepository $userAccountRepository
     * @param UsersGroupsRepository $groupsRepository
     */
    protected function userFormHandler(User $user, array $data,
                                       AdAccountRepository $accountRepository,
                                       UserGroupRepository $groupRepository,
                                       UserPasswordEncoderInterface $encoder,
                                       UserAccountRepository $userAccountRepository,
                                       UsersGroupsRepository $groupsRepository): void
    {

        $isAdmin = array_key_exists('is_admin', $data) ? $data['is_admin'] : false;
        if ($isAdmin) $user->setRoles(["ROLE_ADMIN"]);

        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if (!$form->isValid()) throw new BadRequestHttpException('Invalid form data');

        $entityManager = $this->getDoctrine()->getManager();

        if (!$user->getId()) {
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
        }


        $accounts = $user->getAccounts();
        $user->setAccounts(new ArrayCollection());
        $entityManager->persist($user);
        if ($accounts) {
            foreach ($accounts as $key => $adAccount) {
                $account = $accountRepository->findOneBy(['id' => $key]);
                if (!$account || !$account->getStatus()) continue;

                $userAccount = $userAccountRepository->findOneBy(['user' => $user, 'account' => $account]);
                if (!$userAccount) $user->addAccount($account);

                $group = $groupRepository->findOneBy(['code' => $adAccount]);
                if (!$group) continue;

                $usersGroups = $groupsRepository->findOneBy(['user' =>$user, 'group' => $group]);
                if ($usersGroups) continue;

                $userGroup = new UsersGroups();
                $userGroup->setAccount($account);
                $userGroup->setUser($user);
                $userGroup->setGroup($group);
                $entityManager->persist($userGroup);

            }
        }
        dump($user);
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
