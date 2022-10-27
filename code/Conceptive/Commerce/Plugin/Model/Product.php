<?php

namespace Conceptive\Commerce\Plugin\Model;

/**
 * Plugin for \Magento\Catalog\Model\Product
 */
class Product
{
    /**
     * Retrieve media gallery images
     *
     * @return \Magento\Framework\Data\Collection
     */
    public function afterGetMediaGalleryImages(\Magento\Catalog\Model\Product $product, $images)
    {
        $mainImage = null;
        foreach ($images  as $key => $image) {
            if ($product->getImage() == $image->getFile()) {
                $mainImage = $image;
                break;
            }
        }
        $images->clear();
        if ($mainImage) {
            $images->addItem($mainImage);
        }
        return $images;
    }
}