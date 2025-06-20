<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electricity Calculator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Electricity Cost Calculator</h2>

    <form method="post" class="card p-4">
        <div class="form-group">
            <label for="voltage">Voltage (V)</label>
            <input type="number" step="any" name="voltage" class="form-control"
                   value="<?= isset($_POST['voltage']) ? htmlspecialchars($_POST['voltage']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="current">Current (A)</label>
            <input type="number" step="any" name="current" class="form-control"
                   value="<?= isset($_POST['current']) ? htmlspecialchars($_POST['current']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="rate">Current Rate (sen/kWh)</label>
            <input type="number" step="any" name="rate" class="form-control"
                   value="<?= isset($_POST['rate']) ? htmlspecialchars($_POST['rate']) : '' ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>

    <?php
  
    function calculateElectricity($voltage, $current, $rate_sen) {
        $power_watt = $voltage * $current;
        $power_kW = $power_watt / 1000;
        $rate_rm = $rate_sen / 100;

        $results = [];
        for ($hour = 1; $hour <= 24; $hour++) {
            $energy = $power_kW * $hour;
            $total = $energy * $rate_rm;
            $results[] = [
                'hour' => $hour,
                'energy' => $energy,
                'total' => $total
            ];
        }

        return [
            'power_kW' => $power_kW,
            'rate_rm' => $rate_rm,
            'table' => $results
        ];
    }
    ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php
        $voltage = $_POST['voltage'];
        $current = $_POST['current'];
        $rate_sen = $_POST['rate'];

        $result = calculateElectricity($voltage, $current, $rate_sen);
        $power_kW = $result['power_kW'];
        $rate_rm = $result['rate_rm'];
        ?>
        <div class="card mt-4 p-4">
            <h4>Result Summary</h4>
            <p><strong>Power:</strong> <?= number_format($power_kW, 5) ?> kW</p>
            <p><strong>Rate:</strong> RM <?= number_format($rate_rm, 3) ?> per kWh</p>

            <table class="table table-bordered mt-3">
                <thead class="thead-light">
                <tr>
                    <th># Hour</th>
                    <th>Energy (kWh)</th>
                    <th>Total (RM)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($result['table'] as $row): ?>
                    <tr>
                        <td><?= $row['hour'] ?></td>
                        <td><?= number_format($row['energy'], 5) ?></td>
                        <td><?= number_format($row['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
