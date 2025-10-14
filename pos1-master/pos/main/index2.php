<?php
// Start session and authentication first
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 25px;
        }

        .dropdown-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        select {
            min-width: 250px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ✅ One Dropdown divided into 3 groups -->
        <div class="dropdown-section text-center">
            <label for="chartSelector" style="font-weight:600;">Select Sales Report:</label>
            <select id="chartSelector" class="form-select d-inline-block w-auto">
                <optgroup label="🕓 Daily Reports">
                    <option value="dailyChart">📅 Today's Sales</option>
                    <option value="productChart">📦 Product Sales Today</option>
                    <option value="yesterdayChart">🕒 Yesterday's Sales</option>
                </optgroup>
                <optgroup label="📆 Periodic Reports">
                    <option value="weeklyChart">📅 Weekly Sales</option>
                    <option value="monthlyChart">🗓 Monthly Sales</option>
                </optgroup>
                <optgroup label="📊 Yearly Reports">
                    <option value="yearlyChart">📆 Yearly Sales</option>
                </optgroup>
            </select>
        </div>

        <!-- ✅ Chart display area -->
        <div class="chart-section text-center">

            <!-- Daily -->
            <div class="card chart-card" id="dailyCard">
                <h3>📅 Today's Sales</h3>
                <div class="chart-container"><canvas id="dailyChart"></canvas></div>
            </div>

            <!-- Product -->
            <div class="card chart-card" id="productCard" style="display:none;">
                <h3>📦 Product Sales Today</h3>
                <div class="chart-container"><canvas id="productChart"></canvas></div>
            </div>

            <!-- Yesterday -->
            <div class="card chart-card" id="yesterdayCard" style="display:none;">
                <h3>🕒 Yesterday's Sales</h3>
                <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
            </div>

            <!-- Weekly -->
            <div class="card chart-card" id="weeklyCard" style="display:none;">
                <h3>📅 Weekly Sales</h3>
                <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
            </div>

            <!-- Monthly -->
            <div class="card chart-card" id="monthlyCard" style="display:none;">
                <h3>🗓 Monthly Sales</h3>
                <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
            </div>

            <!-- Yearly -->
            <div class="card chart-card" id="yearlyCard" style="display:none;">
                <h3>📆 Yearly Sales</h3>
                <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
            </div>

        </div>
    </div>

    <!-- ✅ Chart.js + Dropdown Script -->
    <script>
        // Example dummy data
        const exampleData = [5, 10, 15, 20, 25];
        const exampleLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];

        // Initialize charts
        const charts = {
            dailyChart: new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: "Today's Sales", data: exampleData }] }
            }),
            productChart: new Chart(document.getElementById('productChart'), {
                type: 'pie',
                data: { labels: exampleLabels, datasets: [{ label: 'Product Sales', data: exampleData }] }
            }),
            yesterdayChart: new Chart(document.getElementById('yesterdayChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: "Yesterday's Sales", data: exampleData }] }
            }),
            weeklyChart: new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Weekly Sales', data: exampleData }] }
            }),
            monthlyChart: new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: 'Monthly Sales', data: exampleData }] }
            }),
            yearlyChart: new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Yearly Sales', data: exampleData }] }
            })
        };

        // Handle dropdown change
        document.getElementById('chartSelector').addEventListener('change', function() {
            const selected = this.value;
            document.querySelectorAll('.chart-card').forEach(card => card.style.display = 'none');
            const showCard = document.getElementById(selected.replace('Chart', 'Card'));
            if (showCard) showCard.style.display = 'block';
        });

        // Show first chart by default
        document.getElementById('chartSelector').value = 'dailyChart';
    </script>
</body>

