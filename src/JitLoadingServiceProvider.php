<?php

namespace Cruxinator\JitLoading;

use Cruxinator\Package\Package;
use Cruxinator\Package\PackageServiceProvider;
use Cruxinator\JitLoading\Commands\JitLoadingCommand;

class JitLoadingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('jitloading')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_jitloading_table')
            ->hasCommand(JitLoadingCommand::class);
    }
}
