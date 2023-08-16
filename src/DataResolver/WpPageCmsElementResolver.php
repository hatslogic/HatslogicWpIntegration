<?php declare(strict_types=1);

namespace HatslogicWpIntegration\DataResolver;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use HatslogicWpIntegration\Struct\WpStruct;
use HatslogicWpIntegration\Service\CurlService;
use HatslogicWpIntegration\Service\ContentFormatter;

class WpPageCmsElementResolver extends AbstractCmsElementResolver
{
    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    private $curlService;
    
    private $contentFormatter;

    public function __construct(
        SystemConfigService $systemConfigService,
        CurlService $curlService,
        ContentFormatter $contentFormatter
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->curlService = $curlService;
        $this->contentFormatter = $contentFormatter;
    }

    public function getType(): string
    {
        return 'hats-wp-page';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        return null;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $pageStruct = new WpStruct();
        $slot->setData($pageStruct);
        $config = $slot->getFieldConfig();
        $configPageSlug = $config->get('PageSlug');
        $slug = $configPageSlug->getValue() ?? 15;
        $salesChannelId = $resolverContext->getSalesChannelContext()->getSalesChannel()->getId();
        $pluginConfig = $this->systemConfigService->get('HatslogicWpIntegration.config', $salesChannelId);
        $wpHomeUrl = $pluginConfig['wordpressHomeUrl'] ?? '';
        $wpApiPath = $pluginConfig['wordpressApiPath'] ?? '';
        $pageApiUrl = $wpHomeUrl.'/'.$wpApiPath.'/pages?_embed&slug='.$slug;
        $response = $this->curlService->getApiResponse($pageApiUrl);
        $pageData = [];
        if ($response) {
            $pageData = $this->contentFormatter->formatContent($response);
        }
        
        $pageStruct->setPage($pageData);
    }
}