</html>
<?php
// Start session and authentication first
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 25px;
        }

        .dropdown-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        select {
            min-width: 250px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ✅ One Dropdown divided into 3 groups -->
        <div class="dropdown-section text-center">
            <label for="chartSelector" style="font-weight:600;">Select Sales Report:</label>
            <select id="chartSelector" class="form-select d-inline-block w-auto">
                <optgroup label="🕓 Daily Reports">
                    <option value="dailyChart">📅 Today's Sales</option>
                    <option value="productChart">📦 Product Sales Today</option>
                    <option value="yesterdayChart">🕒 Yesterday's Sales</option>
                </optgroup>
                <optgroup label="📆 Periodic Reports">
                    <option value="weeklyChart">📅 Weekly Sales</option>
                    <option value="monthlyChart">🗓 Monthly Sales</option>
                </optgroup>
                <optgroup label="📊 Yearly Reports">
                    <option value="yearlyChart">📆 Yearly Sales</option>
                </optgroup>
            </select>
        </div>

        <!-- ✅ Chart display area -->
        <div class="chart-section text-center">

            <!-- Daily -->
            <div class="card chart-card" id="dailyCard">
                <h3>📅 Today's Sales</h3>
                <div class="chart-container"><canvas id="dailyChart"></canvas></div>
            </div>

            <!-- Product -->
            <div class="card chart-card" id="productCard" style="display:none;">
                <h3>📦 Product Sales Today</h3>
                <div class="chart-container"><canvas id="productChart"></canvas></div>
            </div>

            <!-- Yesterday -->
            <div class="card chart-card" id="yesterdayCard" style="display:none;">
                <h3>🕒 Yesterday's Sales</h3>
                <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
            </div>

            <!-- Weekly -->
            <div class="card chart-card" id="weeklyCard" style="display:none;">
                <h3>📅 Weekly Sales</h3>
                <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
            </div>

            <!-- Monthly -->
            <div class="card chart-card" id="monthlyCard" style="display:none;">
                <h3>🗓 Monthly Sales</h3>
                <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
            </div>

            <!-- Yearly -->
            <div class="card chart-card" id="yearlyCard" style="display:none;">
                <h3>📆 Yearly Sales</h3>
                <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
            </div>

        </div>
    </div>

    <!-- ✅ Chart.js + Dropdown Script -->
    <script>
        // Example dummy data
        const exampleData = [5, 10, 15, 20, 25];
        const exampleLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];

        // Initialize charts
        const charts = {
            dailyChart: new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: "Today's Sales", data: exampleData }] }
            }),
            productChart: new Chart(document.getElementById('productChart'), {
                type: 'pie',
                data: { labels: exampleLabels, datasets: [{ label: 'Product Sales', data: exampleData }] }
            }),
            yesterdayChart: new Chart(document.getElementById('yesterdayChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: "Yesterday's Sales", data: exampleData }] }
            }),
            weeklyChart: new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Weekly Sales', data: exampleData }] }
            }),
            monthlyChart: new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: 'Monthly Sales', data: exampleData }] }
            }),
            yearlyChart: new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Yearly Sales', data: exampleData }] }
            })
        };

        // Handle dropdown change
        document.getElementById('chartSelector').addEventListener('change', function() {
            const selected = this.value;
            document.querySelectorAll('.chart-card').forEach(card => card.style.display = 'none');
            const showCard = document.getElementById(selected.replace('Chart', 'Card'));
            if (showCard) showCard.style.display = 'block';
        });

        // Show first chart by default
        document.getElementById('chartSelector').value = 'dailyChart';
    </script>
</body>

