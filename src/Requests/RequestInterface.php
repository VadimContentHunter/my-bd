<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Requests;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface RequestInterface
{
    public function send(): mixed;
}
