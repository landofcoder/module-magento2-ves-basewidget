<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ves\BaseWidget\Controller\Adminhtml\Widget;

class InstanceSave extends \Magento\Widget\Controller\Adminhtml\Widget\Instance\Save
{
    /**
     * Save action
     *
     * @return void
     */
    public function execute()
    {
        $widgetInstance = $this->_initWidgetInstance();
        if (!$widgetInstance) {
            $this->_redirect('adminhtml/*/');
            return;
        }
        $params = $this->getRequest()->getPost('parameters');
        $params = $this->getWidgetDeclaration($params);

        $widgetInstance->setTitle(
            $this->getRequest()->getPost('title')
        )->setStoreIds(
            $this->getRequest()->getPost('store_ids', [0])
        )->setSortOrder(
            $this->getRequest()->getPost('sort_order', 0)
        )->setPageGroups(
            $this->getRequest()->getPost('widget_instance')
        )->setWidgetParameters(
            $params
        );
        try {
            $widgetInstance->save();
            $this->messageManager->addSuccess(__('The widget instance has been saved.'));
            if ($this->getRequest()->getParam('back', false)) {
                $this->_redirect(
                    'adminhtml/*/edit',
                    ['instance_id' => $widgetInstance->getId(), '_current' => true]
                );
            } else {
                $this->_redirect('adminhtml/*/');
            }
            return;
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            $this->_logger->critical($exception);
            $this->_redirect('adminhtml/*/edit', ['_current' => true]);
            return;
        }
        $this->_redirect('adminhtml/*/');
        return;
    }

    /**
     * Return widget presentation code in WYSIWYG editor
     *
     * @param array $params Pre-configured Widget Params
     * @return string Widget directive ready to parse
     * @api
     */
    public function getWidgetDeclaration($params = [])
    {
        $field_pattern = ["pretext","pretext_html","shortcode","html","box_text","raw_html","content","tabs","latestmod_desc","custom_css","block_params","link","href","url"];
        //$widget_types = ["Ves\BaseWidget\Block\Widget\Accordionbg", "Ves\BaseWidget\Block\Widget\Accordion"];

        foreach ($params as $k => $value) {
            if(0 < strpos($k, 'class') || 0 < strpos($k, 'Class')) {
                continue;
            }
            // Retrieve default option value if pre-configured
            if(is_array($params[$k]) || !base64_decode($params[$k], true)) {
                if(in_array($k, $field_pattern) || preg_match("/^tabs(.*)/", $k) || preg_match("/^link(.*)/", $k) || preg_match("/^href(.*)/", $k) || preg_match("/^html_(.*)/", $k) || preg_match("/^content_(.*)/", $k)) {
                    if(is_array($params[$k])){
                        $params[$k] = base64_encode(serialize($params[$k]));
                    }elseif(!$this->isBase64Encoded($params[$k])){
                        $params[$k] = base64_encode($params[$k]);
                    }
                }
            }

        }

        return $params;
    }

    /**
     * is based 64 encoded
     *
     * @param mixed|string $data
     * @return bool
     */
    public function isBase64Encoded($data)
    {
        if(base64_encode($data) === $data) return false;
        if(base64_encode(base64_decode($data)) === $data){
            return true;
        }
        if (!preg_match('~[^0-9a-zA-Z+/=]~', $data)) {
            $check = str_split(base64_decode($data));
            $x = 0;
            foreach ($check as $char) if (ord($char) > 126) $x++;
            if (count($check) > 0 && $x/count($check)*100 < 30) return true;
        }
        $decoded = base64_decode($data);
        // Check if there are valid base64 characters
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $data)) return false;
        // if string returned contains not printable chars
        if (0 < preg_match('/((?![[:graph:]])(?!\s)(?!\p{L}))./', $decoded, $matched)) return false;
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) return false;


        return false;
    }
}
