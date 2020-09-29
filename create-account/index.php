<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Create Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <link href="bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>

<body>
  <main>
    <section class="section">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-sm-8 text-center">
            <h2 class="h1 text-dark"><strong>Start building your championship program today</strong></h2>
          </div>
        </div>
        <div class="row my-5 justify-content-center">
          <div class="col-sm-4">
            <div id="card-individual" class="card text-dark selected">
              <div class="card-body text-center">
                <h5 class="card-title"><strong>Individual</strong><br>Girls or Boys</h5>
                <div class="card-text">
                  <p class="h2 mb-0"><strong>$450</strong>/yr</p>
                  <p class="d-block" style="height:25px; margin-bottom: 1rem;"></p>
                </div>
                <a href="#" id="select-individual" class="account-type-btn btn btn-theme-2nd">SELECTED</a>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div id="card-combined" class="card text-dark">
              <div class="card-body text-center">
                <h5 class="card-title"><strong>Combined</strong><br>Girls and Boys</h5>
                <div class="card-text">
                  <p class="h2 mb-0"><strong>$799</strong>/yr</p>
                  <p class="theme-2nd-color text-small"><strong>Save $101 every year</strong></p>
                </div>
                <a href="#" id="select-combined" class="account-type-btn btn btn-theme-2nd outline">SELECT</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row align-items-center justify-content-center">
          <div class="col-sm-8">
          <form class="accountform" method="post" action="../stripe-payment/CreditCard.php">
            <div class="md-m-30px-b">

                <!-- Hidden input based on Indvidual or Combined Plan selected above -->

                <!-- @todo User should not be able to edit this.  -->
                <input type="hidden" id="plan_type" name="plan_type" value="Individual">
                <?php 
                  if( !empty( $_REQUEST['Message'] ) )
                  {
                      echo sprintf( '<p class="output_message">%s</p>', $_REQUEST['Message'] );
                  }
                ?>
                <div class="individual-account">

                  <div class="row">
                    <div class="col-md-12">
                      <hr>
                      <h4 class="text-dark mb-4">Create your Account</h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-2">
                      <div class="form-group">
                        <input id="name" name="name" type="text" placeholder="Full Name" class="validate form-control">
                        <span class="input-focus-effect"></span>
                      </div>
                    </div>
                    <div class="col-md-12 mb-2">
                      <div class="form-group">
                        <input id="email" type="email" placeholder="Email Address" name="email" class="validate form-control">
                        <span class="input-focus-effect"></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="combined-accounts d-none">
                  <div class="row">
                    <div class="col-md-12">
                      <hr>
                      <h4 class="text-dark mb-4">Create the Accounts</h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <h5>Girls Account</h5>
                      <p>Provide the information for the person who will manage the <strong>girls</strong> account</p>
                    </div>
                    <div class="col-md-12 mb-2">
                      <div class="form-group">
                        <input id="name" name="gname" type="text" placeholder="Full Name" class="validate form-control">
                        <span class="input-focus-effect"></span>
                      </div>
                    </div>
                    <div class="col-md-12 mb-2">
                      <div class="form-group">
                        <input id="email" type="gemail" placeholder="Email Address" name="gemail" class="validate form-control">
                        <span class="input-focus-effect"></span>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <h5>Boys Account</h5>
                      <p>Provide the information for the person who will manage the <strong>boys</strong> account</p>
                    </div>
                    <div class="col-md-12 mb-2">
                      <div class="form-group">
                        <input id="name" name="bname" type="text" placeholder="Full Name" class="validate form-control">
                        <span class="input-focus-effect"></span>
                      </div>
                    </div>
                    <div class="col-md-12 mb-2">
                      <div class="form-group">
                        <input id="email" type="bemail" placeholder="Email Address" name="bemail" class="validate form-control">
                        <span class="input-focus-effect"></span>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <hr>
                <h4 class="text-dark mb-4">Payment Method</h4>
              </div>
            </div>

            <div class="row mb-5">
              <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="payment" id="credit_card" value="cc" checked>
                  <label class="form-check-label" for="credit_card">
                    Pay with a credit card
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="payment" id="invoice" value="invoice">
                  <label class="form-check-label" for="invoice">
                    Send an Invoice
                  </label>
                </div>
              </div>
            </div>

            <div id="cc_container" class="card bg-light p-3" style="border: 0;">
              <div class="card-body">
                <h6>Payment Information</h6>
                <div class="card-text">
                  <div class="form-group account_type d-none">
                    <select class="form-control" name="type" id="account-type">
                      <option value="girl">Girls Account Details</option>
                      <option value="boy">Boys Account Details</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <input id="ccn" type="tel" name="creditNumber" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="16" placeholder="Credit Card" class="form-control" required>
                  </div>
                  <div class="row">
                    <div class="col-sm-8">
                      <div class="form-group">
                        <input id="expiry" type="text" placeholder="Expiry MM/YY" maxlength="5" name="expiry" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <input id="cvc" type="tel" placeholder="CVC" inputmode="numeric" maxlength="4"  name="cvc" pattern="[0-9\s]{3,4}" class="form-control" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <input id="promo_code" type="text" placeholder="Promo Code" name="promo_code" class="form-control">
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-5">
              <div class="col-md-12">
                <div class="form-group">
                  <button class="btn btn-theme-2nd w-100" id="submitButton" type="submit" name="send">Create Account</button>
                </div>
                <span class="output_message"></span>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 text-center">
                <p class="text-small"><em>Upon successful processing, we will send out the Purchase Order.</em></p>
                <p class="text-small"><em>Upon successful payment, we will send you an email with instructions on setting up your account including program details, setting a secure password, etc.</em></p>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
      </div>
    </section>
  </main>
  <script src="jquery-3.2.1.min.js"></script>

  <script>
    $('#select-individual').click(function() {
      $('#card-individual').toggleClass('selected');
      $('#card-individual .btn').toggleClass('outline');
      $('#card-combined .btn').toggleClass('outline');
      $('#select-individual').text(
        $(this).text() == 'SELECT' ? 'SELECTED' : 'SELECT'
      );
      $('#card-combined').toggleClass('selected');
      $('#select-combined').text(
        $('#select-combined').text() == 'SELECT' ? 'SELECTED' : 'SELECT'
      );
      $('.individual-account').toggleClass('d-none');
      $('#account-type').prop('disabled', function(i, v) { return !v; });

      $('.combined-accounts').toggleClass('d-none');
      $('#cc_container .account_type').toggleClass('d-none');
      var hiddenField = $('#plan_type'),
        val = hiddenField.val();
      $(hiddenField).val(val === 'Combined' ? 'Individual' : 'Combined');
    });

    $('#select-combined').click(function() {
      $('#card-individual').toggleClass('selected');
      $('#card-individual .btn').toggleClass('outline');
      $('#card-combined .btn').toggleClass('outline');
      $('#select-individual').text(
        $('#select-individual').text() == 'SELECT' ? 'SELECTED' : 'SELECT'
      );
      $('#card-combined').toggleClass('selected');
      $('#select-combined').text(
        $(this).text() == 'SELECT' ? 'SELECTED' : 'SELECT'
      );
      $('.combined-accounts').toggleClass('d-none');
      $('.individual-account').toggleClass('d-none');
      $('#account-type').prop('disabled', function(i, v) { return !v; });
      $('#cc_container .account_type').toggleClass('d-none');
      var hiddenField = $('#plan_type'),
        val = hiddenField.val();
      $(hiddenField).val(val === 'Combined' ? 'Individual' : 'Combined');
      
    });

    $('#invoice').click(function() {
      if ($("#plan_type").val() == "Individual") {
        // combined is turned on show the drop down
        $('#invoice_container').addClass('d-none');
      } else {
        $('#invoice_container').removeClass('d-none');
      }

      $('#cc_container').addClass('d-none');
      $('#ccn').prop('disabled', function(i, v) { return !v; });
      $('#expiry').prop('disabled', function(i, v) { return !v; });
      $('#cvc').prop('disabled', function(i, v) { return !v; });
    });
    $('#credit_card').click(function() {
      $('#invoice_container').addClass('d-none');
      $('#cc_container').removeClass('d-none');
      $('#ccn').prop('disabled', function(i, v) { return !v; });
      $('#expiry').prop('disabled', function(i, v) { return !v; });
      $('#cvc').prop('disabled', function(i, v) { return !v; });
    });

    $('#account-type').prop('disabled', function(i, v) { return !v; });

  </script>
</body>

</html>