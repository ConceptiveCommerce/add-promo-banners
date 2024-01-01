<?php

namespace Conceptive\Banners\Model\ResourceModel;

class Summery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
              
    protected function _construct()
    {
        $this->_init('banner_summery', 'summery_id');
    }
}
