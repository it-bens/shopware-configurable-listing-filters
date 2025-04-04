<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\CmsPageCollection;

interface SidebarFilterCmsSlotsExtractorInterface
{
    /**
     * @return CmsSlotEntity[]
     */
    public function extractSidebarFilterCmsSlots(CmsPageCollection $cmsPageCollection): array;
}
