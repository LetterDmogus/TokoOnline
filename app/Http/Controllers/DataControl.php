<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataControl extends Controller
{
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Fetch the data (you can use your $model instead of DB)
        $data = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('payments.status', '=', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'orders.id as Order_ID',
                'orders.created_at as Order_Date',
                'products.name as Product',
                'order_details.quantity as Quantity',
                'order_details.price as Price',
                DB::raw('order_details.quantity * order_details.price as Subtotal')
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headings
        $sheet->fromArray([
            ['Order ID', 'Order Date', 'Product', 'Quantity', 'Price', 'Subtotal']
        ], null, 'A1');

        // Add data rows
        $sheet->fromArray($data->map(function ($row) {
            return [
                $row->Order_ID,
                $row->Order_Date,
                $row->Product,
                $row->Quantity,
                $row->Price,
                $row->Subtotal,
            ];
        })->toArray(), null, 'A2');

        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Stream response (no temp file needed)
        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $filename = 'sales_report_' . now()->format('Ymd_His') . '.xlsx';

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
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
    function detaildelete($table, $id)
    {
        $model = new DataModel();
        $where = array(
            "user_id" => $id
        );
        $model->deleteData($table . 's', $where);
        $where = array(
            "id" => $id
        );
        $model->deleteData('users', $where);
        $model->addLog(Session('id'), 'Delete ' . $table . ' Element', 'Row ID: ' . $id);
        return redirect('/' . $table)->with("success", "Data deleted successfully");
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
            "image_url" => $path,
            "stock" => $request->input("stock"),
            "shop_id" => session('shop_id') ? session('shop_id') : $request->input("toko"),
        );
        $model = new DataModel();
        $model->insertData("products", $data);
        $model->addLog(Session('id'), 'Added Product', Session('username') . ' Added ' . $request->input("name") . ' To the Products table');

        return redirect("product")->with("success", "Data added successfully");
    }
    function restock(Request $request)
    {
        $data = array(
            "product_id" => $request->input("products"),
            "quantity" => $request->input("amount"),
            "manager_id" => Session("id"),
            "created_at" => now()
        );
        $model = new DataModel();
        $model->insertData("restock", $data);
        $model->addLog(Session('id'), 'Added Restock', Session('username') . ' Restocked ' . $request->input("products") . ' To the Products table');
        return redirect("restock")->with("success", "Data added successfully");
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
    function resetpassword($id)
    {
        $data = array(
            "password" => MD5("12345"),
        );
        $model = new DataModel();
        $model->updateData("users", $data, ["id" => $id]);
        $model->addLog(Session('id'), 'Reset USer Password', 'Reset password for User ID: ' . $id);
        return redirect("user")->with("success", "Password resetted successfully");
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
            $model->addLog('1', 'Failed Login', 'User attempt to login, failed miserably. Target: ' . $username);
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
        $model = new DataModel();
        $data = [
            'name' => $request->input('username'),
            'password' => MD5($request->input('password')),
            'email' => $request->input('email'),
            'role' => '1'
        ];
        $model->insertData('users', $data);
        $where = ['email' => $request->input('email')];
        $getID = $model->getWhere('users', $where);
        $data2 = [
            'full_name' => $request->input('fn'),
            'user_id' => $getID->id,
            'birth_date' => $request->input('bd'),
            'gender' => $request->input('gender'),
            'address' => $request->input('ad'),
            'phone' => $request->input('phone'),
        ];
        if ($request->has('sel')) {
            $model->insertData('sellers', $data2);
        } else {
            $model->insertData('sellers', $data2);
        }
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
    function editcustomer(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $model = new DataModel();
        $where = ["id" => $request->input("id")];
        $data = array(
            "name" => $request->input("username"),
            "email" => $request->input("email"),
        );
        $select = $model->getWhere('users', $where);
        if ($request->hasFile('image')) {

            // Optionally delete old photo
            if ($select->profile_url && \Storage::disk('public')->exists($select->profile_url)) {
                \Storage::disk('public')->delete($select->profile_url);
            }

            // Store new one
            $path = $request->file('image')->store('images', 'public');
            $select->profile_url = $path;
            $data['profile_url'] = $path;
        }
        $model->updateData("users", $data, $where);

        $where2 = ["user_id" => $request->input("id")];
        $data2 = array(
            "full_name" => $request->input("fn"),
            "birth_date" => $request->input("bd"),
            "address" => $request->input("ad"),
            "gender" => $request->input("gender"),
            "phone" => $request->input("phone"),
        );
        $model->updateData("customers", $data2, $where2);
        $model->addLog(Session('id'), 'Edited Customer', Session('username') . ' Edited Customer ID: ' . $request->input("id"));
        return redirect("customer")->with("success", "Data updated sucessfully");

    }
    public function updateShop(Request $request, $id)
    {
        $model = new DataModel();

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shop_logos', 'public');
            $data['logo'] = $path;
        }

        $model->updateData('shops', $data, ['id' => $id]);

        return redirect()->route('seller.shop.manage', $id)->with('success', 'Shop updated successfully.');
    }
    public function storeShop(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = new DataModel();
        $getdata = $model->getWhere('sellers', ['user_id' => session('id')], 1);

        $data = [
            'seller_id' => $getdata->id,
            'name' => $request->name,
            'description' => $request->description,
            'shop_tier' => 1,
            'created_at' => now(),
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shop_logos', 'public');
        }

        $model->insertData('shops', $data);

        return redirect('/')->with('success', 'Shop created successfully!');
    }
    public function create($orderId)
    {
        $model = new DataModel();
        $order = $model->getWhere('orders', ['orders.id' => $orderId]);
        return view('operate.payment', compact('order'));
    }

    public function store(Request $request, $orderId)
    {
        $request->validate([
            'method' => 'required|in:cash,e-wallet,credit',
            'proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $model = new DataModel();
        $order = $model->getWhere('orders', ['orders.id' => $orderId]);
        $description = 'Payment for Order #' . $order->id;
        $data = [
            'description' => $description,
            'type' => 'order',
            'method' => $request->input('method'),
            'amount' => $order->total_price,
            'status' => 'pending',
            'created_at' => now(),
            'order_id' => $orderId
        ];

        if ($request->hasFile('proof')) {
            $data['status'] = 'paid';
            $data['paid_at'] = now();
            $data['proof'] = $request->file('proof')->store('payment_proofs', 'public');
        }
        $model->insertData('payments', $data);
        return redirect('orders/detail/' . $orderId)->with('success', 'Payment submitted successfully!');
    }
    public function exportCsv(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $data = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('payments.status', '=', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'orders.id as Order_ID',
                'orders.created_at as Order_Date',
                'products.name as Product',
                'order_details.quantity as Quantity',
                'order_details.price as Price',
                DB::raw('order_details.quantity * order_details.price as Subtotal')
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $filename = 'sales_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Order Date', 'Product', 'Quantity', 'Price', 'Subtotal']);
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->Order_ID,
                    $row->Order_Date,
                    $row->Product,
                    $row->Quantity,
                    $row->Price,
                    $row->Subtotal,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

}