<?php
namespace Darsh\Orderattribute\Plugin;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;

class SalesOrderCustomColumn
{
    private $messageManager;
    private $collection;
    protected $authSession;

    public function __construct(MessageManager $messageManager,
        SalesOrderGridCollection $collection,
        \Magento\Backend\Model\Auth\Session $authSession
    ) {

        $this->messageManager = $messageManager;
        $this->collection     = $collection;
        $this->authSession = $authSession;
    }

    public function aroundGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        \Closure $proceed,
        $requestName
    ) {
        $result = $proceed($requestName);
        if ($requestName == 'sales_order_grid_data_source') {
            if ($result instanceof $this->collection) {
                $select = $this->collection->getSelect();
                $select->joinLeft(
                    ["secondTable" => $this->collection->getTable("sales_order")],
                    'main_table.increment_id = secondTable.increment_id',
                    array('assignee','due_date')
                );
                $currentUserData = $this->authSession->getUser()->getData();
                $roleData = $this->authSession->getUser()->getRole()->getData();
                
                if($roleData['role_id'] != 1){                    
                    //$select->where("secondTable.due_date <= CURDATE() AND secondTable.assignee = ?",$currentUserData['user_id']);
                    $select->where("secondTable.assignee = ?",$currentUserData['user_id']);
                }
                
                return $this->collection;
            }
        }
        return $result;
    }
}