</html>
<?php
// Start session and authentication first
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 25px;
        }

        .dropdown-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        select {
            min-width: 250px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ✅ One Dropdown divided into 3 groups -->
        <div class="dropdown-section text-center">
            <label for="chartSelector" style="font-weight:600;">Select Sales Report:</label>
            <select id="chartSelector" class="form-select d-inline-block w-auto">
                <optgroup label="🕓 Daily Reports">
                    <option value="dailyChart">📅 Today's Sales</option>
                    <option value="productChart">📦 Product Sales Today</option>
                    <option value="yesterdayChart">🕒 Yesterday's Sales</option>
                </optgroup>
                <optgroup label="📆 Periodic Reports">
                    <option value="weeklyChart">📅 Weekly Sales</option>
                    <option value="monthlyChart">🗓 Monthly Sales</option>
                </optgroup>
                <optgroup label="📊 Yearly Reports">
                    <option value="yearlyChart">📆 Yearly Sales</option>
                </optgroup>
            </select>
        </div>

        <!-- ✅ Chart display area -->
        <div class="chart-section text-center">

            <!-- Daily -->
            <div class="card chart-card" id="dailyCard">
                <h3>📅 Today's Sales</h3>
                <div class="chart-container"><canvas id="dailyChart"></canvas></div>
            </div>

            <!-- Product -->
            <div class="card chart-card" id="productCard" style="display:none;">
                <h3>📦 Product Sales Today</h3>
                <div class="chart-container"><canvas id="productChart"></canvas></div>
            </div>

            <!-- Yesterday -->
            <div class="card chart-card" id="yesterdayCard" style="display:none;">
                <h3>🕒 Yesterday's Sales</h3>
                <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
            </div>

            <!-- Weekly -->
            <div class="card chart-card" id="weeklyCard" style="display:none;">
                <h3>📅 Weekly Sales</h3>
                <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
            </div>

            <!-- Monthly -->
            <div class="card chart-card" id="monthlyCard" style="display:none;">
                <h3>🗓 Monthly Sales</h3>
                <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
            </div>

            <!-- Yearly -->
            <div class="card chart-card" id="yearlyCard" style="display:none;">
                <h3>📆 Yearly Sales</h3>
                <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
            </div>

        </div>
    </div>

    <!-- ✅ Chart.js + Dropdown Script -->
    <script>
        // Example dummy data
        const exampleData = [5, 10, 15, 20, 25];
        const exampleLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];

        // Initialize charts
        const charts = {
            dailyChart: new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: "Today's Sales", data: exampleData }] }
            }),
            productChart: new Chart(document.getElementById('productChart'), {
                type: 'pie',
                data: { labels: exampleLabels, datasets: [{ label: 'Product Sales', data: exampleData }] }
            }),
            yesterdayChart: new Chart(document.getElementById('yesterdayChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: "Yesterday's Sales", data: exampleData }] }
            }),
            weeklyChart: new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Weekly Sales', data: exampleData }] }
            }),
            monthlyChart: new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: 'Monthly Sales', data: exampleData }] }
            }),
            yearlyChart: new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Yearly Sales', data: exampleData }] }
            })
        };

        // Handle dropdown change
        document.getElementById('chartSelector').addEventListener('change', function() {
            const selected = this.value;
            document.querySelectorAll('.chart-card').forEach(card => card.style.display = 'none');
            const showCard = document.getElementById(selected.replace('Chart', 'Card'));
            if (showCard) showCard.style.display = 'block';
        });

        // Show first chart by default
        document.getElementById('chartSelector').value = 'dailyChart';
    </script>
</body>

</html>
<?php
// Start session and authentication first
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 25px;
        }

        .dropdown-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        select {
            min-width: 250px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ✅ One Dropdown divided into 3 groups -->
        <div class="dropdown-section text-center">
            <label for="chartSelector" style="font-weight:600;">Select Sales Report:</label>
            <select id="chartSelector" class="form-select d-inline-block w-auto">
                <optgroup label="🕓 Daily Reports">
                    <option value="dailyChart">📅 Today's Sales</option>
                    <option value="productChart">📦 Product Sales Today</option>
                    <option value="yesterdayChart">🕒 Yesterday's Sales</option>
                </optgroup>
                <optgroup label="📆 Periodic Reports">
                    <option value="weeklyChart">📅 Weekly Sales</option>
                    <option value="monthlyChart">🗓 Monthly Sales</option>
                </optgroup>
                <optgroup label="📊 Yearly Reports">
                    <option value="yearlyChart">📆 Yearly Sales</option>
                </optgroup>
            </select>
        </div>

        <!-- ✅ Chart display area -->
        <div class="chart-section text-center">

            <!-- Daily -->
            <div class="card chart-card" id="dailyCard">
                <h3>📅 Today's Sales</h3>
                <div class="chart-container"><canvas id="dailyChart"></canvas></div>
            </div>

            <!-- Product -->
            <div class="card chart-card" id="productCard" style="display:none;">
                <h3>📦 Product Sales Today</h3>
                <div class="chart-container"><canvas id="productChart"></canvas></div>
            </div>

            <!-- Yesterday -->
            <div class="card chart-card" id="yesterdayCard" style="display:none;">
                <h3>🕒 Yesterday's Sales</h3>
                <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
            </div>

            <!-- Weekly -->
            <div class="card chart-card" id="weeklyCard" style="display:none;">
                <h3>📅 Weekly Sales</h3>
                <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
            </div>

            <!-- Monthly -->
            <div class="card chart-card" id="monthlyCard" style="display:none;">
                <h3>🗓 Monthly Sales</h3>
                <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
            </div>

            <!-- Yearly -->
            <div class="card chart-card" id="yearlyCard" style="display:none;">
                <h3>📆 Yearly Sales</h3>
                <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
            </div>

        </div>
    </div>

    <!-- ✅ Chart.js + Dropdown Script -->
    <script>
        // Example dummy data
        const exampleData = [5, 10, 15, 20, 25];
        const exampleLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];

        // Initialize charts
        const charts = {
            dailyChart: new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: "Today's Sales", data: exampleData }] }
            }),
            productChart: new Chart(document.getElementById('productChart'), {
                type: 'pie',
                data: { labels: exampleLabels, datasets: [{ label: 'Product Sales', data: exampleData }] }
            }),
            yesterdayChart: new Chart(document.getElementById('yesterdayChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: "Yesterday's Sales", data: exampleData }] }
            }),
            weeklyChart: new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Weekly Sales', data: exampleData }] }
            }),
            monthlyChart: new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: 'Monthly Sales', data: exampleData }] }
            }),
            yearlyChart: new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Yearly Sales', data: exampleData }] }
            })
        };

        // Handle dropdown change
        document.getElementById('chartSelector').addEventListener('change', function() {
            const selected = this.value;
            document.querySelectorAll('.chart-card').forEach(card => card.style.display = 'none');
            const showCard = document.getElementById(selected.replace('Chart', 'Card'));
            if (showCard) showCard.style.display = 'block';
        });

        // Show first chart by default
        document.getElementById('chartSelector').value = 'dailyChart';
    </script>
