<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use \App\Http\BlindIndexingHelpers;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::when(!empty($search), function ($query) use ($search) {
            $nameIndex = BlindIndexingHelpers::getFieldBlindIndex('users', 'name', $search);
            $emailIndex = BlindIndexingHelpers::getFieldBlindIndex('users', 'email', $search);
            $ssnIndex = BlindIndexingHelpers::getFieldBlindIndex('users', 'ssn', $search);

            $query->where(function ($subQuery) use ($search, $nameIndex, $emailIndex, $ssnIndex) {
                $subQuery->where('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('name_index', $nameIndex)
                        ->orWhere('email_index', $emailIndex)
                        ->orWhere('ssn_index', $ssnIndex);
            });
        })->paginate(perPage: 10);

        return view('users.index', compact('users'));
    }



    public function create()
    {
        return view('users.form');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
            'ssn' => 'required',
            'password' => 'required',
        ]);

        // Encrypt fields here using your BlindIndexingHelpers
        $encryptedName = BlindIndexingHelpers::encryptFieldValue('users', 'name', $validatedData['name']);
        $validatedData['name'] = $encryptedName['encrypted'];
        $validatedData['name_index'] = $encryptedName['index'];

        $encryptedEmail = BlindIndexingHelpers::encryptFieldValue('users', 'email', $validatedData['email']);
        $validatedData['email'] = $encryptedEmail['encrypted'];
        $validatedData['email_index'] = $encryptedEmail['index'];

        $encryptedSSN = BlindIndexingHelpers::encryptFieldValue('users', 'ssn', $validatedData['ssn']);
        $validatedData['ssn'] = $encryptedSSN['encrypted'];
        $validatedData['ssn_index'] = $encryptedSSN['index'];

        // Hash the password
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData); // Add encrypted data as necessary

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::select([
            "users.*",
            "users.name as encrypted_name",
            "users.email as encrypted_email",
            "users.ssn as encrypted_ssn",
        ])->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
            'ssn' => 'required|string',
            'password' => 'nullable|string',
        ]);

        $encryptedEmail = BlindIndexingHelpers::encryptFieldValue('users', 'email', $validatedData['email']);
        $validatedData['email'] = $encryptedEmail['encrypted'];
        $validatedData['email_index'] = $encryptedEmail['index'];

        $encryptedName = BlindIndexingHelpers::encryptFieldValue('users', 'name', $validatedData['name']);
        $validatedData['name'] = $encryptedName['encrypted'];
        $validatedData['name_index'] = $encryptedName['index'];

        $encryptedSSN = BlindIndexingHelpers::encryptFieldValue('users', 'ssn', $validatedData['ssn']);
        $validatedData['ssn'] = $encryptedSSN['encrypted'];
        $validatedData['ssn_index'] = $encryptedSSN['index'];

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData); // Update with encrypted data as necessary

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
