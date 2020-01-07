<?php

namespace FoxChapel\Catalog\Block\Widget;

use Magento\Catalog\Block\Product\Widget\NewWidget;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Catalog\Model\Product;

class NewProductWidget extends NewWidget
{

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    /**
     * NewProductWidget constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param array $data
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        EncoderInterface $urlEncoder,
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $data,
            $serializer
        );
        $this->urlEncoder = $urlEncoder;
    }

    /**
     * To get add to cart params
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($url),
            ]
        ];
    }

    /**
     * To get add to cart url for a product
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $additional
     * @return string
     */
    public function getAddToCartUrl($product, $additional = [])
    {
        $requestingPageUrl = $this->getRequest()->getParam('requesting_page_url');

        if (!empty($requestingPageUrl)) {
            $additional['useUencPlaceholder'] = true;
            $url = parent::getAddToCartUrl($product, $additional);
            return str_replace('%25uenc%25', $this->urlEncoder->encode($requestingPageUrl), $url);
        }

        return parent::getAddToCartUrl($product, $additional);
    }
}
