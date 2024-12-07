<?php

use App\Scheduler\DailyUpdateStatusScheduler;

Schedule::call(new DailyUpdateStatusScheduler)->daily();