</body>

</html>
<?php
// Start session and authentication first
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 25px;
        }

        .dropdown-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        select {
            min-width: 250px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ✅ One Dropdown divided into 3 groups -->
        <div class="dropdown-section text-center">
            <label for="chartSelector" style="font-weight:600;">Select Sales Report:</label>
            <select id="chartSelector" class="form-select d-inline-block w-auto">
                <optgroup label="🕓 Daily Reports">
                    <option value="dailyChart">📅 Today's Sales</option>
                    <option value="productChart">📦 Product Sales Today</option>
                    <option value="yesterdayChart">🕒 Yesterday's Sales</option>
                </optgroup>
                <optgroup label="📆 Periodic Reports">
                    <option value="weeklyChart">📅 Weekly Sales</option>
                    <option value="monthlyChart">🗓 Monthly Sales</option>
                </optgroup>
                <optgroup label="📊 Yearly Reports">
                    <option value="yearlyChart">📆 Yearly Sales</option>
                </optgroup>
            </select>
        </div>

        <!-- ✅ Chart display area -->
        <div class="chart-section text-center">

            <!-- Daily -->
            <div class="card chart-card" id="dailyCard">
                <h3>📅 Today's Sales</h3>
                <div class="chart-container"><canvas id="dailyChart"></canvas></div>
            </div>

            <!-- Product -->
            <div class="card chart-card" id="productCard" style="display:none;">
                <h3>📦 Product Sales Today</h3>
                <div class="chart-container"><canvas id="productChart"></canvas></div>
            </div>

            <!-- Yesterday -->
            <div class="card chart-card" id="yesterdayCard" style="display:none;">
                <h3>🕒 Yesterday's Sales</h3>
                <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
            </div>

            <!-- Weekly -->
            <div class="card chart-card" id="weeklyCard" style="display:none;">
                <h3>📅 Weekly Sales</h3>
                <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
            </div>

            <!-- Monthly -->
            <div class="card chart-card" id="monthlyCard" style="display:none;">
                <h3>🗓 Monthly Sales</h3>
                <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
            </div>

            <!-- Yearly -->
            <div class="card chart-card" id="yearlyCard" style="display:none;">
                <h3>📆 Yearly Sales</h3>
                <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
            </div>

        </div>
    </div>

    <!-- ✅ Chart.js + Dropdown Script -->
    <script>
        // Example dummy data
        const exampleData = [5, 10, 15, 20, 25];
        const exampleLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];

        // Initialize charts
        const charts = {
            dailyChart: new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: "Today's Sales", data: exampleData }] }
            }),
            productChart: new Chart(document.getElementById('productChart'), {
                type: 'pie',
                data: { labels: exampleLabels, datasets: [{ label: 'Product Sales', data: exampleData }] }
            }),
            yesterdayChart: new Chart(document.getElementById('yesterdayChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: "Yesterday's Sales", data: exampleData }] }
            }),
            weeklyChart: new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Weekly Sales', data: exampleData }] }
            }),
            monthlyChart: new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: 'Monthly Sales', data: exampleData }] }
            }),
            yearlyChart: new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Yearly Sales', data: exampleData }] }
            })
        };

        // Handle dropdown change
        document.getElementById('chartSelector').addEventListener('change', function() {
            const selected = this.value;
            document.querySelectorAll('.chart-card').forEach(card => card.style.display = 'none');
            const showCard = document.getElementById(selected.replace('Chart', 'Card'));
            if (showCard) showCard.style.display = 'block';
        });

        // Show first chart by default
        document.getElementById('chartSelector').value = 'dailyChart';
    </script>
