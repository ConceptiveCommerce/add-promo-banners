<?php



namespace Conceptive\Banners\Block\Adminhtml\Banners;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAndContinueButton
 */
class SaveDate extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Show Report'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();  
        $request = $objectManager->get('Magento\Framework\App\Request\Http');  
        $bannerId = $request->getParam('banners_id');

        return $this->getUrl("banners/index/edit/banners_id/$bannerId/");
        // return $this->getUrl('*/*/*');
    }
}
