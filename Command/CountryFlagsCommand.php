<?php

namespace Ekyna\Bundle\CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Intl;

/**
 * Class CountryFlagsCommand
 * @package Ekyna\Bundle\CoreBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @see     https://github.com/madebybowtie/FlagKit
 */
class CountryFlagsCommand extends Command
{
    const DIST_FLAG = 'https://github.com/madebybowtie/FlagKit/raw/master/Assets/PNG/%s.png?raw=true';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('ekyna:core:country-flags')
            ->setDescription("Downloads the country flags");
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lessPath = dirname(__DIR__) . "/Resources/private/less/flags.less";
        $pngPath = dirname(__DIR__) . "/Resources/private/img/flags.png";

        $width = 21;
        $height = 15;
        $columns = $rows = 16;

        $png = imagecreatetruecolor($width * $columns, $height * $rows);

        $less = ".country-flag {\n";
        $less .= "    background: url('/bundles/ekynacore/img/flags.png') no-repeat 0 0 scroll;\n\n";
        $less .= "    display: inline-block;\n";
        $less .= "    height: 15px;\n";
        $less .= "    width: 21px;\n";


        $countries = Intl::getRegionBundle()->getCountryNames();
        $failures = [];

        $progressBar = new ProgressBar($output, count($countries));
        $progressBar->setMessage('Start');
        $progressBar->start();

        $flag = $this->openFlag('none');
        imagecopy($png, $flag, 0, 0, 0, 0, $width, $height);
        imagedestroy($flag);

        $c = 1;
        $r = 0;
        foreach ($countries as $code => $country) {
            $progressBar->setMessage($country);

            $x = $c * $width;
            $y = $r * $height;

            if ($flag = $this->openFlag($code)) {
                imagecopy($png, $flag, $x, $y, 0, 0, $width, $height);
                imagedestroy($flag);
            } else {
                $failures[$code] = $country;
            }

            $less .= sprintf(
                "    &.%s { background-position: %s %s; }\n",
                strtolower($code),
                $x > 0 ? "-{$x}px" : "0",
                $y > 0 ? "-{$y}px" : "0"
            );

            $c++;
            if ($c > $columns - 1) {
                $c = 0;
                $r++;
            }

            $progressBar->advance();
        }

        $less .= "}\n";

        $progressBar->finish();
        $output->writeln('');

        // LESS
        $output->write('Writing LESS file ... ');
        if (@file_put_contents($lessPath, $less)) {
            $output->writeln('<info>done</info>');
        } else {
            $output->writeln('<error>failure</error>');
        }
        $output->writeln('');

        // PNG
        $output->write('Writing PNG file ... ');
        if (imagepng($png, $pngPath, 0, null)) {
            $output->writeln('<info>done</info>');
        } else {
            $output->writeln('<error>failure</error>');
        }
        $output->writeln('');

        imagedestroy($png);

        if (empty($failures)) {
            return;
        }

        $output->writeln('Failures:');
        foreach ($failures as $code => $country) {
            $output->writeln(sprintf('- [%s] %s', $code, $country));
        }
    }

    private function openFlag($code)
    {
        if ($flag = @imagecreatefrompng(sprintf(self::DIST_FLAG, $code))) {
            return $flag;
        }

        if ($flag = @imagecreatefrompng(dirname(__DIR__) . "/Resources/private/flags/$code.png")) {
            return $flag;
        }

        return false;
    }
}
