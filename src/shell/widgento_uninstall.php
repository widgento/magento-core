<?php

require_once 'abstract.php';

class Widgento_Shell_Uninstall extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     */
    public function run()
    {
        if (!$this->_args)
        {
            echo $this->usageHelp();
            return false;
        }

        foreach ($this->_args as $module => $key)
        {
            try 
            {
                Mage::dispatchEvent('widgento_core_uninstall', array('module' => $module));
                echo 'Module "'.$module.'" successfully uninstalled.'."\n";
            }
            catch (Exception $e) 
            {
                echo $e->getMessage()."\n";
                echo $e->getTraceAsString()."\n";
            } 
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f widgento_uninstall.php -- Widgento_Module

  Widgento_Module   Module name you are going to uninstall

USAGE;
    }
}

$shell = new Widgento_Shell_Uninstall();
$shell->run();
