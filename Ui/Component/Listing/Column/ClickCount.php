<?php

namespace Conceptive\Banners\Ui\Component\Listing\Column;

class ClickCount extends \Magento\Ui\Component\Listing\Columns\Column
{

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        //  echo "<pre>"; print_r($dataSource);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('banner_summery'); //gives table name with prefix

        //Select Data from table
        // gives associated array, table fields as key in array.
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as &$item) {
                $sql = "Select  SUM(link_click_count) FROM " . $tableName . " where banner_id=" . $item['banners_id'];
                $result = $connection->fetchAll($sql);
                //$item['yourcolumn'] is column name
                if ($result[0]['SUM(link_click_count)'] != null) {
                    $item['total_clicks'] =  $result[0]['SUM(link_click_count)']; //Here you can do anything with actual data
                } else {
                    $item['total_clicks'] = "0";
                }
            }
        }

        return $dataSource;
    }
}
