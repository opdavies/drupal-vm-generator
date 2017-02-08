<?php

namespace DrupalVm\Command;

use DrupalVm\Exception\FileEmptyException;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment;

abstract class GeneratorCommand extends Command
{
    /**
     * Check if the file already exists.
     */
    protected function assertFileAlreadyExists($filename)
    {
        if (file_exists(
                $this->projectDir.'/'.$filename
            ) && !$this->input->getOption('overwrite')
        ) {
            $this->io->error(sprintf('%s already exists.', $filename));

            exit(1);
        }
    }

    /**
     * A shortcut for rendering a Twig template.
     *
     * @param $template
     * @param array $parameters
     *
     * @return string
     */
    protected function render($template, array $parameters)
    {
        return $this->container['twig']->render($template, $parameters);
    }

    /**
     * @param string $filename
     * @param string $contents
     *
     * @return Command
     *
     * @throws Exception
     */
    protected function writeFile($filename, $contents)
    {
        if (empty($contents)) {
            throw new FileEmptyException('The generated file was empty.');
        }

        $this->container['filesystem']->dumpFile($filename, $contents);

        $this->io->success(sprintf('%s created', $filename));

        return $this;
    }
}
