<?php

namespace App\Enums;

enum ProductStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ARCHIVED = 'archived';
    case DISCONTINUED = 'discontinued';
    case PENDING = 'pending';
    case DRAFT = 'draft';
    case DELETED = 'deleted';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('Active'),
            self::INACTIVE => __('Inactive'),
            self::ARCHIVED => __('Archived'),
            self::DISCONTINUED => __('Discontinued'),
            self::PENDING => __('Pending'),
            self::DRAFT => __('Draft'),
            self::DELETED => __('Deleted'),
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => __('The product is available for sale.'),
            self::INACTIVE => __('The product is not available for sale.'),
            self::ARCHIVED => __('The product is archived and not visible to customers.'),
            self::DISCONTINUED => __('The product is no longer being produced or sold.'),
            self::PENDING => __('The product is pending approval or release.'),
            self::DRAFT => __('The product is in draft status and not yet published.'),
            self::DELETED => __('The product has been deleted and is no longer available.'),
        };
    }
}
