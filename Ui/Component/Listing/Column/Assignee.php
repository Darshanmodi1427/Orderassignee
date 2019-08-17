<?php

namespace Darsh\Orderattribute\Ui\Component\Listing\Column;

class Assignee extends \Magento\Ui\Component\Listing\Columns\Column {

    protected $moduleBlock;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Darsh\Orderattribute\Block\Adminhtml\Order\View\Tab\Custom $moduleBlock,
        array $components = [],
        array $data = []
    ){
        $this->moduleBlock = $moduleBlock;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {
                $item['assignee'] = $this->moduleBlock->getAdminUserName($item['assignee']); 
            }
        }

        return $dataSource;
    }
}