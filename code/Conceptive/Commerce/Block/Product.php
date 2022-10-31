<?php
namespace Conceptive\Commerce\Block;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory ;

class Product extends \Magento\Framework\View\Element\Template
{    
    protected $_productCollectionFactory;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        CollectionFactory $productCollectionFactory,        
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;    
        parent::__construct($context, $data);
    }
    
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status', 1);
        $collection->setPageSize(4); // fetching only 4 products
        return $collection;
    }
}
?>
