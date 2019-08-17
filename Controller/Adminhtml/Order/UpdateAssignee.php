<?php

namespace Darsh\Orderattribute\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;

class UpdateAssignee extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $oABlock;
    protected $_date;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Darsh\Orderattribute\Block\Adminhtml\Order\View\Tab\Custom $oABlock,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->oABlock           = $oABlock;
        $this->_date             = $date;
        parent::__construct($context);
    }

    public function execute()
    {
        $order  = $this->oABlock->getCurrentOrder();
        $params = $this->getRequest()->getParams();

        $due_date = $params['due_date'];
        $assignee = $params['assignee'];

        if ($due_date != '') {
            $newDate = $this->_date->date($due_date)->format('d-m-Y H:i:s');
            $order->setDueDate($newDate)->save();
        }

        if ($assignee != '') {
            $order->setAssignee($assignee)->save();
        }

        $pageFactory = $this->resultPageFactory->create();

        echo $response = $pageFactory->getLayout()->createBlock('\Darsh\Orderattribute\Block\Adminhtml\Order\View\Tab\Custom')
            ->setTemplate('Darsh_Orderattribute::order/view/tab/custom.phtml')->toHtml();

    }
}
