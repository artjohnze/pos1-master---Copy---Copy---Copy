<?php
// Test timezone configuration
include('connect.php');

echo "<h2>Timezone Test</h2>";
echo "<p><strong>PHP Timezone:</strong> " . date_default_timezone_get() . "</p>";
echo "<p><strong>Current PHP Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Current PHP Time (Formatted):</strong> " . date('F j, Y g:i A') . "</p>";

// Test database timezone
$result = $db->query("SELECT NOW() as db_time");
$row = $result->fetch();
echo "<p><strong>Database Time:</strong> " . $row['db_time'] . "</p>";

// Test timezone offset
$timezone = new DateTimeZone('Asia/Manila');
$datetime = new DateTime('now', $timezone);
echo "<p><strong>Manila Timezone Offset:</strong> " . $datetime->format('P') . "</p>";
?>

<script>
    function showtime() {
        var now = new Date();
        // Convert to Philippines time (UTC+8)
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var philippinesTime = new Date(utc + (8 * 3600000)); // UTC+8
        var hours = philippinesTime.getHours();
        var minutes = philippinesTime.getMinutes();
        var seconds = philippinesTime.getSeconds()
        var timeValue = "" + ((hours > 12) ? hours - 12 : hours)
        if (timeValue == "0") timeValue = 12;
        timeValue += ((minutes < 10) ? ":0" : ":") + minutes
        timeValue += ((seconds < 10) ? ":0" : ":") + seconds
        timeValue += (hours >= 12) ? " P.M." : " A.M."
        document.getElementById('js-time').innerHTML = timeValue;
        setTimeout("showtime()", 1000);
    }

    window.onload = function() {
        showtime();
    }
</script>

<div style="margin-top: 20px; padding: 20px; border: 1px solid #ccc; background: #f9f9f9;">
    <h3>JavaScript Clock (Philippines Time)</h3>
    <div id="js-time" style="font-size: 24px; font-weight: bold; color: #333;"></div>
</div>

<div style="margin-top: 20px; padding: 20px; border: 1px solid #ccc; background: #e8f4f8;">
    <h3>System Information</h3>
    <p><strong>Server Timezone:</strong> <?php echo date_default_timezone_get(); ?></p>
    <p><strong>Browser Timezone Offset:</strong> <span id="browser-offset"></span> minutes</p>
    <p><strong>Expected Philippines Time:</strong> UTC+8 (480 minutes ahead of UTC)</p>
</div>

<script>
    document.getElementById('browser-offset').innerHTML = new Date().getTimezoneOffset();
</script>