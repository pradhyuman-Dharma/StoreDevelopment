<?php

namespace Conceptive\Commerce\Block;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection;
use \Magento\Store\Model\StoreManagerInterface ;
use \Magento\Review\Model\ReviewFactory ;

class Product extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_bestSellers;
    protected $_storeManager;
    protected $_reviewFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        // Factory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        ReviewFactory $reviewFactory,
        Collection $bestSellers,
        array $data = []
    ) {
        // $this->_productCollectionFactory = $productCollectionFactory;
        $this->_bestSellers = $bestSellers;
        $this->_storeManager = $storeManager;
        $this->_reviewFactory = $reviewFactory;
        parent::__construct($context, $data);
    }


    public function getProductCollection()
    {
        $bestProducts = $this->_bestSellers->setPeriod('year')->setPageSize(4);
        //$collection->setPeriod('month');
        //$collection->setPeriod('day');

        // foreach ($this->_bestSellers as $item) {
        //     // echo "<pre>";  print_r($item->getData());
        // }
        return $bestProducts;
    }

    public function getRating()
    {
        $collection = $this->getProductCollection();
    //     echo "<pre>";
    //     foreach($collection as $key):
    //     print_r($key);
    // endforeach;
    //     exit;
       // return $collection;
        $rating = array();
        foreach ($collection as $product) {
            echo "<pre>"; print_r($product); exit;
            $rate = $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
            $ratingSummary = $product->getRatingSummary()->getRatingSummary();
            if($ratingSummary!=null){
                $rating[$product->getId()] = $ratingSummary;
            }
        }
        return $rating;

     
    }

    public function getRatingSummary()
    {
    
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $reviewFactory = $objectManager->get(\Magento\Review\Model\ReviewFactory::class);
        $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $productCollection->create()
            ->addAttributeToSelect('*')
            ->load();
        $rating = array();
        foreach ($collection as $product) {
            $reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
            $ratingSummary = $product->getRatingSummary()->getRatingSummary();
            if($ratingSummary!=null){
                $rating[$product->getId()] = $ratingSummary;
            }
        }
        return $rating;
    }  
    
}
