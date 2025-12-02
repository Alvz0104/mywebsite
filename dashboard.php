<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once "employee.php";

$employee = new EmployeeProfile();
$username = $_SESSION["username"];

// Load email from users.json safely
$userFile = __DIR__ . "/users.json";

if (file_exists($userFile)) {
    $userData = json_decode(file_get_contents($userFile), true);
    $email = "No email found";
    foreach ($userData as $usr) {
        if ($usr["username"] === $username) {
            $email = $usr["email"];
            break;
        }   
    }
} else {
    $email = "Email file missing";
}

$message = "";
$profile = $employee->getProfile($username);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "save") {
        $name = trim($_POST["name"] ?? "");
        $position = trim($_POST["position"] ?? "");
        $department = trim($_POST["department"] ?? "");

        if ($name && $position && $department) {
            $profile = $employee->saveProfile($username, $name, $position, $department);
            $message = "Profile saved successfully.";
        } else {
            $message = "All fields are required.";
        }
    }

    if ($action === "delete") {
        if ($employee->deleteProfile($username)) {
            $message = "Profile deleted.";
            $profile = null;
        } else {
            $message = "No profile to delete.";
        }
    }

    if ($action === "update") {
    $name = trim($_POST["name"] ?? "");
    $position = trim($_POST["position"] ?? "");
    $department = trim($_POST["department"] ?? "");

    $current = $employee->getProfile($username);

    $name = $name !== "" ? $name : $current["name"];
    $position = $position !== "" ? $position : $current["position"];
    $department = $department !== "" ? $department : $current["department"];

    $profile = $employee->saveProfile($username, $name, $position, $department);
    $message = "Profile updated successfully.";
}


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body>
    <nav class="navbar">
     <h1>Employee Profile System</h1>

        <div class="nav-right">
        <span>Welcome! <?php echo htmlspecialchars($username); ?></span>
        <a class="nav-btn" href="logout.php">Logout</a>
     </div>
    </nav>

<section id="container"> 
    <h2>Your Employment Profile</h2>

    <?php if ($message):
        $isSuccess = preg_match('/(success|deleted)/i', $message);
    ?>
        <div role="status" aria-live="polite" style="padding:10px;border-radius:4px;margin-bottom:12px;
                            border:1px solid <?php echo $isSuccess ? '#2e7d32' : '#c62828'; ?>;
                            background:<?php echo $isSuccess ? '#e8f5e9' : '#ffebee'; ?>;
                            color:<?php echo $isSuccess ? '#1b5e20' : '#b71c1c'; ?>;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
    <?php endif; ?>

    <form method="POST" action="dashboard.php">
                <label>
                    Name<br>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($profile["name"] ?? ""); ?>" required>
                </label>
                <br><br>
            <div class="field">
            <label class="label" for="department">Department</label>

            <select class="select" id="department" name="department" required>
                <?php
                    $departments = ["IT", "HR", "Finance", "Marketing"];
                    $currentDept = $profile["department"] ?? "";

                    echo '<option value="" disabled ' . ($currentDept === "" ? "selected" : "") . '>Select a department</option>';

                    foreach ($departments as $dept) {
                        $selected = ($currentDept === $dept) ? "selected" : "";
                        echo "<option value='$dept' $selected>$dept</option>";
                    }
                ?>
            </select>
        </div>

        <div class="field">
            <label class="label" for="position">Position</label>

            <select class="select" id="position" name="position" required>
                <?php
                    $currentPos = $profile["position"] ?? "";

                    if (!$currentDept) {
                        echo '<option value="" selected disabled>Select a department first</option>';
                    } else {
                        echo "<option value='' disabled>Select a position</option>";
                        echo "<option value='$currentPos' selected>$currentPos</option>";
                    }
                ?>
            </select>
        </div>

        <script>
            const positions = {
                "IT": ["Developer", "System Admin", "IT Support", "UI Designer", "IT Technician", "System Analyst", "Network Administrator", "Front-End Developer", "Back-End Developer"],
                "HR": ["HR Assistant", "HR Manager", "Recruiter", "HR Officer"],
                "Finance": ["Accountant", "Auditor", "Payroll Officer", "Finance Assisstant", "Budget Analyst"],
                "Marketing": ["Content Creator", "Marketing Manager", "SEO Specialist", "Brand Associate", "Marketing Assistant"]
            };

            const departmentSelect = document.getElementById("department");
            const positionSelect = document.getElementById("position");

            departmentSelect.addEventListener("change", function () {

                const dept = this.value;
                positionSelect.innerHTML = "";

                const defaultOption = document.createElement("option");
                defaultOption.text = "Select a position";
                defaultOption.disabled = true;
                defaultOption.selected = true;
                positionSelect.appendChild(defaultOption);

                positions[dept].forEach(function(pos) {
                    const opt = document.createElement("option");
                    opt.value = pos;
                    opt.text = pos;
                    positionSelect.appendChild(opt);
                });
            });
        </script>


        <!-- Action buttons -->
        <div class="btn-group" >
            <button type="submit" name="action" value="save" class="d-btn">Save Profile</button>
            <button type="submit" name="action" value="update" class="d-btn" <?php echo $profile ? "" : "disabled"; ?>>Update Profile</button>
            <button type="submit" name="action" value="delete" class="d-btn" <?php echo $profile ? "" : "disabled"; ?> onclick="return confirm('Delete your profile?');">Delete Profile</button>
        </div>
    </form>
</section>



    </section>

        <?php if ($profile): ?>
            <div class="card">
                <h2>Current Profile</h2>
                <div class="profile-display">

                <div class="profile-item">
                  <strong>Name:</strong> <?php echo htmlspecialchars($profile['name'] ?? ''); ?>
                </div>
                 <br>

                <div class="profile-item">
                    <strong>Email:</strong> <?php echo htmlspecialchars($email); ?>
                </div>
                <br>

                <div class="profile-item">
                    <strong>Position:</strong> <?php echo htmlspecialchars($profile['position'] ?? ''); ?>
                </div>
                <br>

                <div class="profile-item">
                    <strong>Department:</strong> <?php echo htmlspecialchars($profile['department'] ?? ''); ?>
                </div>

            </div>
        </div>
        <?php else: ?>
            <div class="card">
                <h2>Current Profile</h2>
                <p>No profile available.</p>
            </div>
        <?php endif; ?>

</body>
</html>