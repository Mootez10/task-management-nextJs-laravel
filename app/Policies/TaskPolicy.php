<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function modify(User $user, Task $task): Response
    {
        return $user->id === $task->user_id //edhe ken user-id mtaa mootez houwa nefssou el task mtaa mootez
        ? Response::allow() //kamel mrigel 
        : Response::deny('you do not own this task'); // makesh owner besh taamel heka //deny twakaff el action
    }
}
