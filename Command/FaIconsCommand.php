<?php

namespace Ekyna\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FaIconsCommand
 * @package Ekyna\Bundle\CoreBundle\Command
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FaIconsCommand extends ContainerAwareCommand
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var array
     */
    private $exclude = ['fa', 'font-awesome'];


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ekyna:core:fa-icons')
            ->setDescription('Build the CoreBundle font awesome icons constants class.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->root = dirname($this->getContainer()->getParameter('kernel.root_dir'));

        $iconNames = $this->gatherIconsNames();

        $content = $this->dumpConstantFile($iconNames, $output);

        $this->writeConstantFile($content, $output);
    }

    /**
     * Gather the icon names.
     *
     * @return array
     */
    private function gatherIconsNames()
    {
        $path = $this->root . '/bower_components/font-awesome/less/icons.less';

        $handle = fopen($path, 'r');
        if (!$handle) {
            throw new \RuntimeException("Failed to open $path.");
        }

        $icons = [];

        while (false !== $line = fgets($handle)) {
            if (preg_match('~\{fa-css-prefix\}-([a-z-]+):before~', $line, $matches)) {
                $icons[] = $matches[1];
            }
        }

        return array_diff($icons, $this->exclude);
    }

    /**
     * Dumps the constants file.
     *
     * @param array           $iconNames
     * @param OutputInterface $output
     *
     * @return null|string
     */
    private function dumpConstantFile(array $iconNames, OutputInterface $output)
    {
        if (empty($iconNames)) {
            $output->writeln("Abort as no icon found.");

            return null;
        }

        $content = <<<EOT
<?php

namespace Ekyna\Bundle\CoreBundle\Model;

use Ekyna\Bundle\ResourceBundle\Model\AbstractConstants;

/**
 * Class FAIcons
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class FAIcons extends AbstractConstants
{
    private static \$config = {$this->generateConfig($iconNames)};
    
    /**
     * @inheritDoc
     */
    public static function getConfig()
    {
        return static::\$config;
    }
}

EOT;

        return $content;
    }

    /**
     * Generates the icon constants class's config.
     *
     * @param array $iconNames
     *
     * @return string
     */
    private function generateConfig(array $iconNames)
    {
        $output = "[\n";

        foreach ($iconNames as $name) {
            $output .= "        '$name' => ['{$this->humanize($name)}'],\n";
        }

        $output .= "    ]";

        return $output;
    }

    /**
     * Makes the icon name human readable.
     *
     * @param string $text
     *
     * @return string
     */
    private function humanize($text)
    {
        return ucfirst(trim(str_replace('-', ' ', $text)));
    }

    /**
     * Writes the icon constants class file.
     *
     * @param string          $content
     * @param OutputInterface $output
     */
    private function writeConstantFile($content, OutputInterface $output)
    {
        if (empty($content)) {
            $output->writeln("Abort as empty content.");

            return;
        }

        $path = $this->root . '/src/Ekyna/Bundle/CoreBundle/Model/FAIcons.php';

        $filesystem = new Filesystem();

        if (file_exists($path)) {
            $output->writeln("Backing up the old constant file.");
            $filesystem->rename($path, $path . '.backup', true);
        }

        $output->writeln("Writing the new constant file.");
        $filesystem->dumpFile($path, $content);
    }
}

