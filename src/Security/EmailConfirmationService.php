<?php

namespace App\Security;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailConfirmationService
{
    private VerifyEmailHelperInterface $verifyHelper;
    private MailerInterface $mailerService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        VerifyEmailHelperInterface $verifyHelper,
        MailerInterface $mailerService,
        EntityManagerInterface $entityManager
    ) {
        $this->verifyHelper = $verifyHelper;
        $this->mailerService = $mailerService;
        $this->entityManager = $entityManager;
    }

    public function sendConfirmationEmail(string $verificationRoute, Member $member, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyHelper->generateSignature(
            $verificationRoute,
            (string) $member->getId(),
            (string) $member->getMail()
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiryKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiryData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailerService->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleConfirmation(Request $request, Member $member): void
    {
        $this->verifyHelper->validateEmailConfirmationFromRequest($request, (string) $member->getId(), (string) $member->getMail());

        $member->setVerified(true);

        $this->entityManager->persist($member);
        $this->entityManager->flush();
    }
}
