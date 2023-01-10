<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Form\ResetPasswordRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    #[Route(path: '/{_locale}/login/', name: 'app_login', defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        // if user is already logged in, redirect to home and add a flash message
        $isLogged = $this->getUser() ? true : false;

        if ($isLogged) {
            $translatedMessage = $translator->trans('You are already logged in, you can\'t access this page. If you want to register a new account, please <a href="/logout">logout first</a>.');
            $this->addFlash('warning', $translatedMessage);

            return $this->redirectToRoute('app_profile');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout/', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // create a new route to reset password
    #[Route(path: '/{_locale}/reset-password-request/', name: 'app_reset_password_request', defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function resetPassword(Request $request, UserRepository $ur, TranslatorInterface $translator, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ResetPasswordRequestType::class);

        // handle the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get the email address from the form
            $email = $form->get('email')->getData();

            // find the user in the database
            $user = $ur->findOneBy(['email' => $email]);

            // if the user is found, send an email to reset his password
            if ($user) {
                // generate a unique token
                $token = $tokenGenerator->generateToken();

                // set reset token in the database
                $user->setResetToken($token);

                // persist the token in the database
                // save the token in the database
                $em->persist($user);
                $em->flush();

                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // send the email
                $email = (new TemplatedEmail())
                    ->from(new Address('mailer@titouanthd.com', 'Ti Bot'))
                    ->to($user->getEmail())
                    ->subject('Reset your password')
                    ->htmlTemplate('emails/reset_password_request.html.twig')
                    ->context([
                        'url' => $url,
                        'user' => $user,
                    ]);

                $this->mailer->send($email);

                // add a flash message to inform the user that an email has been sent
                $this->addFlash('info', 'If your email is in our database, you will receive an email to reset your password.');
            } else {
                // add a flash message to inform the user that if his email is in the database, he will receive an email to reset his password
                // even if the email is not in the database, we don't want to inform the user that the email is not in the database
                $this->addFlash('info', $translator->trans('If your email is in our database, you will receive an email to reset your password.'));
            }
        }

        return $this->render('security/reset_password_request.html.twig', [
            'resetPasswordRequestForm' => $form->createView(),
        ]);
    }

    // create a new route to reset the password
    #[Route(path: '/{_locale}/reset-password/{token}', name: 'app_reset_password', defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function resetPasswordWithToken(string $token, Request $request, UserRepository $ur, TranslatorInterface $translator, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        // find the user in the database
        $user = $ur->findOneBy(['resetToken' => $token]);

        // if the user is not found, redirect to the reset password request page and add a flash message
        if ($user) {
            // create a new form to reset the password
            $form = $this->createForm(ResetPasswordType::class);

            // handle the form
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // remove the reset token from the database
                $user->setResetToken(null);

                // set the new password
                $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));

                // save the new password in the database
                $em->persist($user);
                $em->flush();

                // add a flash message to inform the user that his password has been reset
                $this->addFlash('success', $translator->trans('Your password has been reset.'));
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'resetPasswordForm' => $form->createView(),
            ]);

        } else {
            $this->addFlash('danger', $translator->trans('This token is not valid.'));
            return $this->redirectToRoute('app_reset_password_request');
        }

        // if the user is found, create a new form to reset the password
        $form = $this->createForm(ResetPasswordType::class);

        // handle the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // code to reset the password
        }

        return $this->render('security/reset_password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);

    }
}
