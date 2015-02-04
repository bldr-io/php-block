<?php

/**
 * This file is part of php-block
 *
 * (c) Ameen Ross <a.ross@amdev.eu>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE
 */

namespace Bldr\Block\Php\Task\Output;

use JakubOnderka\PhpParallelLint as Linter;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Ameen Ross <a.ross@amdev.eu>
 */
class Output extends Linter\TextOutput
{
    /**
     * @var OutputInterface $realOutput
     */
    private $realOutput;

    /**
     * @param Linter\IWriter $writer
     */
    public function __construct(Linter\IWriter $writer)
    {
    }

    /**
     * @param OutputInterface $realOutput
     * @return Output
     */
    public static function create(OutputInterface $realOutput)
    {
        $output = new static(new Linter\NullWriter);
        $output->realOutput = $realOutput;

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function write($string, $type = self::TYPE_DEFAULT)
    {
        switch ($type) {
            case self::TYPE_OK:
                $this->realOutput->write("<info>{$string}<info>");
                break;
            case self::TYPE_SKIP:
                $this->realOutput->write("<comment>{$string}<comment>");
                break;
            case self::TYPE_ERROR:
                $this->realOutput->write("<error>{$string}<error>");
                break;
            default:
                $this->realOutput->write($string);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function ok()
    {
        $this->write('.');
        $this->progress();
    }

    /**
     * {@inheritdoc}
     */
    public function fail()
    {
        $this->write('-');
        $this->progress();
    }
}
