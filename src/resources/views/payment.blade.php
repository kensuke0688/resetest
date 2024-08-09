<!DOCTYPE html>
<html>
<head>
    <title>Laravel Stripe Payment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<div class="container">
    <h2>Laravel Stripe Payment</h2>
    @if(session('success_message'))
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- コース選択ボタンを追加 -->
            <div class="course-selection">
                <form id="course-form">
                    <button type="button" class="btn btn-primary course-button" data-amount="5000">松 コース</button>
                    <button type="button" class="btn btn-primary course-button" data-amount="10000">竹 コース</button>
                    <button type="button" class="btn btn-primary course-button" data-amount="15000">梅 コース</button>
                </form>
            </div>

            <!-- 支払いフォーム -->
            <form action="{{ route('payment.handle') }}" method="post" id="payment-form" style="display:none;">
                @csrf
                <div class="form-group">
                    <label for="amount">金額</label>
                    <input type="text" name="amount" id="amount" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="card-element">クレジットカード情報</label>
                    <div id="card-element"></div>
                    <div id="card-errors" role="alert"></div>
                </div>
                <button class="btn btn-primary mt-3">決済する</button>
            </form>
        </div>
    </div>
</div>

<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();

    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    var card = elements.create('card', {style: style});
    card.mount('#card-element');

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var courseButtons = document.querySelectorAll('.course-button');
    courseButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var amount = this.getAttribute('data-amount');
            document.getElementById('amount').value = amount;
            document.getElementById('payment-form').style.display = 'block';
        });
    });

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        form.submit();
    }
</script>
</body>
</html>