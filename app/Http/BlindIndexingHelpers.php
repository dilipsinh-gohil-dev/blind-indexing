<?php

namespace App\Http;

use Illuminate\Support\Facades\Log;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\KeyProvider\StringProvider;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\EncryptedField;
use ParagonIE\CipherSweet\Backend\ModernCrypto;

/**
 * Class BlindIndexingHelpers
 *
 * This class provides helper methods for encrypting, decrypting,
 * and managing blind indexing of fields in a database using the
 * CipherSweet library.
 */
class BlindIndexingHelpers
{
    protected static $engine; // The CipherSweet engine instance.

    /**
     * Initializes and returns the CipherSweet engine.
     *
     * @return CipherSweet
     */
    protected static function getEngine()
    {
        // Create the engine only once to improve performance.
        if (!self::$engine) {
            $provider = new StringProvider(env('CIPHERSWEET_KEY'));
            self::$engine = new CipherSweet($provider, new ModernCrypto());
        }
        return self::$engine;
    }

    /**
     * Returns the encrypted field structure with blind indexing.
     *
     * @param string $tableName The name of the database table.
     * @param string $fieldName The name of the field to encrypt.
     * @return EncryptedField
     */
    public static function getStructure($tableName, $fieldName)
    {
        $engine = self::getEngine();
        return (new EncryptedField($engine, $tableName, $fieldName))
            ->addBlindIndex(new BlindIndex($fieldName, [], 128));
    }

    /**
     * Encrypts a given field value and returns the encrypted value along with its blind index.
     *
     * @param string $tableName The name of the database table.
     * @param string $fieldName The name of the field to encrypt.
     * @param mixed $data The data to encrypt.
     * @return array An associative array containing 'encrypted' and 'index' keys.
     */
    public static function encryptFieldValue($tableName, $fieldName, $data)
    {
        $blindIndexObj = self::getStructure($tableName, $fieldName);
        list($encryptedValue, $indexes) = $blindIndexObj->prepareForStorage($data);

        return [
            'encrypted' => $encryptedValue,
            'index' => $indexes[$fieldName] ?? ''
        ];
    }

    /**
     * A convenience method to encrypt a field value.
     *
     * @param string $tableName The name of the database table.
     * @param string $fieldName The name of the field to encrypt.
     * @param mixed $value The value to encrypt.
     * @return array An associative array containing 'encrypted' and 'index' keys.
     */
    public static function encryptField($tableName, $fieldName, $value)
    {
        return self::encryptFieldValue($tableName, $fieldName, $value);
    }

    /**
     * Decrypts a given encrypted field value.
     *
     * @param string $tableName The name of the database table.
     * @param string $fieldName The name of the field to decrypt.
     * @param string $encryptedValue The value to decrypt.
     * @return mixed The decrypted value or an empty string if decryption fails.
     */
    public static function decryptFieldValue($tableName, $fieldName, $encryptedValue)
    {
        try {
            if (!empty($encryptedValue) && trim($encryptedValue) != "") {
                $blindIndexObj = self::getStructure($tableName, $fieldName);
                return $blindIndexObj->decryptValue($encryptedValue);
            }
            return "";
        } catch (\Exception $e) {
            Log::error("Decryption error for $tableName.$fieldName: " . $e->getMessage());
            return "";
        }
    }

    /**
     * A convenience method to decrypt a field value.
     *
     * @param string $tableName The name of the database table.
     * @param string $fieldName The name of the field to decrypt.
     * @param string $encryptedValue The value to decrypt.
     * @return mixed The decrypted value or an empty string if decryption fails.
     */
    public static function decryptField($tableName, $fieldName, $encryptedValue)
    {
        return self::decryptFieldValue($tableName, $fieldName, $encryptedValue);
    }

    /**
     * Retrieves the blind indexes for a given field.
     *
     * @param string $tableName The name of the database table.
     * @param string $fieldName The name of the field to get indexes for.
     * @param mixed $data The data to generate indexes from.
     * @return string The generated blind index or an empty string if none.
     */
    public static function getFieldIndexes($tableName, $fieldName, $data)
    {
        if (!empty($data) && trim($data) != "") {
            $blindIndexObj = self::getStructure($tableName, $fieldName);
            $indexes = $blindIndexObj->getAllBlindIndexes($data);
            return $indexes[$fieldName] ?? '';
        }

        return '';
    }

    /**
     * A convenience method to get the blind index for a specific field.
     *
     * @param string $tableName The name of the database table.
     * @param string $fieldName The name of the field to get the blind index for.
     * @param mixed $value The value to generate the blind index from.
     * @return string The generated blind index or an empty string if none.
     */
    public static function getFieldBlindIndex($tableName, $fieldName, $value)
    {
        return self::getFieldIndexes($tableName, $fieldName, $value);
    }
}
