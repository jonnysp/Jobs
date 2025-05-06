<?php


namespace Jonnysp\Jobs\ContaoManager;

use Jonnysp\Jobs\JonnyspJobs;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;


class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(JonnyspJobs::class)
                ->setLoadAfter([ContaoCoreBundle::class])
                ->setReplace(['jobs']),
        ];
    }
}
