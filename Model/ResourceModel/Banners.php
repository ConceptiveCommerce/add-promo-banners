<?php

namespace Conceptive\Banners\Model\ResourceModel;

class Banners extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
            
    protected function _construct()
    {
        $this->_init('conceptive_banners', 'banners_id');
    }
}
