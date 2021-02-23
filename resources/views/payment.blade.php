

<script type="text/javascript"
        src="{{ asset('js/AcceptUI.js') }}"
        charset="utf-8">
</script>
<div data-payment="paymentFormAuthorize">
<form id="paymentForm"
      method="POST"
      action="">
    <input type="hidden" name="dataValue" id="dataValue" />
    <input type="hidden" name="dataDescriptor" id="dataDescriptor" />
    <button type="button"
            class="AcceptUI"
            data-billingAddressOptions='{"show":false, "required":false}'
            data-apiLoginID="3Fh9Y9E6mM8T"
            data-clientKey="2u6kEEt6Mb278NEGa6K4UbqfvLc7PWP324nAuRqmSds5zfNfvN89bSVtKKcpje65"
            data-acceptUIFormBtnTxt="Submit"
            data-acceptUIFormHeaderTxt="Card Information"
            data-paymentOptions='{"showCreditCard": true}'
            data-responseHandler="responseHandler">Pay
    </button>
</form>
</div>


<script type="text/javascript">
    var data_payment = {
        "createTransactionRequest": {
            "merchantAuthentication": {
                "name": "3Fh9Y9E6mM8T",
                "transactionKey": "543W2Fy46E4vz4BH"
            },
            "refId": "{{ uniqid(rand(0,99)) }}",
            "transactionRequest": {
                "transactionType": "authCaptureTransaction",
                "amount": "10",
                "payment": {
                    "opaqueData": {
                        "dataDescriptor": "COMMON.ACCEPT.INAPP.PAYMENT",
                        "dataValue": "9471056021205027705001",
                    }
                }

            }
        }
    };
</script>
