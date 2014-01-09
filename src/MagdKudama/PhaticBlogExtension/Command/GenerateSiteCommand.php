<?php

namespace MagdKudama\PhaticBlogExtension\Command;

use Doctrine\Common\Util\Inflector;
use MagdKudama\Phatic\AbstractProcessor;
use MagdKudama\Phatic\Collection\ProcessorCollection;
use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\Phatic\Console\Command\CommandOutputHelper;
use MagdKudama\Phatic\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateSiteCommand extends ContainerAwareCommand
{
    private $resultSite;
    private $postsDirectory;
    private $assetsDirectory;

    /** @var Filesystem */
    private $fileSystem;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        /** @var ApplicationConfig $config */
        $config = $this->getContainer()->get('phatic.config');
        $this->resultSite = $config->getResultsPath();
        $this->postsDirectory = $config->getPostsPath();
        $this->assetsDirectory = $config->getAssetsPath();

        $this->fileSystem = $this->getFileSystem();
    }

    protected function configure()
    {
        $this
            ->setName('blog:generate-site')
            ->setDescription('Generates the whole application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->fileSystem->exists($this->resultSite)) {
            CommandOutputHelper::writeError($output, 'The application has not been bootstrapped. Did you forgot to execute the "bootstrap" method?');
            return;
        }

        CommandOutputHelper::writeInfo($output, 'Deleting results directory...');
        $this->fileSystem->remove($this->resultSite);

        CommandOutputHelper::writeInfo($output, 'Creating results directory...');
        $this->fileSystem->mkdir($this->resultSite);

        if (!$this->fileSystem->exists($this->postsDirectory)) {
            $this->fileSystem->mkdir($this->postsDirectory);
        }

        CommandOutputHelper::writeInfo($output, 'Dumping assets...');

        $this->fileSystem->mkdir($this->resultSite . 'assets');
        $this->fileSystem->mirror($this->assetsDirectory, $this->resultSite . 'assets');

        $processors = $this->getContainer()->get('phatic.processors');
        if (!$processors instanceof ProcessorCollection) {
            CommandOutputHelper::writeError($output, 'Processors errors... :(');
            return;
        }

        /** @var AbstractProcessor $processor */
        foreach ($processors as $processor) {
            $processorName = strtoupper(Inflector::pluralize($processor->getName()));
            $this->writeHeader($output, 'CREATING ' . $processorName);
            foreach ($processor->getCollection() as $info) {
                $message = sprintf("Creating %s %s...", $processor->getName(), $info);
                CommandOutputHelper::writeInfo($output, $message);
                $processor->dump($info);
            }
        }

        CommandOutputHelper::writeComment($output, "");
        CommandOutputHelper::success($output);
    }

    /**
     * @param string $message
     */
    protected function writeHeader(OutputInterface $output, $message)
    {
        CommandOutputHelper::writeComment($output, "");
        CommandOutputHelper::writeComment($output, "-----------------------------------");
        CommandOutputHelper::writeComment($output, $message);
        CommandOutputHelper::writeComment($output, "-----------------------------------");
        CommandOutputHelper::writeComment($output, "");
    }
}