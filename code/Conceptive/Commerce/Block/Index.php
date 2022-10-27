<?php

namespace Conceptive\Commerce\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template
{

    /**
     * Hello constructor.
     * @param Template\Context $context
     * @param array $data
     */

    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    // public function AllCars(){
    //     return $this->collection;
    // }

    public function getAddCarPostUrl(){
        return $this->getUrl('helloworld/car/add');
    }
}
