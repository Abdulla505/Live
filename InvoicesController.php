<?php

namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \App\Model\Table\InvoicesTable $Invoices
 */
class InvoicesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['file.php']);

        if (in_array($this->getRequest()->getParam('file.php'), ['file.php'])) {
            //$this->eventManager()->off($this->Csrf);
            $this->eventManager()->off($this->Security);
        }
    }

    public function ipn()
    {
        $this->autoRender = false;

        $payment_method = $this->getRequest()->getQuery('file.php');

        \Cake\Log\Log::debug($payment_method, 'file.php');

        if ($payment_method && !empty($this->getRequest()->getData())) {
            \Cake\Log\Log::debug($this->getRequest()->getData(), 'file.php');

            if ($payment_method == 'file.php') {
                $this->ipnPaypal($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'file.php') {
                $this->ipnSkrill($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'file.php') {
                $this->ipnWebmoney($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'file.php') {
                $this->ipnCoinPayments($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'file.php') {
                $this->ipnPerfectMoney($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'file.php') {
                $this->ipnPayeer($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'file.php') {
                $this->ipnPaytm($this->getRequest()->getData());

                return null;
            }
        }

        if ($payment_method === 'file.php') {
            $this->ipnPaystack();

            return null;
        }

        if ($payment_method === 'file.php') {
            $this->ipnCoinbase();

            return null;
        }
    }

    // @see https://github.com/Paytm-Payments/Paytm_WHMCS_Kit/blob/master/Paytm_WHMCS_v6.x-7.x_Kit-master/Paytm/gateways/callback/paytm.php
    protected function ipnPaytm($data)
    {
        if (isset($data['file.php']) && isset($data['file.php']) && isset($data['file.php']) && $data['file.php'] != 325) {
            require_once(APP . 'file.php');

            $invoice_id = (int)$data['file.php'];
            $invoice = $this->Invoices->get($invoice_id);

            $checksum_recv = 'file.php';
            if (isset($data['file.php'])) {
                $checksum_recv = (isset($data['file.php'])) ? $data['file.php'] : 'file.php';
            }

            $checksum_status = verifychecksum_e(
                $data,
                html_entity_decode(get_option('file.php')),
                $checksum_recv
            );

            // Create an array having all required parameters for status query.
            $requestParamList = ["MID" => get_option('file.php'), "ORDERID" => $data['file.php']];
            $StatusCheckSum = getChecksumFromArray(
                $requestParamList,
                html_entity_decode(get_option('file.php'))
            );
            $requestParamList['file.php'] = $StatusCheckSum;

            // Call the PG'file.php'https://securegw.paytm.in/order/status'file.php'STATUS'file.php'TXN_SUCCESS'file.php'STATUS'file.php'TXN_SUCCESS'file.php'TXNAMOUNT'file.php'TXNAMOUNT'file.php'VERIFIED'file.php'INVALID'file.php'STATUS'file.php'INVALID'file.php'INVALID'file.php'controller'file.php'Invoices'file.php'action'file.php'view'file.php'payments'file.php'payments'file.php'HTTP_X_PAYSTACK_SIGNATURE'file.php'HTTP_X_PAYSTACK_SIGNATURE'file.php''file.php'sha512'file.php'paystack_secret_key'file.php't give any output
        // Remember that this is a call from Paystack'file.php'charge.success'file.php'_'file.php'VERIFIED'file.php'INVALID'file.php'185.71.65.92'file.php'185.71.65.189'file.php'149.202.17.210'file.php'm_operation_id'file.php'm_sign'file.php'payeer_secret_key'file.php'm_operation_id'file.php'm_operation_ps'file.php'm_operation_date'file.php'm_operation_pay_date'file.php'm_shop'file.php'm_orderid'file.php'm_amount'file.php'm_curr'file.php'm_desc'file.php'm_status'file.php'm_params'file.php'm_params'file.php'sha256'file.php':'file.php'm_orderid'file.php'm_sign'file.php'm_status'file.php'success'file.php'VERIFIED'file.php'INVALID'file.php'perfectmoney_account'file.php'perfectmoney_passphrase'file.php'PAYMENT_ID'file.php':'file.php'PAYEE_ACCOUNT'file.php':'file.php'PAYMENT_AMOUNT'file.php':'file.php'PAYMENT_UNITS'file.php':'file.php'PAYMENT_BATCH_NUM'file.php':'file.php'PAYER_ACCOUNT'file.php':'file.php':'file.php'TIMESTAMPGMT'file.php'V2_HASH'file.php'PAYMENT_ID'file.php'PAYMENT_AMOUNT'file.php'PAYEE_ACCOUNT'file.php'PAYMENT_UNITS'file.php'currency_code'file.php'VERIFIED'file.php'INVALID'file.php'LMI_PAYMENT_NO'file.php'LMI_PAYMENT_NO'file.php'LMI_PAYMENT_AMOUNT'file.php'VERIFIED'file.php'INVALID'file.php'coinpayments_merchant_id'file.php'coinpayments_ipn_secret'file.php'HTTP_HMAC'file.php'HTTP_HMAC'file.php'error'file.php'No HMAC signature sent'file.php'payments'file.php'php://input'file.php'error'file.php'Error reading POST data'file.php'payments'file.php'merchant'file.php'merchant'file.php''file.php'error'file.php'No Merchant ID passed'file.php'payments'file.php'error'file.php'Invalid Merchant ID'file.php'payments'file.php'HTTP_HMAC'file.php'error'file.php'HMAC signature does not match'file.php'payments'file.php'custom'file.php'amount1'file.php'status'file.php'error'file.php'Amount is less than order total!'file.php'payments'file.php'VERIFIED'file.php'INVALID'file.php'coinbase_api_secret'file.php'HTTP_X_CC_WEBHOOK_SIGNATURE'file.php'HTTP_X_CC_WEBHOOK_SIGNATURE'file.php''file.php'php://input'file.php'payments'file.php'payments'file.php'Invalid payload provided. No JSON object could be decoded.'file.php'payments'file.php'Invalid payload provided.'file.php'payments'file.php'sha256'file.php'HMAC signature does not match'file.php'payments'file.php'charge:confirmed'file.php'VERIFIED'file.php'INVALID'file.php'merchant_id'file.php'transaction_id'file.php'skrill_secret_word'file.php'mb_amount'file.php'mb_currency'file.php'status'file.php'skrill_email'file.php'transaction_id'file.php'amount'file.php'md5sig'file.php'status'file.php'pay_to_email'file.php'VERIFIED'file.php'INVALID'file.php'cmd'file.php'_notify-validate'file.php'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'file.php'paypal_sandbox'file.php'no'file.php'no'file.php'https://ipnpb.paypal.com/cgi-bin/webscr'file.php'POST'file.php'payments'file.php'custom'file.php'payment_status'file.php'Refunded'file.php'Completed'file.php'VERIFIED'file.php'INVALID';
        }

        $this->Invoices->successPayment($invoice);
    }
}
