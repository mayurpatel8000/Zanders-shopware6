<?php declare(strict_types=1);

namespace Zanders\ProductCompare\Storefront\Controller;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Zanders\ProductCompare\Storefront\Page\Compare\ComparePageLoader;

#[Package('storefront')]
#[Route(defaults: ['_routeScope' => ['storefront']])]
class CompareController extends StorefrontController
{
    public function __construct(
        private readonly ComparePageLoader $comparePageLoader
    ) {
    }

    #[Route(path: '/compare', name: 'frontend.compare.page', methods: ['GET', 'POST'], defaults: ['_httpCache' => false])]
    public function comparePage(Request $request, SalesChannelContext $context): Response
    {
        $page = $this->comparePageLoader->load($request, $context);

        return $this->renderStorefront('@ZandersProductCompare/storefront/page/compare/index.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route(path: '/compare/content', name: 'frontend.compare.content', methods: ['GET', 'POST'], defaults: ['_httpCache' => false, 'XmlHttpRequest' => true])]
    public function compareContent(Request $request, SalesChannelContext $context): Response
    {
        $page = $this->comparePageLoader->load($request, $context);

        return $this->renderStorefront('@ZandersProductCompare/storefront/component/compare/compare-table.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route(path: '/compare/bar', name: 'frontend.compare.bar', methods: ['GET', 'POST'], defaults: ['_httpCache' => false, 'XmlHttpRequest' => true])]
    public function compareBar(Request $request, SalesChannelContext $context): Response
    {
        $page = $this->comparePageLoader->load($request, $context);

        return $this->renderStorefront('@ZandersProductCompare/storefront/component/compare/compare-bar.html.twig', [
            'page' => $page,
        ]);
    }
}

