<!-- application/views/employee_view.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Information</title>
    <!-- Load Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<!-- Load Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>

    <div class="container mt-5">
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="employee-tab" data-toggle="tab" href="#employee" role="tab" aria-controls="employee" aria-selected="true">Employee Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab" aria-controls="salary" aria-selected="false">Salary & Age</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="employee" role="tabpanel" aria-labelledby="employee-tab">
                <h2>Employee Details</h2>
                <!-- Add content for employee details -->
                <p>Employee Code: XYZ123</p>
                <p>Name: John Doe</p>
            </div>

            <div class="tab-pane fade" id="salary" role="tabpanel" aria-labelledby="salary-tab">
                <h2>Salary & Age</h2>
                <!-- Add content for salary and age -->
                <p>Salary: $50,000</p>
                <p>Age: 30</p>
            </div>
        </div>
    </div>

    <!-- Load Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
