<?php

namespace App\Form\DataTransformer;

use App\Entity\Label;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class LabelTransformer implements DataTransformerInterface
{
    private EntityManagerInterface $em;
    private LabelRepository $labelRepo;

    public function __construct(EntityManagerInterface $em, LabelRepository $labelRepo)
    {
        $this->em = $em;
        $this->labelRepo = $labelRepo;
    }

    public function transform($label): string
    {
        if (null === $label) {
            return '';
        }

        return $label->getId();
    }

    public function reverseTransform($labelId): ?Label
    {
        if (empty($labelId)) {
            return null;
        }

        $label = $this->labelRepo->find($labelId);

        if (null === $label) {
            throw new TransformationFailedException(sprintf