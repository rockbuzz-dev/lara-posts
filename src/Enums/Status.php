<?php

namespace Rockbuzz\LaraPosts\Enums;

use BenSampo\Enum\Enum;

final class Status extends Enum
{
    const DRAFT = 1;
    const MODERATE = 5;
    const PUBLISHED = 9;
}
