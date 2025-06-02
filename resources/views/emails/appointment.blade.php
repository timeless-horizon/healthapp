<!DOCTYPE html>
<html>

<head>
    <title>Doctor's Appointment Schedule</title>
</head>

<body>
    <p>Dear {{ $formData['name'] }}</p>
    <p>An appointment has been Schedule for you with your doctor. </p>
    <p>Find the details of the schedule below</p>
    <p><strong>Time:</strong> {{ $formData['doctorDateAndTime'] }}</p>
    <p><strong>Meeting Link:</strong> {{ $formData['meeting_link'] }}</p>
    <p>You can join with the link above or alternatively login to your dashboard and click on the meeting button when
        the time is due</p>
    <span class="mt-5">Timeless Healthcare247</span>
</body>

</html>