<?php

namespace Conceptive\Banners\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $resultJsonFactory;

    protected $_resource;

    protected $remoteAddress;

    protected $customerSession;

    protected $dateTime;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        RemoteAddress $remoteAddress,
        Session $customerSession,
        DateTime $dateTime,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    ) {

        $this->resultPageFactory = $resultPageFactory;
        $this->_resource = $resource;
        $this->remoteAddress = $remoteAddress;
        $this->customerSession = $customerSession;
        $this->dateTime = $dateTime;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }


    public function execute()
    {
        $bannerId = $this->getRequest()->getParam('bannerId');
        $visitorIP =  $this->remoteAddress->getRemoteAddress();
        $fullname = "Guest";
        $email = " ";
        $click_count = 1;
        $contact = NULL;
        $date = $this->dateTime->gmtDate();

        if ($this->customerSession->isLoggedIn()) {
            $fullname = $this->customerSession->getCustomer()->getName();  // get  Full Name
            $email =  $this->customerSession->getCustomer()->getEmail(); // get Email
        }

        $connection = $this->_resource->getConnection();

        $themeTable = $this->_resource->getTableName('banner_summery');

        $select_sql = "Select * FROM " . $themeTable;
        // fetch result
        $results = $connection->fetchAll($select_sql);
        // print result
        // print_r($results);
        $bannerIdArray = array();
        $nameArray = array();
        $emailArray = array();
        $ipArray = array();
        $matchBannerId = array();
        $matchVisitorBannerId = array();
        foreach ($results as $values) {
            foreach ($values as $key => $value) {
                if ($key == "visitor_ip") {
                    $ipArray[] = $value;
                }
                if ($key == "email") {
                    $emailArray[] = $value;
                }
                if ($key == "banner_id") {
                    $bannerIdArray[] = $value;
                }
                if ($key == "user_name") {
                    $nameArray[] = $value;
                }
                if ($value == $email) {
                    $matchBannerId[] = $values['banner_id'];
                }
                if (($value == $visitorIP) && ($values['user_name'] == "Guest")) {
                    $matchVisitorBannerId[] = $values['banner_id'];
                }
                if ($value == "Guest") {
                    continue;
                }
            }
        }

        if (!(in_array($email, $emailArray) && in_array($fullname, $nameArray) && in_array($bannerId, $matchBannerId)) && ($fullname != "Guest")) {
            $sql = "INSERT INTO " . $themeTable . "(banner_id, link_click_count, user_name, email, contact, banner_click_time, visitor_ip) VALUES ('$bannerId', '$click_count','$fullname','$email','$contact','$date','$visitorIP')";
            $connection->query($sql);
        }
        if (!(in_array($visitorIP, $ipArray) && in_array($bannerId, $matchVisitorBannerId))  && $fullname == "Guest") {
            $sql = "INSERT INTO " . $themeTable . "(banner_id, link_click_count, user_name, email, contact, banner_click_time, visitor_ip) VALUES ('$bannerId', '$click_count','$fullname','$email','$contact','$date','$visitorIP')";
            $connection->query($sql);
        }
        return;
    }
}
