<?php

namespace Conceptive\Banners\Model\ResourceModel\Summery;

use \Conceptive\Banners\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'summery_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Conceptive\Banners\Model\Summery', 'Conceptive\Banners\Model\ResourceModel\Summery');
        $this->_map['fields']['summery_id'] = 'main_table.summery_id';
    }
}
