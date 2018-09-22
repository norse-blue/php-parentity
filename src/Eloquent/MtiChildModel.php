<?php

namespace NorseBlue\Parentity\Eloquent;

use Illuminate\Database\Eloquent\Model;
use NorseBlue\Parentity\Traits\IsMtiChildModel;

class MtiChildModel extends Model
{
    use IsMtiChildModel;
}
