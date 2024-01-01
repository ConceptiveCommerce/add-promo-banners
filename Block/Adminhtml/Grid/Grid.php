<?php

namespace Conceptive\Banners\Block\Adminhtml\Grid;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\ObjectManagerInterface;
use Conceptive\Banners\Model\ResourceModel\Summery\CollectionFactory as DemoCollection;
// also you can use Magento Default CollectionFactory
class Grid extends Extended
{
    protected $registry;
    protected $_objectManager = null;
    protected $demoFactory;
    protected $request;
    public function __construct(
        Context $context,
        Data $backendHelper,
        Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        ObjectManagerInterface $objectManager,
        DemoCollection $demoFactory,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->registry = $registry;
        $this->request = $request;
        $this->demoFactory = $demoFactory;
        parent::__construct($context, $backendHelper, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->setId('banner_id');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $bannerId = $this->request->getParam('banners_id');
        if ($bannerId) {
            $demo = $this->demoFactory->create()
                ->addFieldToSelect('*');
            $demo->addFieldToFilter(
                array('banner_id'),
                array(
                    array('like' => "%$bannerId%")
                )
            );
            $coll =  $this->request->getParam('daterange');
            if ($coll) {
                $datedata = explode("-", $coll);
                $fromdate1 = $datedata[0];
                $todate1 = $datedata[1];
                $fromdate = $datedata[0];
                $todate = $datedata[1];
                if (strtotime($fromdate1) == strtotime($todate1)) {
                    $demo->addFieldToFilter('banner_click_time', ['like' => date('Y-m-d', strtotime($fromdate))]);
                } else {
                    $demo->addFieldToFilter('banner_click_time', ['gteq' => date('Y-m-d H:i:s', strtotime($fromdate))]);
                    $demo->addFieldToFilter('banner_click_time', ['lteq' => date('Y-m-d H:i:s', strtotime($todate))]);
                }
            }

            $this->setCollection($demo);
            return parent::_prepareCollection();
        }
    }
    protected function _prepareColumns()
    {
        if ($this->request->getParam('banners_id')) {
            // $this->addColumn(
            //     'id',
            //     [
            //         'header_css_class' => 'a-center',
            //         'type' => 'checkbox',
            //         'name' => 'summery_id',
            //         'align' => 'center',
            //         'index' => 'id',
            //     ]
            // );
            $this->addColumn(
                'summery_id',
                [
                    'header' => __('ID'),
                    'type' => 'number',
                    'index' => 'summery_id',
                    'header_css_class' => 'col-id',
                    'column_css_class' => 'col-id',
                ]
            );
            // $this->addColumn(
            //     'banner_id',
            //     [
            //         'header' => __('Banner ID'),
            //         'type' => 'number',
            //         'index' => 'banner_id',
            //         'header_css_class' => 'col-id',
            //         'column_css_class' => 'col-id',
            //     ]
            // );
            $this->addColumn(
                'user_name',
                [
                    'header' => __('Customer Name'),
                    'type' => 'text',
                    'index' => 'user_name',
                    'header_css_class' => 'col-id',
                    'column_css_class' => 'col-id',
                ]
            );
            $this->addColumn(
                'email',
                [
                    'header' => __('Customer Email'),
                    'index' => 'email',
                    'class' => '',
                    'width' => '125px',
                ]
            );
            $this->addColumn(
                'contact',
                [
                    'header' => __('Contact'),
                    'type' => 'text',
                    'index' => 'contact',
                    'header_css_class' => 'col-id',
                    'column_css_class' => 'col-id',
                ]
            );
            $this->addColumn(
                'banner_click_time',
                [
                    'header' => __('Date'),
                    'index' => 'banner_click_time',
                    'type' => 'datetime',
                ]
            );

            $this->addExportType('banners/grid/exportCsv', __('CSV'));
            $this->addExportType('banners/grid/exportExcel', __('Excel XML'));

            return parent::_prepareColumns();
        }
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/*', ['_current' => true]);
    }
}
