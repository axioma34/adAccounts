<?php

namespace App\Controller;

use App\Entity\UserAccount;
use App\Entity\UserGroup;
use App\Entity\UsersGroups;
use App\Repository\UserAccountRepository;
use App\Repository\UserRepository;
use App\Repository\UsersGroupsRepository;
use App\Security\TokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserAuthController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param Request $request
     * @param UserRepository $userRepo
     * @param UserPasswordEncoderInterface $encoder
     * @param UserAccountRepository $userAccRepo
     * @param UsersGroupsRepository $ugRepo
     * @param GuardAuthenticatorHandler $guardHandler
     * @param TokenAuthenticator $authenticator
     * @return Response
     */
    public function login(Request $request, UserRepository $userRepo,
                          UserPasswordEncoderInterface $encoder,
                          UserAccountRepository $userAccRepo,
                          UsersGroupsRepository $ugRepo,
                          GuardAuthenticatorHandler $guardHandler,
                          TokenAuthenticator $authenticator): Response
    {
        $data = $request->toArray();
        $login = array_key_exists('login', $data) ? $data['login'] : null;
        $password = array_key_exists('password', $data) ? $data['password'] : null;

        $user = $userRepo->findOneBy(['email' => $login]);

        if (!$user || !$user->isActive()) throw new NotFoundHttpException('User not found or disabled');

        if (!$encoder->isPasswordValid($user, $password)) {
            throw new AccessDeniedHttpException('Wrong password');
        }

        $activeUserAccounts = [];
        $userAccounts = $userAccRepo->findOneBy(['user' => $user]) ?? [];

        /** @var UserAccount $account */
        foreach ($userAccounts as $account) {
            $account = $account->getAccount();
            $userGroup = $ugRepo->findOneBy(['user' => $user, 'account' => $account]);
            if (!$userGroup) continue;
            $group = $userGroup->getGroup();
            if (!$account->getStatus() && $group->getCode() !== UserGroup::EDIT) continue;
            $activeUserAccounts[] = $account;
        }

        dump((array_search('ROLE_ADMIN',$user->getRoles()) == false) );
        if ((array_search('ROLE_ADMIN',$user->getRoles()) === false) && !count($activeUserAccounts)) {
            throw new BadRequestHttpException('У пользователя нет доступов ни к одному кабинету');
        }

        if (!$user->getApiToken()) {
            $user->setApiToken($encoder->encodePassword($user, time()));
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->json(['token' => $user->getApiToken()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
