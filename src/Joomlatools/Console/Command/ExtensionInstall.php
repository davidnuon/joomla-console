<?php
/**
 * @copyright	Copyright (C) 2007 - 2015 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		Mozilla Public License, version 2.0
 * @link		http://github.com/joomlatools/joomla-console for the canonical source repository
 */

namespace Joomlatools\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Joomlatools\Console\Joomla\Bootstrapper;

class ExtensionInstall extends Site\AbstractSite
{
    protected $extension = array();

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('extension:install')
            ->setDescription('Install extensions into a site')
            ->addArgument(
                'extension',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'A list of extensions to install to the site using discover install. Use \'all\' to install all discovered extensions.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->extension = $input->getArgument('extension');

        $this->check($input, $output);
        $this->install($input, $output);
    }

    public function check(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->target_dir)) {
            throw new \RuntimeException(sprintf('Site not found: %s', $this->site));
        }
    }

    public function install(InputInterface $input, OutputInterface $output)
    {
        $app = Bootstrapper::getApplication($this->target_dir);
        $db  = \JFactory::getDbo();

        // Output buffer is used as a guard against Joomla including ._ files when searching for adapters
        // See: http://kadin.sdf-us.org/weblog/technology/software/deleting-dot-underscore-files.html
        ob_start();

        $installer = $app->getInstaller();
        $installer->discover();

        require_once $app->getPath().'/administrator/components/com_installer/models/discover.php';

        $model = new \InstallerModelDiscover();
        $model->discover();

        $results = $model->getItems();

        $install = array();

        foreach ($results as $result)
        {
            if ($this->extension == $result->element && in_array($result->element, array('com_extman', 'koowa'))) {
                array_unshift($install, $result->extension_id);
            }

            if ($this->extension == 'all' || in_array(substr($result->element, 4), $this->extension) || in_array($result->element, $this->extension)) {
                $install[$result->element] = $result->extension_id;
            }

            if (in_array($result->extension_id, $install) && $result->type == 'plugin') {
                $plugins[$result->element] = $result->extension_id;
            }
        }

        ob_end_clean();

        $install = array_unique($install);

        foreach ($install as $element => $extension_id)
        {
            try
            {
                $installer->discover_install($extension_id);

                if (in_array($extension_id, $plugins))
                {
                    $sql = "UPDATE `#__extensions` SET `enabled` = 1 WHERE `extension_id` = '$extension_id'";

                    $db->setQuery($sql);
                    $db->execute();

                    switch ($element)
                    {
                        case 'com_extman':
                            if(class_exists('Koowa') && !class_exists('ComExtmanDatabaseRowExtension')) {
                                \KObjectManager::getInstance()->getObject('com://admin/extman.database.row.extension');
                            }
                            break;
                        case 'koowa':
                            $path = JPATH_PLUGINS . '/system/koowa/koowa.php';

                            if (!file_exists($path)) {
                                return;
                            }

                            require_once $path;

                            if (class_exists('\PlgSystemKoowa'))
                            {
                                $dispatcher = \JEventDispatcher::getInstance();
                                new \PlgSystemKoowa($dispatcher, (array)\JPLuginHelper::getPLugin('system', 'koowa'));
                            }
                            break;
                    }
                }
            }
            catch (\Exception $e) {
                $output->writeln("<info>Caught exception during install: " . $e->getMessage() . "</info>\n");
            }
        }
    }
}