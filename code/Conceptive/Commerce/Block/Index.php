<?php

namespace Conceptive\Commerce\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;

class Index extends Template
{

    protected $registry;

    protected $product;

    /**
     * Hello constructor.
     * @param Template\Context $context
     * @param array $data
     */

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        Product $product,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->product = $product;
    }

    public function getCurrentProductId(){
        return $this->registry->registry('current_product')->getId();
    }


    public function getCurrentProduct(){
        $product_id = $this->getCurrentProductId();
        return $this->product->load($product_id);
    }


}
