<?php

class DPWC_DataPlansException extends Exception
{
    private $_dataPlansError = null;

    public function __construct($message = null, $dataPlansError = null)
    {
        parent::__construct($message);
        $this->setDataPlansError($dataPlansError);
    }

    /**
     * Returns an instance of an exception class from the given error response.
     *
     * @param  array $array
     *
     * @return DataPlansAuthenticationFailureException|DataPlansNotFoundException|DataPlansUsedTokenException|DataPlansInvalidCardException|DataPlansInvalidCardTokenException|DataPlansMissingCardException|DataPlansInvalidChargeException|DataPlansFailedCaptureException|DataPlansFailedFraudCheckException|DataPlansUndefinedException
     */
    public static function getInstance($array)
    {
        switch ($array['code']) {
            case 'authentication_failure':
                return new DataPlansAuthenticationFailureException($array['message'], $array);

            case 'bad_request':
                return new DataPlansBadRequestException($array['message'], $array);

            case 'not_found':
                return new DataPlansNotFoundException($array['message'], $array);

            case 'used_token':
                return new DataPlansUsedTokenException($array['message'], $array);

            case 'invalid_card':
                return new DataPlansInvalidCardException($array['message'], $array);

            case 'invalid_card_token':
                return new DataPlansInvalidCardTokenException($array['message'], $array);

            case 'missing_card':
                return new DataPlansMissingCardException($array['message'], $array);

            case 'invalid_charge':
                return new DataPlansInvalidChargeException($array['message'], $array);

            case 'failed_capture':
                return new DataPlansFailedCaptureException($array['message'], $array);

            case 'failed_fraud_check':
                return new DataPlansFailedFraudCheckException($array['message'], $array);

            case 'failed_refund':
                return new DataPlansFailedRefundException($array['message'], $array);

            case 'invalid_link':
                return new DataPlansInvalidLinkException($array['message'], $array);

            case 'invalid_recipient':
                return new DataPlansInvalidRecipientException($array['message'], $array);

            case 'invalid_bank_account':
                return new DataPlansInvalidBankAccountException($array['message'], $array);

            default:
                return new DataPlansUndefinedException($array['message'], $array);
        }
    }

    /**
     * Sets the error.
     *
     * @param DataPlansError $dataPlansError
     */
    public function setDataPlansError($dataPlansError)
    {
        $this->_dataPlansError = $dataPlansError;
    }

    /**
     * Gets the DataPlansError object. This method will return null if an error happens outside of the API. (For example, due to HTTP connectivity problem.)
     * Please see https://docs.omise.co/api/errors/ for a list of possible errors.
     *
     * @return DataPlansError
     */
    public function getDataPlansError()
    {
        return $this->_dataPlansError;
    }
}

class DataPlansAuthenticationFailureException extends DPWC_DataPlansException { }
class DataPlansBadRequestException extends DPWC_DataPlansException { }
class DataPlansNotFoundException extends DPWC_DataPlansException { }
class DataPlansUsedTokenException extends DPWC_DataPlansException { }
class DataPlansInvalidCardException extends DPWC_DataPlansException { }
class DataPlansInvalidCardTokenException extends DPWC_DataPlansException { }
class DataPlansMissingCardException extends DPWC_DataPlansException { }
class DataPlansInvalidChargeException extends DPWC_DataPlansException { }
class DataPlansFailedCaptureException extends DPWC_DataPlansException { }
class DataPlansFailedFraudCheckException extends DPWC_DataPlansException { }
class DataPlansFailedRefundException extends DPWC_DataPlansException { }
class DataPlansInvalidLinkException extends DPWC_DataPlansException { }
class DataPlansInvalidRecipientException extends DPWC_DataPlansException { }
class DataPlansInvalidBankAccountException extends DPWC_DataPlansException { }
class DataPlansUndefinedException extends DPWC_DataPlansException { }