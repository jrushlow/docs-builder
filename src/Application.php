<?php declare(strict_types=1);

namespace SymfonyDocsBuilder;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use SymfonyDocsBuilder\Command\BuildDocsCommand;

class Application
{
    private $application;
    private $configBag;

    public function __construct(string $symfonyVersion)
    {
        $this->application = new BaseApplication();

        $configuration   = $this->getSymfonyDocConfiguration($basePath = realpath(__DIR__.'/..'));
        $this->configBag = new BuildContext(
            $basePath,
            $symfonyVersion,
            sprintf($configuration['symfony_api_url'], $symfonyVersion),
            $configuration['php_doc_url'],
            sprintf($configuration['symfony_doc_url'], $symfonyVersion)
        );
    }

    public function run(InputInterface $input): int
    {
        $inputOption = new InputOption(
            'symfony-version',
            null,
            InputOption::VALUE_REQUIRED,
            'The symfony version of the doc to parse.',
            false === getenv('SYMFONY_VERSION') ? 'master' : getenv('SYMFONY_VERSION')
        );
        $this->application->getDefinition()->addOption($inputOption);
        $this->application->add(
            new BuildDocsCommand($this->configBag)
        );

        return $this->application->run($input);
    }

    private function getSymfonyDocConfiguration(string $basePath): array
    {
        return json_decode(file_get_contents(sprintf('%s/conf.json', $basePath)), true);
    }
}