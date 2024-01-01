<?php

namespace Conceptive\Banners\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\View\Result\PageFactory;

class ExportCsv extends Action
{
    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * ExportCsv constructor.
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        PageFactory $resultPageFactory
    ) {
        $this->fileFactory = $fileFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $fileName   = "banner_summery.csv";
        $grid = $resultPage->getLayout()->createBlock('Conceptive\Banners\Block\Adminhtml\Grid\Csv');
        return $this->fileFactory->create($fileName, $grid->getCsvFile(), DirectoryList::VAR_DIR);
    }
}
