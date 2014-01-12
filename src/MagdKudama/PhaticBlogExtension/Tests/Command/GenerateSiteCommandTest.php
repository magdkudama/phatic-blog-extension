<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Command;

use MagdKudama\Phatic\Console\Application;
use MagdKudama\Phatic\Tests\TestCase;
use MagdKudama\PhaticBlogExtension\Command\GenerateSiteCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class GenerateSiteCommandTest extends TestCase
{
    /** @var Application */
    protected $application;

    /** @var CommandTester */
    protected $commandTester;

    /** @var Filesystem */
    protected $filesystem;

    /** @var Command */
    protected $command;

    protected $options;

    public function setUp()
    {
        $this->filesystem = new Filesystem();

        $this->application = new Application();
        $this->application->add(new GenerateSiteCommand());
        $this->application->setAutoExit(false);

        $this->command = $this->application->find('blog:generate-site');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @dataProvider siteProvider
     */
    public function testCommandGeneratesExpectedOutput($site)
    {
        $siteDirectory = __DIR__ . '/Fixtures/' . $site . '/';
        $this->filesystem->mkdir($siteDirectory . 'site/result');

        $options = [
            'command' => $this->command->getName(),
            '--config' => __DIR__ . '/Fixtures/phatic.yml',
            '--dir' => $siteDirectory
        ];

        $this->commandTester->execute($options);

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/Results/output-' . $site . '.txt',
            $this->commandTester->getDisplay(),
            'Command behaves correctly, outputting content to screen'
        );

        $this->filesystem->remove($siteDirectory . 'result');
    }

    /**
     * @expectedException MagdKudama\PhaticBlogExtension\Exception\PageNotFoundException
     */
    public function testAbsentHomepageThrowsException()
    {
        $siteDirectory = __DIR__ . '/Fixtures/site4/';
        $this->filesystem->mkdir($siteDirectory . 'site/result');

        $options = [
            'command' => $this->command->getName(),
            '--config' => __DIR__ . '/Fixtures/phatic.yml',
            '--dir' => $siteDirectory
        ];

        $this->commandTester->execute($options);

        $this->filesystem->remove($siteDirectory . 'result');
    }

    public function siteProvider()
    {
        return [
            ['site1'],
            ['site2'],
            ['site3'],
        ];
    }
}