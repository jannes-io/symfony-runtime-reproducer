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
        $output = '';


        $output .= '<strong>Running bin/console command with symfony/process without QUERY_STRING overwrite:</strong><br/>';
        $process = new Process(['php', 'bin/console', 'app:test'], $this->rootDir);
        $process->mustRun();
        $output .= substr(nl2br($process->getOutput() . PHP_EOL . $process->getErrorOutput()), 0, 200);
        $output .= '<br/><br/>----------------------------------------------------------------------<br/><br/>';


        $output .= '<strong>Running bin/console command with symfony/process with QUERY_STRING overwrite:</strong><br/>';
        $process = new Process(['php', 'bin/console', 'app:test'], $this->rootDir, ['QUERY_STRING' => false]);
        $process->mustRun();
        $output .= substr(nl2br($process->getOutput() . PHP_EOL . $process->getErrorOutput()), 0, 200);
        $output .= '<br/><br/>----------------------------------------------------------------------<br/><br/>';


        $output .= '<strong>Running bin/console with proc_open:</strong><br/>';
        $p = proc_open(
            'php bin/console app:test',
            [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']],
            $pipes,
            $this->rootDir,
        );
        $output .= stream_get_contents($pipes[1]);
        proc_close($p);
        $output .= '<br/><br/>----------------------------------------------------------------------<br/><br/>';


        $output .= '<strong>Running "php dumpenv.php" with symfony/process:</strong><br/>';
        $process = new Process(['php', 'dumpenv.php'], $this->rootDir);
        $process->mustRun();
        $output .= nl2br($process->getOutput() . PHP_EOL . $process->getErrorOutput());
        $output .= '<br/><br/>----------------------------------------------------------------------<br/><br/>';


        $output .= '<strong>Running "php dumpenv.php" with proc_open:</strong><br/>';
        $p = proc_open(
            'php dumpenv.php',
            [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']],
            $pipes,
            $this->rootDir,
        );
        $output .= stream_get_contents($pipes[1]);
        proc_close($p);
        $output .= '<br/><br/>----------------------------------------------------------------------<br/><br/>';


        return new Response($output);
    }
}
