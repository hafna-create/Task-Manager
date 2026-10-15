<h2>Hello {{ $task->assignee->name }},</h2>
<p>A new task has been assigned to you.</p>
<ul>
    <li><strong>Task:</strong> {{ $task->title }}</li>
    <li><strong>Project:</strong> {{ $task->project->name }}</li>
    <li><strong>Due:</strong> {{ $task->due_date?->format('d M Y') ?? 'N/A' }}</li>
</ul>
<p>Please log in to the staff portal to update its status.</p>