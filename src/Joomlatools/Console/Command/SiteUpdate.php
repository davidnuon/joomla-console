<?php
/**
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		Mozilla Public License, version 2.0
 * @link		http://github.com/joomlatools/joomla-console for the canonical source repository
 */

namespace Joomlatools\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Joomlatools\Console\Joomla\Bootstrapper;

class SiteUpdate extends SiteAbstract
{
    /**
     * Joomla version to update to
     *
     * @var string
     */
    protected $latestversion;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('site:update')
            ->setDescription('Update a site to the latest stable Joomla version in the same branch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->check($input, $output);

    }

    public function check(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->target_dir)) {
            throw new \RuntimeException(sprintf('Site not found: %s', $this->site));
        }

        $app = Bootstrapper::getApplication($this->target_dir);

        $branch = substr(JVERSION, 0, 1);
        if (is_numeric($branch)) {
            $versions = new Versions();
            $this->latestversion = $versions->getLatestRelease($branch);

            if(version_compare(JVERSION, $this->latestversion, '>=')) {
                throw new \RuntimeException(sprintf('Site already updated to latest version: %s', JVERSION));
            }
        }
        else {
            throw new \RuntimeException(sprintf('invalid site version: %s', JVERSION));
        }

        echo '<pre>'.print_r($this->latestversion,true).'</pre>';
    }
}