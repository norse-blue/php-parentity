<?php

namespace NorseBlue\Parentity\Eloquent;

use Illuminate\Database\Eloquent\Model;
use NorseBlue\Parentity\Traits\IsMtiChildModel;

/**
 * Class MtiChildModel
 *
 * @package NorseBlue\Parentity\Eloquent
 */
abstract class MtiChildModel extends Model
{
    use IsMtiChildModel;
}
