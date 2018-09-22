<?php

namespace NorseBlue\Parentity\Eloquent;

use Illuminate\Database\Eloquent\Model;
use NorseBlue\Parentity\Traits\IsMtiParentModel;

abstract class MtiParentModel extends Model
{
    use IsMtiParentModel;
}
