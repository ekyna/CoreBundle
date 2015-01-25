<?php

namespace Ekyna\Bundle\CoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class ClearImagesCommand
 * @package Ekyna\Bundle\CoreBundle\Command
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ClearImagesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ekyna:core:clear-images')
            ->setDescription('Removes images files.')
            ->setHelp(<<<EOT
The <info>ekyna:core:clear-images</info> removes images files.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = $this->getContainer()->get('knp_gaufrette.filesystem_map')->get('ekyna_image');

        if (!$input->getOption('no-interaction')) {
            /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
            $dialog = $this->getHelperSet()->get('dialog');

            $question = '<question>Are you sure you want to remove all images files ? Y/N</question>';
            $response = $dialog->askConfirmation($output, $question, false);
            if (!$response) {
                $output->writeln('Abort by user.');
                return;
            }
        }

        $output->write('Removing files ... ');

        $dirKeys = [];
        $keys = $filesystem->keys();
        foreach ($keys as $key) {
            if ($filesystem->getAdapter()->isDirectory($key)) {
                $paths = explode('/', $key);
                while (!empty($paths)) {
                    $dirKeys[] = implode('/', $paths);
                    array_pop($paths);
                }
            } else {
                $filesystem->delete($key);
            }
        }
        foreach ($dirKeys as $key) {
            $filesystem->delete($key);
        }

        $output->writeln('done.');
    }
}