</body>

</html>
<?php
// Start session and authentication first
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 25px;
        }

        .dropdown-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        select {
            min-width: 250px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ✅ One Dropdown divided into 3 groups -->
        <div class="dropdown-section text-center">
            <label for="chartSelector" style="font-weight:600;">Select Sales Report:</label>
            <select id="chartSelector" class="form-select d-inline-block w-auto">
                <optgroup label="🕓 Daily Reports">
                    <option value="dailyChart">📅 Today's Sales</option>
                    <option value="productChart">📦 Product Sales Today</option>
                    <option value="yesterdayChart">🕒 Yesterday's Sales</option>
                </optgroup>
                <optgroup label="📆 Periodic Reports">
                    <option value="weeklyChart">📅 Weekly Sales</option>
                    <option value="monthlyChart">🗓 Monthly Sales</option>
                </optgroup>
                <optgroup label="📊 Yearly Reports">
                    <option value="yearlyChart">📆 Yearly Sales</option>
                </optgroup>
            </select>
        </div>

        <!-- ✅ Chart display area -->
        <div class="chart-section text-center">

            <!-- Daily -->
            <div class="card chart-card" id="dailyCard">
                <h3>📅 Today's Sales</h3>
                <div class="chart-container"><canvas id="dailyChart"></canvas></div>
            </div>

            <!-- Product -->
            <div class="card chart-card" id="productCard" style="display:none;">
                <h3>📦 Product Sales Today</h3>
                <div class="chart-container"><canvas id="productChart"></canvas></div>
            </div>

            <!-- Yesterday -->
            <div class="card chart-card" id="yesterdayCard" style="display:none;">
                <h3>🕒 Yesterday's Sales</h3>
                <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
            </div>

            <!-- Weekly -->
            <div class="card chart-card" id="weeklyCard" style="display:none;">
                <h3>📅 Weekly Sales</h3>
                <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
            </div>

            <!-- Monthly -->
            <div class="card chart-card" id="monthlyCard" style="display:none;">
                <h3>🗓 Monthly Sales</h3>
                <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
            </div>

            <!-- Yearly -->
            <div class="card chart-card" id="yearlyCard" style="display:none;">
                <h3>📆 Yearly Sales</h3>
                <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
            </div>

        </div>
    </div>

    <!-- ✅ Chart.js + Dropdown Script -->
    <script>
        // Example dummy data
        const exampleData = [5, 10, 15, 20, 25];
        const exampleLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];

        // Initialize charts
        const charts = {
            dailyChart: new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: "Today's Sales", data: exampleData }] }
            }),
            productChart: new Chart(document.getElementById('productChart'), {
                type: 'pie',
                data: { labels: exampleLabels, datasets: [{ label: 'Product Sales', data: exampleData }] }
            }),
            yesterdayChart: new Chart(document.getElementById('yesterdayChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: "Yesterday's Sales", data: exampleData }] }
            }),
            weeklyChart: new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Weekly Sales', data: exampleData }] }
            }),
            monthlyChart: new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: { labels: exampleLabels, datasets: [{ label: 'Monthly Sales', data: exampleData }] }
            }),
            yearlyChart: new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: { labels: exampleLabels, datasets: [{ label: 'Yearly Sales', data: exampleData }] }
            })
        };

        // Handle dropdown change
        document.getElementById('chartSelector').addEventListener('change', function() {
            const selected = this.value;
            document.querySelectorAll('.chart-card').forEach(card => card.style.display = 'none');
            const showCard = document.getElementById(selected.replace('Chart', 'Card'));
            if (showCard) showCard.style.display = 'block';
        });

        // Show first chart by default
        document.getElementById('chartSelector').value = 'dailyChart';
    </script>
</body>

</html>
