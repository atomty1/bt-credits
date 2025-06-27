<?php
require "includes/formhandler.php";


// Team Leaders
$teamLeadersResult = $conn->query("SELECT id, name FROM team_leaders");

// Reasons
$reasonsResult = $conn->query("SELECT id, reason_text FROM credit_reasons");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BT|EE Credit Form </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  <link rel="stylesheet" href="css/script.css">
</head>

<body>
  <div class="bg-overlay">
    <div>
      <nav class="navbar  bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="img/logo.png" alt="Logo" width="90" height="32" class="d-inline-block align-text-top">
            <b class="text-uppercase text-light">Belfast Contact Center</b>
          </a>
        </div>
      </nav>
    </div>


    <div class="container mb-2" style="padding-top: 10px;">
      <form id="creditsForm" action="includes/formhandler.php" class="col-md-6 card px-5 py-3" method="POST">
        <fieldset>
          <h3 class="col text-center text-uppercase">Credits Form</h3>
          <p class="col mb-1 text-center">Kindly use this form to provide credits applied to customers' accounts.</p>
          <div class="col mb-3">
            <label for="guideTextInput" class="form-label">Guide</label>
            <input type="text" id="guideTextInput" required class="form-control" placeholder="Guide" name="guide_name">
          </div>

          <div class="col mb-3">
            <label for="teamSelect" class="form-label">Whose team are you on?</label>
            <select id="teamSelect" class="form-select" name="team_leader" required>
              <option value="" disabled selected>--Select Team--</option>
              <?php while ($row = $teamLeadersResult->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col mb-3">
            <label for="accountTextInput" class="form-label">Account Number</label>
            <input type="text" id="accountTextInput" required name="acct_number" class="form-control" placeholder="Account Number" name="acct_number" maxlength="10">
          </div>

          <div class="col mb-3">
            <label for="date" class="form-label">Date</label><br>
            <input id="date" class="form-control" type="date" required name="date_applied" id="dateInput">
          </div>

          <div class="col mb-3">
            <label for="amountTextInput" class="form-label">Amount (£)</label>
            <input type="text" id="amountTextInput" required class="form-control" placeholder="Amount" name="amount_credited" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
          </div>

          <div class="col mb-3">
            <label for="reasonSelect" class="form-label">Select reason for applying credit</label>
            <select id="reasonSelect" class="form-select" name="reason" required>
              <option value="" disabled selected>--Select Reason--</option>
              <?php while ($row = $reasonsResult->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['reason_text']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary form-control">Submit</button>

          <!-- Feedback message placeholder -->
          <div class="col mt-3">
            <div id="feedback"></div>
          </div>

        </fieldset>
      </form>

    </div>
    <footer class="bg-dark" style="height: 50px;">
      <p class="text-center text-light pt-2"> &copy; <?php echo date("Y"); ?> Shonubi Femi </p>
    </footer>
  </div>


  <!-- <script src="index.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/en.js"></script>
  <script>
    window.onload = function() {
      // Get today's date in YYYY-MM-DD format
      const today = new Date();
      const yyyy = today.getFullYear();
      const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
      const dd = String(today.getDate()).padStart(2, '0');
      const formattedToday = `${yyyy}-${mm}-${dd}`;

      const dateInput = document.getElementById("date");

      // Set the minimum date
      dateInput.setAttribute("min", formattedToday);

      // If the input already has a past value, clear it
      if (dateInput.value && dateInput.value < formattedToday) {
        dateInput.value = "";
      }
    }
  </script>
  <script>
    document.getElementById('creditsForm').addEventListener('submit', function(e) {
      e.preventDefault(); // Prevent form from reloading the page

      const form = e.target;
      const formData = new FormData(form);

      fetch('includes/formhandler.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text()) // Assuming PHP returns plain text or JSON
        .then(data => {
          document.getElementById('feedback').textContent = data;
          form.reset(); // Optionally reset form
        })
        .catch(error => {
          document.getElementById('feedback').textContent = error;
        });
    });
  </script>
</body>

</html>