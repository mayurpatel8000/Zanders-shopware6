<?php declare(strict_types=1);

namespace Zanders\ProductCompare\Storefront\Page\Compare;

use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Storefront\Page\Page;

class ComparePage extends Page
{
    protected ?ProductCollection $products = null;

    /**
     * @var array<string, mixed>
     */
    protected array $propertyMatrix = [];

    /**
     * @var array<string>
     */
    protected array $productIds = [];

    public function getProducts(): ?ProductCollection
    {
        return $this->products;
    }

    public function setProducts(ProductCollection $products): void
    {
        $this->products = $products;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPropertyMatrix(): array
    {
        return $this->propertyMatrix;
    }

    /**
     * @param array<string, mixed> $propertyMatrix
     */
    public function setPropertyMatrix(array $propertyMatrix): void
    {
        $this->propertyMatrix = $propertyMatrix;
    }

    /**
     * @return array<string>
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }

    /**
     * @param array<string> $productIds
     */
    public function setProductIds(array $productIds): void
    {
        $this->productIds = $productIds;
    }
}

