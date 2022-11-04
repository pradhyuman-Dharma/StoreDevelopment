<?php

namespace Conceptive\Commerce\Block;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection;
use \Magento\Store\Model\StoreManagerInterface ;
use \Magento\Review\Model\ReviewFactory ;
use Magento\Catalog\Model\Product ;
use \Magento\Catalog\Helper\Image;

class ProductCollection extends \Magento\Framework\View\Element\Template
{
    protected $_productRepository;
    protected $_bestSellers;
    protected $_storeManager;
    protected $_reviewFactory;
    protected $_productModel;
    protected $imageHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        ReviewFactory $reviewFactory,
        Product $productModel,
        Image $imageHelper,
        Collection $bestSellers,
        array $data = []
    ) {
        $this->_productRepository = $productRepository;
        $this->_bestSellers = $bestSellers;
        $this->_storeManager = $storeManager;
        $this->_reviewFactory = $reviewFactory;
        $this->_productModel = $productModel;
        $this->imageHelper = $imageHelper;
        parent::__construct($context, $data);
    }


    public function getBestSellers()
    {
        $bestProducts = $this->_bestSellers->setPeriod('year')->setPageSize(4);
        return $bestProducts;
    }
    public function getImage($product)
    {
        $productDetail = $this->_productModel->load($product->getProductId());
        $image_url = $this->imageHelper->init($productDetail, 'product_page_image_small')->setImageFile($productDetail->getFile())->getUrl();
        return $image_url;
    }
    public function getProDetails($productId){
        $prodetail = $this->_productRepository->getById($productId);
        return $prodetail;
    }

    public function getUserLogin(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        return $customerSession;
    }

    // public function getRating()
    // {
    //     $collection = $this->getProductCollection();
    //     $rating = array();
    //     foreach ($collection as $product) {
    //         echo "<pre>"; print_r($product); exit;
    //         $rate = $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
    //         $ratingSummary = $product->getRatingSummary()->getRatingSummary();
    //         if($ratingSummary!=null){
    //             $rating[$product->getId()] = $ratingSummary;
    //         }
    //     }
    //     return $rating;

     
    // }

    // public function getRatingSummary($product)
    // {
    //     // echo "<pre>";
    //     // print_r($product);exit;
    //     $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    //     $reviewFactory = $objectManager->get(\Magento\Review\Model\ReviewFactory::class);
    //     $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
    //     $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
    //     $collection = $productCollection->create()
    //         ->addAttributeToSelect('*')
    //         ->load();
    //     $rating = array();
    //     // foreach ($collection as $product) {
    //         $reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
    //         $ratingSummary = $product->getRatingSummary()->getRatingSummary();
    //         if($ratingSummary!=null){
    //             $rating[$product->getId()] = $ratingSummary;
    //         }
    //     // }
    //     if($ratingSummary != null){
    //         return $rating;
    //     }
    //     return;
    // }  
    
}
