<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface Request
{
    public function send(): array;
}
