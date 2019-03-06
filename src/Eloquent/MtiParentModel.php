<?php

namespace NorseBlue\Parentity\Eloquent;

use Illuminate\Database\Eloquent\Model;
use NorseBlue\Parentity\Traits\IsMtiParentModel;

/**
 * Class MtiParentModel
 *
 * @package NorseBlue\Parentity\Eloquent
 */
abstract class MtiParentModel extends Model
{
    use IsMtiParentModel;
}
