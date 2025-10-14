<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataModel;

class DataControl extends Controller
{
    function delete($table, $id)
    {
        $model = new DataModel();
        $where = array(
            "id" => $id
        );
        $model->deleteData($table, $where);
        $model->addLog(Session('id'), 'Delete ' . $table . ' Element', 'Row ID: ' . $id);
        return redirect()->back()->with("success", "Data deleted successfully");
    }
    function tambahproduct(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            // Store in storage/app/public/photos
            $path = $request->file('image')->store('images', 'public');
        }
        $data = array(
            "name" => $request->input("name"),
            "price" => $request->input("price"),
            "description" => $request->input("desc"),
            "category" => $request->input("kategori"),
            "available" => $request->input("available"),
            "image_url" => $path
        );
        $model = new DataModel();
        $model->insertData("products", $data);
        $model->addLog(Session('id'), 'Added Product', Session('username') . ' Added ' . $request->input("name") . ' To the Products table');

        return redirect("product")->with("success", "Data added successfully");
    }
    function editproduct(Request $request)
    {
        $model = new DataModel();
        $where = ["id" => $request->input("id")];
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $data = array(
            "name" => $request->input("name"),
            "price" => $request->input("price"),
            "description" => $request->input("desc"),
            "category" => $request->input("kategori"),
            "available" => $request->input("available")
        );
        $select = $model->getWhere('products', $where);
        if ($request->hasFile('image')) {
            // Optionally delete old photo
            if ($select->image_url && \Storage::disk('public')->exists($select->image_url)) {
                \Storage::disk('public')->delete($select->image_url);
            }

            // Store new one
            $path = $request->file('image')->store('images', 'public');
            $select->image_url = $path;
            $data['image_url'] = $path;
        }
        $model->updateData("products", $data, $where);
        $model->addLog(Session('id'), 'Edited Product', Session('username') . ' Edited ' . $request->input("name") . ' To the products table');
        return redirect("product")->with("success", "Data updated successfully");
    }
    function tambahuser(Request $request)
    {
        $data = array(
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "role" => $request->input("role"),
            "password" => MD5($request->input("password"))
        );
        $model = new DataModel();
        $model->insertData("users", $data);
        $model->addLog(Session('id'), 'Added User', Session('username') . ' Added ' . $request->input("name") . ' To the users table');
        return redirect("user")->with("success", "Data added successfully");
    }
    function edituser(Request $request)
    {
        $data = array(
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "role" => $request->input("role")
        );
        $model = new DataModel();
        $model->updateData("users", $data, ["id" => $request->input("id")]);
        return redirect("user")->with("success", "Data updated successfully");
    }
    function login(Request $request)
    {
        $username = $request->input('username');
        $credentials = [
            'name' => $username,
            'password' => MD5($request->input('password'))
        ];
        $model = new DataModel();
        $user = $model->getWhere('users', $credentials);

        if ($user && $username === $user->name) {
            $request->session()->put('username', $username);
            $request->session()->put('id', $user->id);
            $request->session()->put('role', $user->role);
            $model->addLog(Session('id'), 'User Login', Session('username') . ' Logged in');
            return redirect()->route('home');
        } else {
            $model->addLog('1', 'Failed Login', 'User attempt to login, failed miserably', 'Username: ' . $username);
            return redirect()->route('login')->with('error', 'Invalid username or password');
        }
    }
    function logout(Request $request)
    {
        // Logic for logging out the user (e.g., clearing session data)
        $model = new DataModel();
        $model->addLog(Session('id'), 'User Logout', Session('username') . ' Logged out');
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
    function register(Request $request)
    {
        $data = [
            'name' => $request->input('username'),
            'password' => MD5($request->input('password')),
            'email' => $request->input('email'),
            'role' => '1'
        ];
        $model = new DataModel();
        $model->insertData('users', $data);
        $model->addLog('1', 'User Register', Session('username') . ' Logged out');
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }
    public function add($id)
    {
        $model = new DataModel();
        $product = $model->getWhere('products', ['id' => $id]);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Get cart from session (or create empty array)
        $cart = session()->get('cart', []);

        // If product already in cart, increase quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Otherwise, add new product
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image_url,
            ];
        }

        // Save back to session
        session()->put('cart', $cart);
        $model->addLog('1', 'User Register', Session('username') . ' Logged out');
        // Redirect back with message
        return redirect()->back()->with('success', 'Product added to your cart!');
    }


    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Example: create order (you can adjust based on your table)
        $model = new DataModel();
        $order = $model->insertRetID('orders', [
            'customer_id' => Session('id'),
            'total_price' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        foreach ($cart as $productId => $item) {
            $model->insertData('order_details', [
                'order_id' => $order,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');
        return redirect()->route('products')->with('success', 'Order placed successfully!');
    }

}
