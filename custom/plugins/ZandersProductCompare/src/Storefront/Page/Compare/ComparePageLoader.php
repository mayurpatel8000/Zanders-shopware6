<?php declare(strict_types=1);

namespace Zanders\ProductCompare\Storefront\Page\Compare;

use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\HttpFoundation\Request;

class ComparePageLoader
{
    public function __construct(
        private readonly GenericPageLoaderInterface $genericPageLoader,
        private readonly SalesChannelRepository $productRepository
    ) {
    }

    public function load(Request $request, SalesChannelContext $context): ComparePage
    {
        $page = $this->genericPageLoader->load($request, $context);
        $page = ComparePage::createFrom($page);
        $page->getMetaInformation()?->setRobots('noindex,follow');

        // Extract product IDs from query string or request body
        $rawIds = $request->get('products', $request->get('ids', []));
        if (is_string($rawIds)) {
            $productIds = array_filter(explode(',', $rawIds));
        } elseif (is_array($rawIds)) {
            $productIds = array_filter($rawIds);
        } else {
            $productIds = [];
        }

        // Limit to max 4 products
        $productIds = array_slice(array_unique($productIds), 0, 4);
        $page->setProductIds($productIds);

        if (empty($productIds)) {
            $page->setProducts(new ProductCollection());
            $page->setPropertyMatrix([]);
            return $page;
        }

        $criteria = new Criteria($productIds);
        $criteria->addAssociation('cover.media');
        $criteria->addAssociation('media');
        $criteria->addAssociation('manufacturer');
        $criteria->addAssociation('properties.group');
        $criteria->addAssociation('options.group');
        $criteria->addAssociation('prices');

        /** @var ProductCollection $products */
        $products = $this->productRepository->search($criteria, $context)->getEntities();

        // Preserve order of requested IDs
        $orderedProducts = new ProductCollection();
        foreach ($productIds as $id) {
            $product = $products->get($id);
            if ($product) {
                $orderedProducts->add($product);
            }
        }

        $page->setProducts($orderedProducts);
        $page->setPropertyMatrix($this->buildPropertyMatrix($orderedProducts));

        return $page;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPropertyMatrix(ProductCollection $products): array
    {
        $matrix = [];

        foreach ($products as $product) {
            $properties = $product->getProperties();
            if (!$properties) {
                continue;
            }

            foreach ($properties as $option) {
                $group = $option->getGroup();
                if (!$group) {
                    continue;
                }

                $groupId = $group->getId();
                if (!isset($matrix[$groupId])) {
                    $groupName = $group->getTranslation('name') ?? $group->getName() ?? 'Specification';
                    $matrix[$groupId] = [
                        'id' => $groupId,
                        'name' => $groupName,
                        'position' => $group->getPosition() ?? 0,
                        'values' => [],
                    ];
                }

                $optionName = $option->getTranslation('name') ?? $option->getName();
                $matrix[$groupId]['values'][$product->getId()][] = $optionName;
            }
        }

        // Sort rows by property group position then name
        uasort($matrix, function ($a, $b) {
            if ($a['position'] === $b['position']) {
                return strcmp($a['name'], $b['name']);
            }
            return $a['position'] <=> $b['position'];
        });

        // Normalize values for all products
        foreach ($matrix as &$groupRow) {
            $allValues = [];
            foreach ($products as $product) {
                $pId = $product->getId();
                if (!empty($groupRow['values'][$pId])) {
                    $valStr = implode(', ', $groupRow['values'][$pId]);
                    $groupRow['values'][$pId] = $valStr;
                    $allValues[] = $valStr;
                } else {
                    $groupRow['values'][$pId] = null;
                    $allValues[] = '';
                }
            }

            // Flag whether this attribute differs across compared products
            $groupRow['hasDifference'] = count(array_unique($allValues)) > 1;
        }
        unset($groupRow);

        return $matrix;
    }
}

