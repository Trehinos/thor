<?php

namespace Thor\Structures\Collection;

enum UniqueSortFlag:int
{
    case REGULAR = 0;
    case NUMERIC = 1;
    case STRING = 2;
    case LOCALE_STRING = 5;
}

