<?php declare(strict_types=1);

namespace HatslogicWpIntegration\Storefront\Controller;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use HatslogicWpIntegration\Service\CurlService;
use HatslogicWpIntegration\Service\ContentFormatter;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class BlogController extends StorefrontController
{
    /**
     * @var SystemConfigService
     */
    private $systemConfigService;
    
    private $genericPageLoader;

    private $router;
    
    private $curlService;
    
    private $contentFormatter;

    public function __construct(
        SystemConfigService $systemConfigService,
        GenericPageLoaderInterface $genericPageLoader,
        UrlGeneratorInterface $router,
        CurlService $curlService,
        ContentFormatter $contentFormatter
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->genericPageLoader = $genericPageLoader;
        $this->router = $router;
        $this->curlService = $curlService;
        $this->contentFormatter = $contentFormatter;
    }

    /**
    * @Route("/blog/{blog_slug}", name="frontend.hatslogic.blog", methods={"GET"})
    */
    public function blogDetail(Request $request, SalesChannelContext $context): Response
    {
        $slug = $request->get('blog_slug');
        $blog = [];
        $page = $this->genericPageLoader->load($request, $context);
        $salesChannelId = $context->getSalesChannel()->getId();
        $pluginConfig = $this->systemConfigService->get('HatslogicWpIntegration.config', $salesChannelId);
        $wpHomeUrl = $pluginConfig['wordpressHomeUrl'] ?? '';
        $wpApiPath = $pluginConfig['wordpressApiPath'] ?? '';
        $postApiUrl = $wpHomeUrl.'/'.$wpApiPath.'/posts/?_embed&slug='.$slug;
        $response = $this->curlService->getApiResponse($postApiUrl);
        if ($response) {
            $blog = $this->contentFormatter->formatContent($response);
            if (is_array($response) && count($response) > 0) {
                $metaTitle = $response[0]['yoast_head_json']['title'] ?? $response[0]['title']['rendered'];
                $metaDescription = $response[0]['yoast_head_json']['description'] ?? $response[0]['title']['rendered'];
                $canonicalLink = $this->router->generate('frontend.hatslogic.blog', [
                    'blog_slug' => $slug,
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $page->getMetaInformation()->setMetaTitle($metaTitle);
                $page->getMetaInformation()->setMetaDescription($metaDescription);
                $page->getMetaInformation()->setCanonical($canonicalLink);
            }
        }
        
        return $this->renderStorefront('@HatslogicWpIntegration/storefront/page/wp-post/index.html.twig', [
            'blog' => $blog,
            'page' => $page
        ]);
    }

}