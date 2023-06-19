<?php

namespace App\Enums;

enum ExpenseType: string
{
    case FUEL = "Fuel";
    case TOLL = "Toll";
    case MEAL = "Meal";
    case CONFERENCE = "Conference";
}
?>
