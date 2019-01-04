<?php declare(strict_types=1);

namespace SymfonyDocsBuilder\Listener;

use Symfony\Component\Filesystem\Filesystem;
use SymfonyDocsBuilder\BuildContext;

class CopyImagesDirectoryListener
{
    private $configBag;

    public function __construct(BuildContext $configBag)
    {
        $this->configBag = $configBag;
    }

    public function postBuildRender()
    {
        $fs = new Filesystem();
        if ($fs->exists($imageDir = sprintf('%s/_images', $this->configBag->getSourceDir()))) {
            $fs->mirror($imageDir, sprintf('%s/_images', $this->configBag->getHtmlOutputDir()));
        }
    }
}