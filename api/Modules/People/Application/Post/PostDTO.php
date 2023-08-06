<?php

namespace Modules\People\Application\Post;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class PostDTO extends DTOAbstract
{
	public string $apelido;
	
	public string $nome;
	
	public string $nascimento;
	
	public array $stack;
}