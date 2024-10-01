<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SubTask {{ ucfirst($action) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-content {
            padding: 30px;
            line-height: 1.6;
        }

        .email-content h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .email-content p {
            margin: 5px 0;
        }

        .email-footer {
            background-color: #f4f4f4;
            color: #888;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }

        .email-footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>SubTask {{ ucfirst($action) }}</h1>
        </div>

        <!-- Content -->
        <div class="email-content">
            <h2>Hi,</h2>
            <p>A SubTask has been <strong>{{ $action }}</strong> in your task management system. Below are the details of the SubTask:</p>

            <p><strong>SubTask Title:</strong> {{ $subtask->title }}</p>
            <p><strong>SubTask Description:</strong> {{ $subtask->description }}</p>
            <p><strong>Status:</strong> {{ ucfirst($subtask->status) }}</p>
            <p><strong>Due Date:</strong> {{ $subtask->due_date ?? 'No due date set' }}</p>

            <br>
            <p>If you have any questions or need assistance, feel free to <a href="mailto:support@yourapp.com">contact support</a>.</p>

            <br>
            <a href="{{ url('/tasks/' . $task->id) }}" class="button">View SubTask Details</a>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} maisonduweb. All rights reserved.</p>
            <p>Need help? Visit our <a href="{{ url('/support') }}">Support Center</a> or email us at <a href="mailto:support@yourapp.com">maisonduweb@gmail.com</a>.</p>
        </div>
    </div>
</body>
</html>
