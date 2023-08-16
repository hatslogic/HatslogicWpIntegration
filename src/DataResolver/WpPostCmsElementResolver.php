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

class WpPostCmsElementResolver extends AbstractCmsElementResolver
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
        return 'hats-wp-post';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        return null;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $postStruct = new WpStruct();
        $slot->setData($postStruct);
        $config = $slot->getFieldConfig();
        $configPagePostLimit = $config->get('PagePostLimit');
        $postLimit = $configPagePostLimit->getValue();
        $configPageTitleLimit = $config->get('PageTitleLimit');
        $titleLimit = $configPageTitleLimit->getValue();
        $configDesktopColumnCount = $config->get('DesktopColumnCount');
        $desktopColumn = $configDesktopColumnCount->getValue();
        $configTabletColumnCount = $config->get('TabletColumnCount');
        $tabletColumn = $configTabletColumnCount->getValue();
        $configMobileColumnCount = $config->get('MobileColumnCount');
        $mobileColumn = $configMobileColumnCount->getValue();
        $currentPage = $this->getCurrentPageNumber();
        $salesChannelId = $resolverContext->getSalesChannelContext()->getSalesChannel()->getId();
        $pluginConfig = $this->systemConfigService->get('HatslogicWpIntegration.config', $salesChannelId);
        $wpHomeUrl = $pluginConfig['wordpressHomeUrl'] ?? '';
        $wpApiPath = $pluginConfig['wordpressApiPath'] ?? '';
        $postApiUrl = $wpHomeUrl.'/'.$wpApiPath.'/posts?_embed&per_page='.$postLimit.'&page='.$currentPage.'';
        $response = $this->curlService->getApiHeaderResponse($postApiUrl);
        $totalPosts = $response['xWpTotal'] ?? 0;
        $postsDatas = $response['body'] ?: [];
        $formattedData = $this->contentFormatter->formatBlogList($postsDatas, $titleLimit);
        $data = [
            'posts' => $formattedData,
            'totalPosts' => $totalPosts,
            'currentPage' => $currentPage,
            'perPage' => $postLimit,
            'desktopColumn' => $desktopColumn,
            'tabletColumn' => $tabletColumn,
            'mobileColumn' => $mobileColumn,
        ];

        $postStruct->setPosts($data);
    }

    private function getCurrentPageNumber(): int
    {
        return isset($_GET['page']) ? (int)$_GET['page'] : 1;
    }
}