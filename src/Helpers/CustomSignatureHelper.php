<?php

namespace Karu\CustomSignature\Helpers;

use Illuminate\Http\Request;

use Carbon\Carbon;

class CustomSignatureHelper
{
    public function checkSignature(Request $request, $connection=null)
    {

        if(!$connection)
            $connection = array_keys(config('signature.default'))[0];

        /**
         *
         *  return type : boolean
         *
         *  dev_code    - type  : string (alphanumeric), length (10)
         *  the usage for "dev_code" is to bypass the checking of timestamp
         *
         *  nonce       - type  : string (alphanumeric), length (6)
         *  timestamp   - type  : integer (GMT +0000), the unique timestamp
         *  signature   - type  : string (alphanumeric), length (40)
         *
         **/
        if( $request->has('dev_code') && ($request->dev_code == env('APP_DEV_CODE')) )
            return true;

        if( !$request->has('timestamp') || !is_numeric($request->timestamp) ||
            !$request->has('nonce') || strlen($request->nonce) != config("signature.{$connection}.nonce_length") ||
            !$request->has('signature') || strlen($request->signature) != 40 )
            return false;

        // the signature only alive for 10 minutes
        else if( ($this->getTimestamp() - $request->timestamp) > 600 )
            return false;
        else if( ($this->getTimestamp() - $request->timestamp) < 0 )
            return false;

//            $encItems       = [$request->nonce, $request->timestamp, env('APP_SECRET_KEY')];
        //  sort($encItems, SORT_NATURAL);
        $mSignature     = $this->getSignature($request->nonce, $request->timestamp, config('signature.default'));

        if( $request->signature != $mSignature )
            return false;

        return true;
    }

    public function getSignature($nonce, $timestamp, $connection)
    {
        $encItems     = [$nonce, $timestamp, config("signature.{$connection}.key")];

        return sha1( implode('`'.config("signature.{$connection}.secret").'`', $encItems) );
    }

    public function getNonce($connection)
    {
        return str_random(config("signature.{$connection}.nonce_length"));
    }

    public function getTimestamp()
    {
        return Carbon::now('UTC')->timestamp;
    }

    protected function getUUID()
    {
        /**
         *
         * Generate v4 UUID
         *
         * Version 4 UUIDs are pseudo-random.
         */

        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
