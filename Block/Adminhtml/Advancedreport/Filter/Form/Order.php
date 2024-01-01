<?php

namespace Conceptive\Banners\Block\Adminhtml\Advancedreport\Filter\Form;

class Order extends \Conceptive\Banners\Block\Adminhtml\Advancedreport\Filter\Form
{
    protected function _prepareForm()
    {
        $actionUrl = $this->getUrl($this->getFormActionUrl());
        $report_type = $this->getReportType();
        $report_types = array("statistics", "customergroup");
        $notin_report_types = array("customergroup", "producttype", "hourly", "weekday");
        $notshow_order_statuses = array("detailed", "itemsdetailed", "abandoned", "abandoneddetailed");
        $show_group_by = array("abandoned");
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Request\Http');
        $controlname = $request->getRouteName();


        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'filter_form',
                    'action' => $actionUrl,
                    'method' => 'get'
                ]
            ]
        );
        $htmlIdPrefix = 'order_report_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        if (($controlname == "banners" && $request->getParam('banners_id') != null)) {

            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Analytical Report')]);
        } else {
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('')]);
        }


        $statuses = $this->_objectManager->create('Magento\Sales\Model\Order\Config')->getStatuses();

        $values = array();
        foreach ($statuses as $code => $label) {
            if (false === strpos($code, 'pending')) {
                $values[] = array(
                    'label' => __($label),
                    'value' => $code
                );
            }
        }

        $fieldset->addField('store_ids', 'hidden', array(
            'name'  => 'store_ids'
        ));
        $fieldset->addField('filter_from', 'hidden', array(
            'name'  => 'filter_from'
        ));
        $fieldset->addField('filter_to', 'hidden', array(
            'name'  => 'filter_to'
        ));

        //Config date range
        $default_value = "May 25, 2016 - November 28, 2016";
        $filterData = $this->getFilterData();
        //Chnages
        //   $filter_from = "08/01/2022";
        //  $filter_to = "08/18/2022";

        if ($controlname == "banners") {
            if ($request->getParam('daterange') !== null) {
                $coll = $request->getParam('daterange');
                $datedata = explode("-", $coll);
                $fromdate1 = $datedata[0];
                $todate1 = $datedata[1];
                $filter_from = date('m/d/Y', strtotime($fromdate1));
                $filter_to = date('m/d/Y', strtotime($todate1));
            } else {
                $filter_from = date('m/01/Y');
                $filter_to = date('m/d/Y');
            }
        } else {
            if($filterData !== null){ 
                $filter_from = $filterData->getData("filter_from");
                $filter_to = $filterData->getData("filter_to");
            } else{
                $filter_from = date('m/01/Y');
                $filter_to = date('m/d/Y');
            }
        }

        //Check if empty filter from and filter to, set default from date and to date
        if (!$filter_from && !$filter_to) {
            if (in_array($report_type, $report_types)) {
                $cur_month = date("m");
                $cur_year = date("Y");
                $filter_from = $cur_month . "/01/" . $cur_year;
                $filter_to = date("m/d/Y");
            }
        }
        $filter_from_jstime = '';
        $filter_to_jstime = '';
        if ($filter_from && $filter_to) {
            $filter_from_jstime = strtotime($filter_from) * 1000;
            $filter_to_jstime = strtotime($filter_to) * 1000;

            $filter_from_obj = new \Zend_Date(strtotime($filter_from));
            $filter_to_obj = new \Zend_Date(strtotime($filter_to));

            $filter_from = $filter_from_obj->toString('MMMM dd, yyyy');
            $filter_to = $filter_to_obj->toString('MMMM dd, yyyy');
            $default_value = $filter_from . " - " . $filter_to;
        }
        if ($controlname == "banners") {
            if ($request->getParam('banners_id') != null) {

                $current_year = date('Y');
                $next_year = (int)$current_year + 1;
                $fieldset->addType('date_range', 'Conceptive\Banners\Block\Adminhtml\System\Config\Form\Field\DateRange');
                $fieldset->addField('reportrange', 'date_range', [
                    'label'         => __("Date Range"),
                    'name'          => 'reportrange',
                    'block_id'      => 'reportrange',
                    'required'      => true,
                    'default_value' => $default_value,
                    'min_date'      => '01/01/1975',
                    'max_date'      => '12/31/' . $next_year,
                    'start_date'    => $filter_from_jstime,
                    'end_date'      => $filter_to_jstime,
                    'target_from'   => '#order_report_filter_from',
                    'target_to'     => '#order_report_filter_to',
                    'field_style'   => 'background: #fff; cursor: pointer; padding: 10px 10px; border: 1px solid #ccc; width:300px ',
                    'label_style'   =>  '',
                ]);
                $fieldset->addField('showreport', 'button', [
                    'name'      => 'showreport',
                    'value'     => __('Show Report'),
                ], 'showreport');
            }
        } else {

            $current_year = date('Y');
            $next_year = (int)$current_year + 1;
            $fieldset->addType('date_range', 'Conceptive\Banners\Block\Adminhtml\System\Config\Form\Field\DateRange');
            $fieldset->addField('reportrange', 'date_range', [
                'label'         => __("Date Range"),
                'name'          => 'reportrange',
                'block_id'      => 'reportrange',
                'required'      => true,
                'default_value' => $default_value,
                'min_date'      => '01/01/1975',
                'max_date'      => '12/31/' . $next_year,
                'start_date'    => $filter_from_jstime,
                'end_date'      => $filter_to_jstime,
                'target_from'   => '#order_report_filter_from',
                'target_to'     => '#order_report_filter_to',
                'field_style'   => 'background: #fff; cursor: pointer; padding: 10px 10px; border: 1px solid #ccc; width:300px ',
                'label_style'   =>  '',
            ]);
        }


        if (in_array($report_type, $show_group_by)) {
            $fieldset->addField('group_by', 'select', [
                'name'      => 'group_by',
                'label'     =>  __('Show By'),
                'options'   => array(
                    'day' =>  __('Day'),
                    'week' =>  __('Week'),
                    'month' =>  __('Month'),
                    'quarter' =>  __('Quarter'),
                    'year' =>  __('Year'),
                ),
                'note'      =>  __('Show period time by option.'),
            ]);
        }

        if (!in_array($report_type, $notshow_order_statuses)) {
            $fieldset->addField('show_order_statuses', 'hidden', [
                'name'      => 'show_order_statuses',
                'label'     => __('Order Status'),
                'options'   => array(
                    '0' => __('Any'),
                    '1' => __('Specified'),
                ),
                'note'      => __('Applies to Any of the Specified Order Statuses'),
            ], 'to');

            $fieldset->addField('order_statuses', 'multiselect', [
                'name'      => 'order_statuses',
                'values'    => $values,
                'label'     => __('Status'),
                'display'   => 'none'
            ], 'show_order_statuses');

            // define field dependencies
            if ($this->getFieldVisibility('show_order_statuses') && $this->getFieldVisibility('order_statuses')) {
                $this->setChild(
                    'form_after',
                    $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
                        ->addFieldMap("{$htmlIdPrefix}show_order_statuses", 'show_order_statuses')
                        ->addFieldMap("{$htmlIdPrefix}order_statuses", 'order_statuses')
                        ->addFieldDependence('order_statuses', 'show_order_statuses', '1')

                );
            }
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
