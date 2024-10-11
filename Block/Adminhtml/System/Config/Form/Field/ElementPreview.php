<?php
/**
 * Venustheme
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Venustheme.com license that is
 * available through the world-wide-web at this URL:
 * http://www.venustheme.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Venustheme
 * @package    Ves_BaseWidget
 * @copyright  Copyright (c) 2014 Venustheme (http://www.venustheme.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
 */
namespace Ves\BaseWidget\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class ElementPreview extends Template implements RendererInterface
{
    protected $_storeManager;

    protected $_factoryElement;

    protected $_factoryCollection;

    protected $_backendData;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Backend\Helper\Data $backendData,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_factoryElement = $factoryElement;
        $this->_factoryCollection = $factoryCollection;
        $this->_backendData = $backendData;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getBaseMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

	/**
	 * @param  AbstractElement $element
	 * @return string
	 */
	public function render(AbstractElement $element)
	{
        $imageUrl = $element->getLabel();
        if($imageUrl){
            $imageUrl = $this->getBaseMediaUrl().$imageUrl;
            $html = '';
            $html .= '<div class="admin__field field system-heading element-preview with-note" style="text-align: center;padding: 10px;margin-bottom: 20px;margin-top: 20px;"><label class="label admin__field-label">&nbsp;</label>';
            $html .= '<div class="admin__field-control control"><img src="'.$imageUrl.'" class="img-responsive" alt="'.$element->getLabel().'"/></div>';
            $html .= '</div>';
            return $html;
        }
        return "";
	}
}
