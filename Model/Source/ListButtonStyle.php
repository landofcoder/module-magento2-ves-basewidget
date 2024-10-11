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
 * @copyright  Copyright (c) 2015 Venustheme (http://www.venustheme.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
 */
namespace Ves\BaseWidget\Model\Source;

class ListButtonStyle implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
                  array('value' => "default", 'label'=>__('Default')),
                  array('value' => "primary", 'label'=>__('Primary')),
                  array('value' => "success", 'label'=>__('Success')),
                  array('value' => "info", 'label'=>__('Info')),
                  array('value' => "warning", 'label'=>__('Warning')),
                  array('value' => "danger", 'label'=>__('Danger')),
                  array('value' => "link", 'label'=>__('Link')),
                  array('value' => "dark", 'label'=>__('Dark')),
                  array('value' => "light", 'label'=>__('Light')),
                  array('value' => "secondary", 'label'=>__('Secondary'))
                  );
    }
}
