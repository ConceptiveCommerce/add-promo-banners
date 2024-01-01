<?php
namespace Conceptive\Banners\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
        
class Banners extends Template
{
          
    protected $scopeConfig;
    protected $collectionFactory;
    protected $objectManager;
    protected $dateTime;
        
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Conceptive\Banners\Model\ResourceModel\Banners\CollectionFactory $collectionFactory,
        DateTime $dateTime,
        ObjectManagerInterface $objectManager
    ) {
        
        $this->scopeConfig = $context->getScopeConfig();
        $this->collectionFactory = $collectionFactory;
        $this->dateTime = $dateTime;
        $this->objectManager = $objectManager;
                
        parent::__construct($context);
    }
        
        
    public function getFrontBanners()
    {
                
            
        $collection = $this->collectionFactory->create()->addFieldToFilter('status', 1);
                
        /*
         * cehck for arguments,provided in block call
         */
        if ($ids_list = $this->getBannerBlockArguments()) {
            $collection->addFilter('banners_id', ['in' => $ids_list], 'public');
        }
                
                
        return $collection;
    }
                
        
    public function getBannerBlockArguments()
    {
            
        $list =  $this->getBannerList();
                
        $listArray = [];
                
        if ($list != '') {
            $listArray = explode(',', $list);
        }
                
        return $listArray;
    }
        
    public function getMediaDirectoryUrl()
    {
            
        $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
        ->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            
        return $media_dir;
    }

    public function getTodayDate(){
        return $this->dateTime;
    }
}
