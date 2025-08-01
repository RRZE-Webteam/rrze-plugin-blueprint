<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;

/**
 * Encryption class for secure data handling.
 *
 * This class provides methods to encrypt and decrypt values using OpenSSL.
 * It uses a cipher method of 'aes-256-cbc' and requires the OpenSSL extension.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Encryption
{
    /**
     * The cipher method used for encryption and decryption.
     *
     * This constant defines the cipher method that will be used for
     * encrypting and decrypting values. It is set to 'aes-256-cbc'.
     *
     * @var string
     */
    const CIPHER_METHOD = 'aes-256-cbc';

    /**
     * Encrypts a value.
     *
     * If a user-based key is set, that key is used. Otherwise the default key is used.
     *
     * @param string $value Value to encrypt.
     * @return string|bool Encrypted value, or false on failure.
     */
    public static function encrypt($value)
    {
        if (!extension_loaded('openssl')) {
            return $value;
        }

        $method = self::CIPHER_METHOD;
        $ivlen  = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivlen);

        $rawValue = openssl_encrypt($value . self::getSalt(), $method, self::getKey(), 0, $iv);
        if (!$rawValue) {
            return false;
        }

        return base64_encode($iv . $rawValue);
    }

    /**
     * Decrypts a value.
     *
     * If a user-based key is set, that key is used. Otherwise the default key is used.
     *
     * @param string $rawValue Value to decrypt.
     * @return string|bool Decrypted value, or false on failure.
     */
    public static function decrypt($rawValue)
    {
        if (!extension_loaded('openssl') || empty($rawValue)) {
            return $rawValue;
        }

        $rawValue = base64_decode($rawValue, true);

        $method = self::CIPHER_METHOD;
        $ivlen = openssl_cipher_iv_length($method);
        $iv = substr($rawValue, 0, $ivlen);

        $rawValue = substr($rawValue, $ivlen);

        $value = openssl_decrypt($rawValue, $method, self::getKey(), 0, $iv);
        if (!$value || substr($value, -strlen(self::getSalt())) !== self::getSalt()) {
            return false;
        }

        return substr($value, 0, -strlen(self::getSalt()));
    }

    /**
     * Gets the encryption key to use.
     *
     * @return string Encryption key.
     */
    private static function getKey()
    {
        if (defined('AUTH_KEY') && '' !== AUTH_KEY) {
            return AUTH_KEY;
        }

        // If this is reached, you're either not on a live site or have a serious security issue.
        return 'this-is-a-fallback-key-but-not-secure';
    }

    /**
     * Gets the salt to use for encryption.
     *
     * This method retrieves the salt defined in the AUTH_SALT constant.
     * If AUTH_SALT is not defined or is empty, a fallback value is returned.
     *
     * @return string The salt used for encryption.
     */
    private static function getSalt()
    {
        if (defined('AUTH_SALT') && '' !== AUTH_SALT) {
            return AUTH_SALT;
        }

        // If this is reached, you're either not on a live site or have a serious security issue.
        return 'this-is-a-fallback-salt-but-not-secure';
    }
}
