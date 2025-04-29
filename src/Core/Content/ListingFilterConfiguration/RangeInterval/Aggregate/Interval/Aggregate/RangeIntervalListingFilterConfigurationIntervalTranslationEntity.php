<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class RangeIntervalListingFilterConfigurationIntervalTranslationEntity extends TranslationEntity
{
    protected string $itbLfcRangeIntervalIntervalId;

    protected RangeIntervalListingFilterConfigurationIntervalEntity $itbRangeIntervalInterval;

    protected ?string $title = null;

    /**
     * @api
     */
    public function getItbLfcRangeIntervalIntervalId(): string
    {
        return $this->itbLfcRangeIntervalIntervalId;
    }

    /**
     * @api
     */
    public function getItbRangeIntervalInterval(): RangeIntervalListingFilterConfigurationIntervalEntity
    {
        return $this->itbRangeIntervalInterval;
    }

    /**
     * @api
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @api
     */
    public function setItbLfcRangeIntervalIntervalId(string $itbLfcRangeIntervalIntervalId): void
    {
        $this->itbLfcRangeIntervalIntervalId = $itbLfcRangeIntervalIntervalId;
    }

    /**
     * @api
     */
    public function setItbRangeIntervalInterval(RangeIntervalListingFilterConfigurationIntervalEntity $itbRangeIntervalInterval): void
    {
        $this->itbRangeIntervalInterval = $itbRangeIntervalInterval;
    }

    /**
     * @api
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
}
