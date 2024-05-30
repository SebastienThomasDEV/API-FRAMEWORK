<?php

use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Repository\UserRepository;

const ENTITY_TEMPLATE = <<<EOT
<?php

namespace Sthom\Back\Entity;

class {entity}
{
    {properties}
    {getters}
    {setters}
}
EOT;

const PROPERTY_TEMPLATE = 'private ?{type} @{name} = null;';

const GETTER_TEMPLATE = <<<EOT
    public final function get{ucName}(): ?{type}
    {
        return @this->{name};
    }
EOT;

const SETTER_TEMPLATE = <<<EOT
    public final function set{ucName}({type} @{name}): void
    {
        @this->{name} = @{name};
    }
EOT;


const CONTROLLER_TEMPLATE = <<<EOT
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;

class {controller} extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public final function base(): array
    {
        return @this->send([
            'message' => 'Hello World',
        ]);
    }
}

EOT;


const REPOSITORY_TEMPLATE = <<<EOT
<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class {repository} extends AbstractRepository
{
    

}

EOT;






