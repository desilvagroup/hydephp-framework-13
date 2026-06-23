<?php

declare(strict_types=1);

namespace Hyde\Foundation;

use Illuminate\Foundation\Application as BaseApplication;

/**
 * @property self $app
 */
class Application extends BaseApplication
{
    protected $storagePath = 'app/storage';

    /**
     * {@inheritdoc}
     */
    protected function registerBaseBindings(): void
    {
        parent::registerBaseBindings();
    }

    /**
     * Get the path to the cached packages.php file.
     */
    public function getCachedPackagesPath(): string
    {
        // Since we have a custom path for the cache directory, we need to return it here.
        return 'app/storage/framework/cache/packages.php';
    }
}
