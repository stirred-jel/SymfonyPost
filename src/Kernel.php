<?php

namespace App\Core;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

final class AppKernel extends SymfonyKernel
{
    use MicroKernelTrait;
}
