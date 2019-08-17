<?php

namespace Darsh\Orderattribute\Block\Adminhtml\Order\View\Tab;

class Custom extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Template
     *
     * @var string
     */
    protected $_template = 'order/view/tab/custom.phtml';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;
    protected $userCollectionFactory;
    protected $orderRepository;
    protected $_userFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\User\Model\ResourceModel\User\CollectionFactory $userCollectionFactory,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\User\Model\UserFactory $userFactory,
        array $data = []
    ) {
        $this->coreRegistry          = $registry;
        $this->userCollectionFactory = $userCollectionFactory;
        $this->orderRepository       = $orderRepository;
        $this->_userFactory = $userFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Task Management');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Task Management');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        // For me, I wanted this tab to always show
        // You can play around with the ACL settings
        // to selectively show later if you want
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        // For me, I wanted this tab to always show
        // You can play around with conditions to
        // show the tab later
        return false;
    }

    /**
     * Get Tab Class
     *
     * @return string
     */
    public function getTabClass()
    {
        // I wanted mine to load via AJAX when it's selected
        // That's what this does
        return 'ajax only';
    }

    /**
     * Get Class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->getTabClass();
    }

    /**
     * Get Tab Url
     *
     * @return string
     */
    public function getTabUrl()
    {
        // customtab is a adminhtml router we're about to define
        // the full route can really be whatever you want
        return $this->getUrl('assignee/order/customTab', ['_current' => true]);
    }

    public function getAdminUsers()
    {
        $adminUsers = [];

        foreach ($this->userCollectionFactory->create() as $user) {
            $adminUsers[] = [
                'value' => $user->getId(),
                'label' => $user->getName(),
            ];
        }

        return $adminUsers;
    }

    public function getAdminUserName($userId)
    {
        $user = $this->_userFactory->create();
        $user->load($userId);                
        return $user->getName();
    }

    public function getCurrentOrder()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        if ($orderId) {
            return $this->orderRepository->get($orderId);
        }

        return;
    }

    public function getOrderAssigneeValue()
    {
        $order = $this->getCurrentOrder();

        if ($order) {
            return $order->getAssignee();
        }

        return '';
    }

    public function getOrderDuedateValue()
    {
        $order = $this->getCurrentOrder();

        if ($order) {
            return $order->getDueDate();
        }

        return '';
    }

    public function getUpdateInstructionUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        $params = array('order_id' => $orderId);

        return $url = $this->getUrl("assignee/order/updateAssignee", $params);
    }

}
