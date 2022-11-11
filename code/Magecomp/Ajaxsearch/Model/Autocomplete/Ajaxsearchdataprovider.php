<?php
namespace Magecomp\Ajaxsearch\Model\Autocomplete;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Search\Model\Autocomplete\DataProviderInterface;
use Magento\Search\Model\Autocomplete\ItemFactory;
use Magento\Search\Model\QueryFactory;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magecomp\Ajaxsearch\Helper\Data as AjaxHelper;

class Ajaxsearchdataprovider implements DataProviderInterface
{
    protected $queryFactory;
    protected $itemFactory;
    protected $productRepository;
    protected $searchCriteriaBuilder;
    protected $priceCurrency;
    protected $productHelper;
    protected $imageHelper;
    protected $layerResolver;
    protected $ajaxhelper;

    public function __construct(
        QueryFactory $queryFactory,
        ItemFactory $itemFactory,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PriceCurrencyInterface $priceCurrency,
        Image $imageHelper,
        LayerResolver $layerResolver,
        AjaxHelper $ajaxhelper
    )
    {
        $this->queryFactory = $queryFactory;
        $this->itemFactory  = $itemFactory;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->priceCurrency = $priceCurrency;
        $this->imageHelper = $imageHelper;
        $this->layerResolver = $layerResolver;
        $this->ajaxhelper = $ajaxhelper;
    }

    public function getItems()
    {

        $result     = [];
        $query      = $this->queryFactory->get()->getQueryText();
        $productIds = $this->searchProductsFullText($query);

        if ($productIds)
        {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $productIds, 'in')->create();
            $products = $this->productRepository->getList($searchCriteria);

            foreach ($products->getItems() as $product)
            {
                $image = $this->imageHelper->init($product, 'product_page_image_small')->getUrl();

                $resultItem = $this->itemFactory->create([
                    'title'             => $product->getName(),
                    'price'             => $this->priceCurrency->format($product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue(),false),
                    'special_price'     => $this->priceCurrency->format($product->getPriceInfo()->getPrice('special_price')->getAmount()->getValue(),false),
                    'has_special_price' => $product->getSpecialPrice() > 0 ? true : false,
                    'image'             => $image,
                    'url'               => $product->getProductUrl()
                ]);
                $result[]   = $resultItem;
            }
        }

        return $result;
    }

    protected function searchProductsFullText($query)
    {
        if($this->ajaxhelper->isEnabled()) :
            $this->layerResolver->create(LayerResolver::CATALOG_LAYER_SEARCH);
            $productCollection = $this->layerResolver->get()
                ->getProductCollection()
                ->setPageSize($this->ajaxhelper->getProductCount())
                ->addSearchFilter($query);

            $productIds = [];
            foreach ($productCollection as $product) {
                $productIds[] = $product->getId();
            }
            return $productIds;
        endif;
    }
}