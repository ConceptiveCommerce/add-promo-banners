<?php

namespace Conceptive\Banners\Model\Banners\Source;

class Position implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'Homepage', 'label' => __('Homepage')],
            ['value' => 'Above Cart', 'label' => __('Above Cart')],
            ['value' => 'Cart Page(Bottom)', 'label' => __('Cart Page(Bottom)')],
            ['value' => 'Sidebar Main', 'label' => __('Sidebar Main')],
            ['value' => 'Product Page(Top)', 'label' => __('Product Page(Top)')],
            ['value' => 'Product Page(Bottom)', 'label' => __('Product Page(Bottom)')],
            // ['value' => 'Product Page(Below Cart Button)', 'label' => __('Product Page(Below Cart Button)')],
            ['value' => 'Category Page(Top)', 'label' => __('Category Page(Top)')],
            ['value' => 'Category Page(Bottom)', 'label' => __('Category Page(Bottom)')],
            // ['value' => 'catPageBelowAddToCartBtn', 'label' => __('Category Page(Below Add to Cart Button)')],
        ];
    }
}