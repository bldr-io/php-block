<?php

/**
 * This file is part of php-block
 *
 * (c) Ameen Ross <a.ross@amdev.eu>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE
 */

namespace Bldr\Block\Php\Task;

use Bldr\Block\Core\Task\AbstractTask;
use Bldr\Block\Core\Task\Traits\FinderAwareTrait;
use Bldr\Exception\TaskRuntimeException;
use JakubOnderka\PhpParallelLint as Linter;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Ameen Ross <a.ross@amdev.eu>
 */
class LintTask extends AbstractTask
{
    use FinderAwareTrait;

    /**
     * @var Linter\Manager $linter
     */
    private $linter;

    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        $this->setName('lint')
            ->setDescription('Lints PHP files in parallel')
            ->addParameter('paths', true, 'Array of PHP files or directories to check')
            ->addParameter('extensions', false, 'Array of file extensions to check within directories')
            ->addParameter('excluded', false, 'Array of files or directories to exclude')
            ->addParameter('shortTag', false, 'Flag whether to check code between short PHP tags')
            ->addParameter('aspTags', false, 'Flag whether to check code between ASP tags')
            ->addParameter('parallelJobs', false, 'The number of lint jobs to run in parallel')
            ->addParameter('phpExecutable', false, 'The path to the PHP executable')
            ->addParameter('gitExecutable', false, 'The path to the git executable');
    }

    /**
     * {@inheritDoc}
     */
    public function run(OutputInterface $output)
    {
        // Run the linter.
        $this->linter = new Linter\Manager();
        $this->linter->setOutput(Output\Output::create($output));
        $result = $this->linter->run($this->getLinterSettings());

        // Throw an exception when syntax errors were found.
        if ($result->hasError()) {
            throw new TaskRuntimeException($this->getName(), 'The linter identified syntax errors.');
        }
    }

    /**
     * @return Linter\Settings
     * @throws \RuntimeException
     */
    private function getLinterSettings()
    {
        $settings = new Linter\Settings;
        $settings->paths = $this->getParameter('paths');

        if ($this->hasParameter('extensions')) {
            $settings->extensions = $this->getParameter('extensions');
        }

        if ($this->hasParameter('excluded')) {
            $settings->excluded = $this->getParameter('excluded');
        }

        if ($this->hasParameter('shortTag')) {
            $settings->shortTag = $this->getParameter('shortTag');
        }

        if ($this->hasParameter('aspTags')) {
            $settings->aspTags = $this->getParameter('aspTags');
        }

        if ($this->hasParameter('parallelJobs')) {
            $settings->parallelJobs = $this->getParameter('parallelJobs');
        }

        if ($this->hasParameter('phpExecutable')) {
            $settings->phpExecutable = $this->getParameter('phpExecutable');
        }

        if ($this->hasParameter('gitExecutable')) {
            $settings->gitExecutable = $this->getParameter('gitExecutable');
        }

        return $settings;
    }
}
