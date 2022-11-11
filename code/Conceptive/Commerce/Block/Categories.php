<?php

namespace Conceptive\Commerce\Block;

use \Magento\Backend\Block\Template;
use \Magento\Backend\Block\Template\Context;
use \Magento\Catalog\Helper\Category;

class Categories extends Template
{
    protected $_categoryHelper;

    public function __construct(
        Context $context,
        Category $categoryHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryHelper = $categoryHelper;
    }

    public function getCategoryCollection()
    {
        $categories = $this->_categoryHelper->getStoreCategories();
        return $categories;
    }
}
