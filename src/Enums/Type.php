<?php

namespace Rockbuzz\LaraPosts\Enums;

use BenSampo\Enum\Enum;

final class Type extends Enum
{
    const ARTICLE = 1;
    const PODCAST = 5;
    const VIDEO = 9;
}
