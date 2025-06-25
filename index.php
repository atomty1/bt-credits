<?php
require "includes/formhandler.php";
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
      <form action="includes/formhandler.php" class="col-md-6 card px-5 py-3" method="POST">
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
              <option>Luke Perrot</option>
              <option>Stuart Coalter</option>
              <option>Tammy McBride</option>
              <option>Nathan </option>
              <option>Hollie</option>
              <option>Leanne</option>
              <option>Anto</option>
              <option>Jonnie</option>
            </select>
          </div>

          <div class="col mb-3">
            <label for="accountTextInput" class="form-label">Account Number</label>
            <input type="text" id="accountTextInput" required name="acct_number" class="form-control" placeholder="Account Number" name="acct_number" maxlength="10">
          </div>

          <div class="col mb-3">
            <label for="birthday" class="form-label">Date</label><br>
            <input class="form-control" type="date" required name="date_applied" id="dateInput">
          </div>

          <div class="col mb-3">
            <label for="amountTextInput" class="form-label">Amount (£)</label>
            <input type="text" id="amountTextInput" required class="form-control" placeholder="Amount" name="amount_credited" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
          </div>

          <div class="col mb-3">
            <label for="reasonSelect" class="form-label">Select reason for applying credit</label>
            <select id="reasonSelect" class="form-select" name="reason" required>
              <option value="" disabled selected>--Select Reason--</option>
              <option>Auto Compensation Not Applied</option>
              <option>Billing Error</option>
              <option>Complaint Closure</option>
              <option>Complete Wifi Guarantee </option>
              <option>Equipment Charge</option>
              <option>Home Tech Expert</option>
              <option>Held To Term Charge</option>
              <option>Incorrect Charge</option>
              <option>Missed Engineer</option>
              <option>Reward Card</option>
              <option>Held To Term Charge 1.6</option>

            </select>
          </div>

          <button type="submit" class="btn btn-primary form-control">Submit</button>

        </fieldset>
      </form>

    </div>
    <footer class="bg-dark" style="height: 50px;">
      <p class="text-center text-light pt-2"> &copy; <?php echo date("Y"); ?> Shonubi Femi </p>
    </footer>
  </div>


  <script src="index.js"></script>
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

      const dateInput = document.getElementById("dateInput");

      // Set the minimum date
      dateInput.setAttribute("min", formattedToday);

      // If the input already has a past value, clear it
      if (dateInput.value && dateInput.value < formattedToday) {
        dateInput.value = "";
      }
    }
  </script>

  <script>
    document.querySelector("form").addEventListener("submit", function(e) {
      const form = e.target;
      const inputs = form.querySelectorAll("input, select");
      let allFilled = true;

      inputs.forEach(input => {
        if (input.type !== "submit" && !input.value.trim()) {
          allFilled = false;
          input.classList.add("is-invalid");
        } else {
          input.classList.remove("is-invalid");
        }
      });

      if (!allFilled) {
        e.preventDefault(); // Stop form submission
        alert("Please fill in all the required fields.");
      } else {
        // Optional confirmation popup
        alert("Form submitted successfully!");
      }
    });
  </script>


</body>

</html>