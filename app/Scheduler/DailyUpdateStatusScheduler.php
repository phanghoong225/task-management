<?php

namespace App\Scheduler;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;


class DailyUpdateStatusScheduler
{
    public function __invoke(){
        Log::info(message: "Scheduler start.");

        $searchDueDates = [];
        $searchDueDates[] = now()->settime(0, 0)->addDays(-1);
        $searchDueDates[] = now()->settime(0, 0)->addDays(6);

        try {
            Task::whereIn('dueDate', $searchDueDates)
                ->chunkById(200, function (Collection $tasks) {

                    $updates = [];
                    foreach ($tasks as $task) {
                        $dueDate = new \DateTime($task->dueDate);
                        $status = $this->setstatus($dueDate);

                        $updates[] = [
                            'id' => $task->id,
                            'name' =>$task->name,
                            'description' => $task->description,
                            'dueDate' => $task->dueDate,
                            'status' => $status,
                            'assignee_id'=>$task->assignee_id,
                            'workflow'=>$task->workflow,
                        ];
                    }

                    Log::info('Updates: ', $updates);
                    Task::upsert($updates, ['id'], ['status']);

                }, 'dueDate');
        } catch (\Exception $exception) {
            Log::error('Exception occur in update status, {message}', ['message'-> $exception->getMessage()]);
        }
        Log::info("Scheduler end.");
    }

    private function setstatus($dueDate)
    {
        $today = now()->settime(0, 0);
        $diff = date_diff($today, $dueDate, false);
        $isNegative = $diff->invert;

        $diffDay = $diff->d;

        if ($isNegative == 1) {
            $status = "Overdue";
        } else if ($diffDay >= 7) {
            $status = "Not Urgent";
        } else {
            $status = "Due Soon";
        }
        return $status;
    }
}