<?php

class Codesbug_Bulksms_Model_Bulksms extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('bulksms/bulksms');
    }

    public function sendSMS($to, $message)
    {
        Mage::log($to);
        Mage::log($message);
        $username = Mage::getStoreConfig('bulksmssection/settings/username');
        $password = Mage::getStoreConfig('bulksmssection/settings/password');
        $url = "https://bulksms.vsms.net/eapi/submission/send_sms/2/2.0?username=" . $username . "&password=" . $password . "&message=" . $message . "&msisdn=" . $to;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }

    public function testSMS($user_number)
    {

        $message = Mage::Helper('bulksms')->__('Test SMS');
        $sms = $this->sendSMS($user_number, $message);
        $data = json_decode($sms);
        if (!isset($data->error)) {
            return true;
        } else {
            return false;
        }
    }

    public function isModuleActive()
    {
        $smsModule = Mage::getStoreConfig('smssection/advancesetting/orderverification');
        $username = Mage::getStoreConfig('bulksmssection/settings/username');
        $password = Mage::getStoreConfig('bulksmssection/settings/password');

        if ($smsModule && trim($username) != '' && trim($password) != '') {
            return true;
        } else {
            return false;
        }
    }

}
