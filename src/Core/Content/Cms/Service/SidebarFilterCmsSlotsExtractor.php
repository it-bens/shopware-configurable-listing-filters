<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service;

use Shopware\Core\Content\Cms\CmsPageCollection;

final class SidebarFilterCmsSlotsExtractor implements SidebarFilterCmsSlotsExtractorInterface
{
    public function extractSidebarFilterCmsSlots(CmsPageCollection $cmsPageCollection): array
    {
        $sidebarFilterCmsSlots = [];
        foreach ($cmsPageCollection->getElements() as $cmsPage) {
            if ($cmsPage->getSections() === null) {
                continue;
            }

            foreach ($cmsPage->getSections() as $cmsSection) {
                if ($cmsSection->getBlocks() === null) {
                    continue;
                }

                foreach ($cmsSection->getBlocks() as $cmsBlock) {
                    if ($cmsBlock->getSlots() === null) {
                        continue;
                    }

                    foreach ($cmsBlock->getSlots() as $cmsSlot) {
                        if ($cmsSlot->getType() === 'sidebar-filter') {
                            $sidebarFilterCmsSlots[] = $cmsSlot;
                        }
                    }
                }
            }
        }

        return $sidebarFilterCmsSlots;
    }
}
