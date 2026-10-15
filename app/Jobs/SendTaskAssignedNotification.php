<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\TaskAssignedMail;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;

class SendTaskAssignedNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public Task $task) {}

    public function handle(): void
    {
        $this->task->loadMissing(['assignee', 'project']);

        if ($this->task->assignee) {
            Mail::to($this->task->assignee->email)->send(new TaskAssignedMail($this->task));
        }
    }
}