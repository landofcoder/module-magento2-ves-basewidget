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
namespace Ves\BaseWidget\Block\Adminhtml;

class Cms extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

    protected $_cms_block_model;

    /**
     * @param \Magento\Backend\Block\Template\Context                $context
     * @param \Ves\BaseWidget\Model\Source\ListCms $cms_block_model,
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Ves\BaseWidget\Model\Source\ListCms $cms_block_model,
        array $data = []
    ) {
        $this->_cms_block_model = $cms_block_model;
        parent::__construct($context, $data);
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getCMSBlocks()
    {
        return $this->_cms_block_model->toOptionArray();
    }
}
