<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

final class SignupController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/signup', name: 'user_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the plain password
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

            // Persist the new user entity
            $em->persist($user);
            $em->flush();

            // Send email confirmation
            $this->emailVerifier->sendEmailConfirmation('verify_user_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@blogsite.com', 'Blog Platform'))
                    ->to($user->getEmail())
                    ->subject('Confirm Your Email Address')
                    ->htmlTemplate('signup/confirmation_email.html.twig')
            );

            // Redirect to blog index after successful registration
            return $this->redirectToRoute('blog_index');
        }

        return $this->render('signup/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'verify_user_email')]
    public function verifyEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verification_error', $translator->trans($e->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('user_register');
        }

        $this->addFlash('success', 'Your email has been verified successfully.');

        return $this->redirectToRoute('user_register');
    }
}
