<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <form id="checkoutForm" method="POST" action="/payment/process">
            @csrf
            <input type="text" name="totalPrice" id="totalPrice">
            <input type="hidden" name="omiseToken">
            <input type="hidden" name="omiseSource">
            <button type="submit" id="checkoutButton">Checkout</button>
        </form>

        @if($charge && !empty($charge))
            <p>{{ $charge['id'] }}</p>
            <h1>QR Code:</h1>
            <img src="{{ $charge['source']['scannable_code']['image']['download_uri'] }}" alt="QR Code" style="width: 400px; height: auto;">
        @endif

        @if(isset($status))
            <div class="alert alert-success">
                {{ $status }}
            </div>
        @endif


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.omise.co/omise.js"></script>
        <script>
            OmiseCard.configure({
                publicKey: "{{ config('services.omise.public_key') }}"
            });

            var button = document.querySelector("#checkoutButton");
            var form = document.querySelector("#checkoutForm");

            button.addEventListener("click", (event) => {
                event.preventDefault();
                OmiseCard.open({
                    amount: document.getElementById("totalPrice").value * 100,
                    currency: "THB",
                    defaultPaymentMethod: "promptpay",
                    onCreateTokenSuccess: (nonce) => {
                        if (nonce.startsWith("tokn_")) {
                            form.omiseToken.value = nonce;
                        } else {
                            form.omiseSource.value = nonce;
                        };
                        form._token.value = '{{ csrf_token() }}';
                        form.submit();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                @if($charge && !empty($charge)) 
                    var chargeId = <?php echo json_encode($charge['id']); ?>;
                    pollWebhookStatus(chargeId);
                @endif
            });

            function pollWebhookStatus(element){
                setInterval(() => {
                    fetch("{{ Route('checkWebhookStatus') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-Token": '{{csrf_token()}}'
                        },
                        body:JSON.stringify(
                            {
                                charge_id: element
                            }
                        )
                    })
                    .then(async response => {
                        const isJson = response.headers.get('content-type')?.includes('application/json');
                        const data = isJson ? await response.json() : null; 
    
                        if(!response.ok){
                            const error = (data && data.errorMessage) || "{{trans('general.warning.system_failed')}}" + " (CODE:"+response.status+")";
                            return Promise.reject(error);
                        }

                        console.log('data:', data);
                        if (data.status === 'success') {
                            updatePageAfterWebhook();
                        } else {
                            console.log('pending payment');
                        }
    
                    })
                    .catch((er) => {
                        console.log('Error' + er);
                    });
                }, 1000);
            }

            function updatePageAfterWebhook() {
                console.log('Webhook received, updating page...');
            }

        </script>

    </body>
</html>