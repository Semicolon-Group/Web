<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            new BaseBundle\BaseBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new MemberBundle\MemberBundle(),
            new NewsFeedBundle\NewsFeedBundle(),
            new MatchBundle\MatchBundle(),
            new EventBundle\EventBundle(),
            new ExperienceBundle\ExperienceBundle(),
            new RecommandationBundle\RecommandationBundle(),
            new Business\HomeBundle\BusinessHomeBundle(),
            new Business\EventBundle\BusinessEventBundle(),
            new Business\AdvertBundle\BusinessAdvertBundle(),
            new Business\ChartBundle\BusinessChartBundle(),
            new Admin\HomeBundle\AdminHomeBundle(),
            new Admin\MemberBundle\AdminMemberBundle(),
            new Admin\BusinessBundle\AdminBusinessBundle(),
            new Admin\ChartBundle\AdminChartBundle(),
            new Business\FOSSecurityBundle\BusinessFOSSecurityBundle(),
            new Admin\FOSSecurityBundle\AdminFOSSecurityBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Admin\QuestionBundle\AdminQuestionBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
