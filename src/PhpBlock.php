<?php

/**
 * This file is part of php-block
 *
 * (c) Ameen Ross <a.ross@amdev.eu>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE
 */

namespace Bldr\Block\Php;

use Bldr\DependencyInjection\AbstractBlock;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;

/**
 * @author Ameen Ross <a.ross@amdev.eu>
 */
class PhpBlock extends AbstractBlock
{
    /**
     * {@inheritDoc}
     */
    protected function assemble(array $config, SymfonyContainerBuilder $container)
    {
        $this->addTask('bldr_php.lint', 'Bldr\Block\Php\Task\LintTask');
    }
}
