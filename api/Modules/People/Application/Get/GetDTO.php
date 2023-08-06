<?php

namespace Modules\People\Application\Get;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class GetDTO extends DTOAbstract
{
	public ?string $uuid = null;
	
	public ?string $t = null;
}