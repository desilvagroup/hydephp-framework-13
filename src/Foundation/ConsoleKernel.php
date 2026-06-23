<?php

declare(strict_types=1);

namespace Hyde\Foundation;

use Illuminate\Foundation\Console\Kernel;

class ConsoleKernel extends Kernel
{
    /**
     * Get the bootstrap classes for the application.
     */
    protected function bootstrappers(): array
    {
        // Since we store our application config in `app/config.php`, we need to replace
        // the default LoadConfiguration bootstrapper class with our implementation.
        // We do this by swapping out the LoadConfiguration class with our own.
        // We also inject our Yaml configuration loading bootstrapper.

        // First, we need to register our Yaml configuration repository,
        // as this code executes before service providers are registered.
        $this->app->singleton(Internal\YamlConfigurationRepository::class);

        return [
            \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
            \Hyde\Foundation\Internal\LoadYamlEnvironmentVariables::class,
            \Hyde\Foundation\Internal\LoadConfiguration::class,
            \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
            \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
            \Hyde\Foundation\Internal\LoadYamlConfiguration::class,
            \Illuminate\Foundation\Bootstrap\SetRequestForConsole::class,
            \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
            \Illuminate\Foundation\Bootstrap\BootProviders::class,
        ];
    }
}
