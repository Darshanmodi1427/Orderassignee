<?php

namespace Darsh\Orderattribute\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Setup\SalesSetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Sales setup factory
     *
     * @var SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * Init
     *
     * @param SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

        /**
         * Remove previous attributes
         */
        $attributes = ['due_date', 'assignee'];
        foreach ($attributes as $attr_to_remove) {
            $salesSetup->removeAttribute(\Magento\Sales\Model\Order::ENTITY, $attr_to_remove);

        }

        /**
         * Add 'NEW_ATTRIBUTE' attributes for order
         */
        $options = ['type' => 'varchar', 'nullable' => false, 'comment' => 'Assignee'];
        $salesSetup->addAttribute('order', 'assignee', $options);

        $options1 = ['type' => 'datetime', 'nullable' => false, 'comment' => 'Due Date'];
        $salesSetup->addAttribute('order', 'due_date', $options1);

    }
}
