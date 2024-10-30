<?php 
namespace App\enum;

enum StatusDette: string
{
    case PAYE = 'PAYE';
    case IMPAYE = 'IMPAYE';
    case ALL = 'ALL';
}

// $dette = new Dette();
// $dette->setStatus(StatusDette::PAYE); 