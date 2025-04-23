<!DOCTYPE html>
<html>
<head>
    <title>New Campaign Assigned</title>
</head>
<body>
    <h2>hello {{ $vendor->name }},</h2>

    <p>You Have to Assign a New Campaign </p>

    <ul>
        <li><strong>Campaign Name:</strong> {{ $campaign->name }}</li>
        <li><strong>Description:</strong> {{ $campaign->description }}</li>
        <li><strong>Start Date:</strong> {{ $campaign->start_date }}</li>
        <li><strong>End Date:</strong> {{ $campaign->end_date }}</li>
    </ul>

    <p>Ypu have to check your dashboard now</p>

    <p>Thanks<br>
    Admin Team</p>
</body>
</html>
