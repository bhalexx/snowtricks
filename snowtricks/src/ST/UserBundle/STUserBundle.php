<?php

namespace ST\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class STUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
