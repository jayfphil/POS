<?php
    session_start(); 
    require './include/config_pos.php';

    $template->set('title', 'Welcome');
    $template->place('header'); // Set page header code (from inc/templates):

    $result_dailytotal = PDO_FetchRow("SELECT SUM(total_amt) as DailyTotal FROM `transaction_tb` WHERE `tt_completed` IS NOT NULL AND DATE(`date_created`) LIKE  '%".$date_format."%'");
?>
  <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

  <style>
    html {
      margin: 2em;
    }

    body {
      background-color: white;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select.form-control {
      background: transparent;
      border: none;
      border-bottom: 1px solid #000000;
      -webkit-box-shadow: none;
      box-shadow: none;
      border-radius: 0;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select.form-control:focus {
      background: transparent;
      -webkit-box-shadow: none;
      box-shadow: none;
    }

    textarea {
      resize:none;
    }

    @media print {
        .btn {
           display: none;
        }
    }
  </style>

    <div class="container-fluid">

      <form action="pos.php">
      
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="text-center">
              <b>LUCKY BUNNY PH; SALES MONITORING SHEET</b>
              <hr />
            </div>
          </div>
        </div>

        <div class="form-group row">

          <label for="date" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">Date</label>
          <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
            <input type="text" class="form-control" id="date" name="date">
          </div>

          <label for="branch" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">Branch</label>
          <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
            <input type="text" class="form-control" id="branch" name="branch">
          </div>

        </div>
        <div class="form-group row">

          <label for="cashier1" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">1st Cashier</label>
          <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
            <input type="text" class="form-control" id="cashier1" name="cashier1">
          </div>

          <label for="cashier2" class="col-lg-1 col-md-2 col-sm-2 col-xs-12 col-form-label">2nd Cashier</label>
          <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12">
            <input type="text" class="form-control" id="cashier2" name="cashier2">
          </div>

        </div>

        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <hr />
            <h6>PARTICULARS</h6>
          </div>
        </div>

        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>RELEASED</b> Cups</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Cups Income</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-form-label"><i>Less:</i></label>
          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Snacks - Ala Carte</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>SOLD</b> Cups</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Snacks - Unlimited</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>REJECTED</b> Cups</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Cakes Income</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>MISSING</b> Cups</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Merchandises</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Total Number of <b>COMPLI</b> Cups</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            &nbsp;
          </div>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <textarea></textarea>
          </div>

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-form-label">Witness for the Rejected, Missing & Compli Cups:</label>

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 text-center">
            <hr />
            <label class="col-form-label">Witness Staff</label>
          </div>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12 text-center">
            <hr />
            <label class="col-form-label">Witness Staff</label>
          </div>

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <b>TOTAL INCOME</b>
          </div>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample" placeholder="PHP" value="<?php echo ($result_dailytotal['DailyTotal']) ? number_format($result_dailytotal['DailyTotal'], 2):""; ?>">
          </div>

        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <hr />
          </div>
        </div>

        <div class="form-group row">

          <label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-form-label"><i>Less:</i> Daily Operational Expenses</label>
          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Groceries (Snacks)</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Drinking Water</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">General Merchandise</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Tube Ice / Crushed Ice</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Discounts (SC, PWD, Promos)</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Liquefied Petroleum Gas</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Communication</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">TOTAL EXPENSES</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample" placeholder="PHP">
          </div>

        </div>
        <div class="form-group row">

          <label for="test" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">Stocks Deliveries (Milktea)</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="test" name="test">
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label">NET INCOME OF THE DAY</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12">
            <input type="text" class="form-control" id="sample" name="sample" placeholder="PHP">
          </div>

        </div>
        <div class="form-group row">

          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
              I / We hereby certify that the financial statements and informations declared at the back of this document are true and correct to the best of my / our knowledge.
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label short-div">COH IF NO BANKING WAS MADE</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12 short-div">
            <input type="text" class="form-control" id="sample" name="sample" placeholder="PHP">
          </div>

        </div>
        <div class="form-group row">

          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
              And I / We understand that any PROVEN false statement may qualify me for any legal action that may take by the management of Lucky Bunny Ph.
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label short-div">GRAND TOTAL / COH</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12 short-div">
            <input type="text" class="form-control" id="sample" name="sample" placeholder="PHP">
          </div>

        </div>
        <div class="form-group row">

          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
            <hr />
              (Cashier's On-Duty, Signature/s Over Printed Name)
          </div>

          <label for="sample" class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-form-label short-div">AUDITED BY:</label>
          <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12 short-div">
            <input type="text" class="form-control" id="sample" name="sample" placeholder="PHP">
          </div>

        </div>

        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col text-center">
              <button type="submit" class="btn btn-secondary">Go Back</button>
              <button type="button" class="btn btn-primary" onclick="window.print();">PRINT</button>
            </div>
          </div>
        </div>

      </form>   


    </div>

<?php
  // Set page footer code (from inc/templates):
  $template->place('footer');
?>
