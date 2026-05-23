<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class TestController
{
    public function __construct(
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $rootDir,
    ) {
    }

    public function __invoke(): Response
    {
        $process = new Process(['php', 'bin/console', 'app:test'], $this->rootDir);
        $process->mustRun();

        return new Response(nl2br($process->getOutput() . PHP_EOL . $process->getErrorOutput()));
    }
}
