<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ChangelogLinker\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    // this parameter is detected from "git origin", but you can change it
    $parameters->set(Option::REPOSITORY_URL, 'https://github.com/HDInnovations/UNIT3D-Community-Edition');
};
