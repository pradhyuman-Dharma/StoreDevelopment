<?php

namespace Conceptive\Commerce\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Index extends Template
{

    protected $registry;

    protected $product;

    protected $_bestSellers;

    protected $configurable;

    protected $configurableProd;

    /**
     * Hello constructor.
     * @param Template\Context $context
     * @param array $data
     */

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        Product $product,
        Collection $bestSellers,
        Configurable $configurable,
        ProductRepositoryInterface $configurableProd,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->product = $product;
        $this->_bestSellers = $bestSellers;
        $this->configurable = $configurable;
        $this->configurableProd = $configurableProd;
    }

    public function getCurrentProductId(){
        return $this->registry->registry('current_product')->getId();
    }


    public function getCurrentProduct(){
        $product_id = $this->getCurrentProductId();
        return $this->product->load($product_id);
    }

    public function getProduct($product_id){
        return $this->product->load($product_id);
    }

    public function getBestSellerProductsCollection()
    {
        $bestProducts = $this->_bestSellers->setPeriod('year')->setPageSize(4);
        return $bestProducts;
    }

    public function getConfigurableProdId($productId){
        $productData = $this->configurable->getParentIdsByChild($productId);
        return $productData;
    }

    public function getConfigurableProd($productId){
        $product = $this->configurableProd->getById($productId);
        return $product;
    }
}
