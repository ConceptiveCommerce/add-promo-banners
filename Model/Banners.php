<?php

namespace Conceptive\Banners\Model;

class Banners extends \Magento\Framework\Model\AbstractModel
{
        
        
    protected function _construct()
    {
        $this->_init('Conceptive\Banners\Model\ResourceModel\Banners');
    }
        
        
    public function getAvailableStatuses()
    {
                
                
        $availableOptions = ['1' => 'Enable',
                          '0' => 'Disable'];
                
        return $availableOptions;
    }
}
