<?php

declare(strict_types=1);

namespace Hyde\Framework;

use Hyde\Pages\HtmlPage;
use Hyde\Pages\BladePage;
use Hyde\Pages\MarkdownPage;
use Hyde\Pages\MarkdownPost;
use Hyde\Foundation\HydeKernel;
use Hyde\Foundation\Providers\ViewServiceProvider;
use Hyde\Pages\DocumentationPage;
use Hyde\Foundation\Providers\NavigationServiceProvider;
use Hyde\Framework\Services\MarkdownService;
use Hyde\Framework\Services\BuildTaskService;
use Hyde\Foundation\Providers\ConfigurationServiceProvider;
use Hyde\Framework\Concerns\RegistersFileLocations;
use Illuminate\Support\ServiceProvider;
use Hyde\Facades\Config;

/**
 * Register and bootstrap core Hyde application services.
 */
class HydeServiceProvider extends ServiceProvider
{
    use RegistersFileLocations;

    protected HydeKernel $kernel;

    public function register(): void
    {
        $this->kernel = HydeKernel::getInstance();
        $this->kernel->setBasePath($this->app->basePath());
        HydeKernel::setInstance($this->kernel);

        $this->app->instance(HydeKernel::class, $this->kernel);

        $this->app->singleton(BuildTaskService::class, BuildTaskService::class);
        $this->app->bind(MarkdownService::class, MarkdownService::class);

        $this->app->register(ConfigurationServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(NavigationServiceProvider::class);

        $this->kernel->setSourceRoot(Config::getString('hyde.source_root', ''));

        $this->registerSourceDirectories([
            HtmlPage::class => $this->getSourceDirectoryConfiguration(HtmlPage::class, '_pages'),
            BladePage::class => $this->getSourceDirectoryConfiguration(BladePage::class, '_pages'),
            MarkdownPage::class => $this->getSourceDirectoryConfiguration(MarkdownPage::class, '_pages'),
            MarkdownPost::class => $this->getSourceDirectoryConfiguration(MarkdownPost::class, '_posts'),
            DocumentationPage::class => $this->getSourceDirectoryConfiguration(DocumentationPage::class, '_docs'),
        ]);

        $this->registerOutputDirectories([
            HtmlPage::class => $this->getOutputDirectoryConfiguration(HtmlPage::class, ''),
            BladePage::class => $this->getOutputDirectoryConfiguration(BladePage::class, ''),
            MarkdownPage::class => $this->getOutputDirectoryConfiguration(MarkdownPage::class, ''),
            MarkdownPost::class => $this->getOutputDirectoryConfiguration(MarkdownPost::class, 'posts'),
            DocumentationPage::class => $this->getOutputDirectoryConfiguration(DocumentationPage::class, 'docs'),
        ]);

        $this->storeCompiledSiteIn(Config::getString('hyde.output_directory', '_site'));

        $this->useMediaDirectory(Config::getString('hyde.media_directory', '_media'));

        $this->discoverBladeViewsIn(BladePage::sourceDirectory());
    }

    public function boot(): void
    {
        //
    }
}
