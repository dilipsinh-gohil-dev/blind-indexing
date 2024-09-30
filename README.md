# Blind Indexing Demo

This project demonstrates a secure approach to handling sensitive user information using blind indexing and encryption. The application is built with Laravel and utilizes the [Paragonie/Ciphersweet](https://github.com/paragonie/ciphersweet) library for encryption.

## How It Works

Blind indexing is a method that allows you to perform searches on encrypted data without exposing the actual data itself. By generating a blind index from the sensitive information, you can search and compare records securely. The main components of this approach are:

1. **Encryption**: Sensitive user data (like names, emails, and SSNs) is encrypted using strong encryption algorithms. This ensures that even if the database is compromised, the sensitive data remains unreadable.

2. **Blind Indexing**: Alongside the encrypted data, a blind index is created. This index allows you to perform searches on the encrypted fields without decrypting the data. The blind index is derived from the original data, allowing you to retrieve records efficiently while maintaining security.

3. **Database Storage**: The encrypted values and their corresponding blind indexes are stored in the database. The database schema for users might look like this:
   - `id`: Unique identifier for the user.
   - `name`: User's actual name (plain text).
   - `name_index`: Blind index generated from the user's name.
   - `email`: User's actual email (plain text).
   - `email_index`: Blind index generated from the user's email.
   - `ssn`: User's actual Social Security Number (plain text).
   - `ssn_index`: Blind index generated from the user's SSN.
   - Other fields like phone and address as necessary.

## Features

- **Secure Storage**: Encrypt sensitive data (name, email, SSN) securely.
- **Blind Indexing**: Efficiently search encrypted fields.
- **User Interface**: Input data and visualize both raw and encrypted data easily.


## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/dilipsinh-gohil-dev/blind-indexing.git
   cd blind-indexing-demo

2. **Install dependencies:**:
    ```bash
    composer install
    php artisan key:generate
    php artisan migrate

3. **Set the CipherSweet key in .env:**:
    ```bash
    CIPHERSWEET_KEY=your_256_bit_base64_key


4. **Starting the server:**:
    ```bash
    php artisan serve


## Helpers Documentation

This section provides an overview of the helper functions used in the project, detailing their purpose, parameters, return values, and example usage.

### 1. `getEngine()`
Retrieves the CipherSweet engine instance, initializing it if necessary.

- **Returns**: `CipherSweet` instance.

### 2. `getStructure($tableName, $fieldName)`
Generates the encryption structure for a specified table and field.

- **Parameters**:
  - `string $tableName`: The name of the database table.
  - `string $fieldName`: The name of the field to be encrypted.
- **Returns**: `EncryptedField` instance.

### 3. `encryptFieldValue($tableName, $fieldName, $data)`
Encrypts the specified field value and generates a blind index.

- **Parameters**:
  - `string $tableName`: The name of the database table.
  - `string $fieldName`: The name of the field to be encrypted.
  - `string $data`: The raw data to be encrypted.
- **Returns**: 
  ```php
  [
      'encrypted' => string,  // The encrypted value.
      'index' => string       // The generated blind index.
  ]

### 4. `decryptFieldValue($tableName, $fieldName, $encryptedValue)`
Decrypts the specified encrypted field value.

- **Parameters**:
  - `string $tableName`: The name of the database table.
  - `string $fieldName`: The name of the field to be encrypted.
  - `string $encryptedValue`: The encrypted value to decrypt.
- **Returns**: 
  - `string`: The decrypted value or an empty string on failure.

### 5. `getFieldIndexes($tableName, $fieldName, $data)`
Retrieves blind indexes for a specified field value.

- **Parameters**:
  - `string $tableName`: The name of the database table.
  - `string $fieldName`: The name of the field.
  - `string $data`: The raw data to generate indexes for.
- **Returns**: 
  - `string`: The generated indexes or an empty string if not applicable.

### 6. `getFieldBlindIndex($tableName, $fieldName, $value)`
Convenience method for retrieving blind index.

- **Parameters**:
  - `string $tableName`: The name of the database table.
  - `string $fieldName`: The name of the field.
  - `string $value`: The value to generate the blind index for.
- **Returns**: 
  - `string`: The blind index.

## Usage Example

Here are several examples demonstrating how to use the helper functions in your application.

#### 1. Encrypting a Field Value

To encrypt a field value and generate a blind index, you can use the `encryptFieldValue` method:

```php
use App\Http\BlindIndexingHelpers;

// Encrypting a user's email
$emailData = 'example@example.com';
$encryptionResult = BlindIndexingHelpers::encryptFieldValue('users', 'email', $emailData);

// Accessing the encrypted value and blind index
$encryptedEmail = $encryptionResult['encrypted'];
$emailIndex = $encryptionResult['index'];

echo "Encrypted Email: " . $encryptedEmail;
echo "Email Index: " . $emailIndex;
```

#### 2. Decrypting a Field Value

To decrypt an encrypted field value, use the `decryptFieldValue` method:

```php
// Assuming you have the encrypted email from the previous example
$decryptedEmail = BlindIndexingHelpers::decryptFieldValue('users', 'email', $encryptedEmail);

echo "Decrypted Email: " . $decryptedEmail;

```
#### 3. Retrieving Blind Indexes for a Field

To get the blind index for a specific field value, you can use the `getFieldBlindIndex` method:

```php
// Retrieving the blind index for a specific email value
$blindIndex = BlindIndexingHelpers::getFieldBlindIndex('users', 'email', $emailData);

echo "Blind Index for Email: " . $blindIndex;

```
#### 4. Getting Field Indexes

To retrieve all blind indexes for a given data input, use the `getFieldIndexes` method:

```php
// Getting all blind indexes for a user's name
$nameData = 'John Doe';
$indexes = BlindIndexingHelpers::getFieldIndexes('users', 'name', $nameData);

echo "Blind Indexes for Name: " . $indexes;



