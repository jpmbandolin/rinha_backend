<?php

namespace Modules\People;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use Modules\People\Application\Post\Post;
use Modules\People\Application\Get\GetDTO;
use Modules\People\Application\Post\PostDTO;

class Router
{
	public function __invoke(RouteCollectorProxy $group): void
	{
		$group->post('', [Post::class, 'run'])
			->add(new DtoBuilder(PostDTO::class));
		
		$group->get('[/{uuid}]', [Application\Get\Get::class, 'run'])
			->add(new DtoBuilder(GetDTO::class));
		
	}
}