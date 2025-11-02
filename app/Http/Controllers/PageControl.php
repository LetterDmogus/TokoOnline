<?php

namespace App\Http\Controllers;
use App\Models\DataModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PageControl extends Controller
{
    public function exportPDF(Request $request)
    {
        $startDate = $request->query('startDate', now()->startOfMonth());
        $endDate = $request->query('endDate', now());
        $sales = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->select(
                'orders.id as order_id',
                'orders.created_at as order_date',
                'products.name as product_name',
                'order_details.quantity',
                'order_details.price',
                DB::raw('order_details.quantity * order_details.price as subtotal')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('payments.status', '=', 'paid')
            ->orderBy('orders.created_at', 'desc')
            ->get(); // your existing query/filter logic
        $totalSales = $sales->sum('subtotal');
        $pdf = Pdf::loadView('exports.salespdf', compact('sales', 'startDate', 'endDate', 'totalSales'));
        return $pdf->download('sales_report.pdf');
    }

    function index()
    {
        if (session('role') == 3 || session('role') == 4) {
            return view("home");
        } else if (session("role") == 2) {
            $model = new DataModel();
            $shops = $model->getJoinData('shops', [
                ['sellers', 'shops.seller_id', 'sellers.id']
            ], ['shops.*', 'sellers.full_name as sellername'], ['sellers.user_id' => session('id')]);
            session()->put('shop', 'none');
            return view("sellerhome", compact('shops'));
        } else if (session("role") == 1) {
            $model = new DataModel();
            $profile = $model->getWhere('users', ['id' => Session('id')]);
            $order = $model->getWhere('orders', ['customer_id' => Session('id')], 3, ['orders.id', 'total_order']);
            $cancelled_order = $model->getWhere('orders', ['customer_id' => Session('id'), 'status' => 'cancelled'], 3, ['orders.id', 'total_order']);
            $delivered_order = $model->getWhere('orders', ['customer_id' => Session('id'), 'status' => 'completed'], 3, ['orders.id', 'total_order']);
            return view("customerhome", compact("order", "cancelled_order", "delivered_order", "profile"));
        } else {
            return redirect('/login')->with("error", "Unauthorized account, Access not allowed, please login.");
        }
    }

    function product(Request $request)
    {
        $model = new DataModel();
        $category = $request->input('category');
        $search = $request->input('search');
        $categories = $model->getData('categories');
        if ($category || $search) {
            $products = $model->getFilteredData('products', [['categories', 'products.category', 'categories.id'], ['shops', 'shops.id', 'products.shop_id']], ['category' => $category], $search, ['products.name', 'products.description', 'shops.name'], ['products.*', 'categories.name as categoryname', 'shops.name as shop_name']);
        } else {
            $products = $model->getJoinData("products", [["categories", "products.category", "categories.id"], ['shops', 'shops.id', 'products.shop_id']], ['products.*', 'categories.name as categoryname', 'shops.name as shop_name']);
        }
        if (session('role') == 3 || session('role') == 4) {
            return view("content.product", compact('products', 'categories', 'category', 'search'));
        } elseif (session('role') == 1) {
            return view("content.customerproduct", compact('products', 'categories', 'category', 'search'));
        } elseif (session('role') == 2) {
            if (session('shop') == 'none') {
                $shops = $model->getJoinData('shops', [
                    ['sellers', 'shops.seller_id', 'sellers.id']
                ], ['shops.*', 'sellers.full_name as sellername'], ['sellers.user_id' => session('id')]);
                return redirect('/')->with("warn", "Check product status by managing your shop!");
            } else {
                return redirect('/seller/shop/' . session('shop'));
            }
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function user(Request $request)
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $selectedrole = $request->input('selectedrole');
            $search = $request->input('search');
            $roles = $model->getData('roles');
            if ($selectedrole || $search) {
                $data = $model->getFilteredData('users', [['roles', 'users.role', 'roles.id']], ['role' => $selectedrole], $search, ['users.name', 'users.email'], ['users.*', 'roles.name as rolename']);
            } else {
                $data = $model->getJoinData("users", [["roles", "users.role", "roles.id"]], ['users.*', 'roles.name as rolename']);
            }
            return view("content.user", compact('data', 'roles', 'selectedrole', 'search'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function order(Request $request)
    {
        if (session('role') == 3 || session('role') == 4) {
            $model = new DataModel();
            $selectedstatus = $request->input('selectedstatus');
            $search = $request->input('search');
            if ($selectedstatus || $search) {
                $data = $model->getFilteredData('orders', [['customers', 'orders.customer_id', 'customers.user_id']], ['status' => $selectedstatus], $search, ['customers.full_name'], ['orders.*', 'customers.full_name']);
            } else {
                $data = $model->getJoinData("orders", [["customers", "orders.customer_id", "customers.user_id"]], ['orders.*', 'customers.full_name']);
            }
            return view("content.order", compact('data', 'selectedstatus', 'search'));
        } elseif (session('role') == 1) {
            $model = new DataModel();
            $selectedstatus = $request->input('selectedstatus');
            $search = $request->input('search');
            if ($selectedstatus || $search) {
                $data = $model->getFilteredData('orders', [['customers', 'orders.customer_id', 'customers.user_id']], ['status' => $selectedstatus, 'customer_id' => session('id')], $search, ['customers.full_name'], ['orders.*', 'customers.full_name']);
            } else {
                $data = $model->getJoinData("orders", [["customers", "orders.customer_id", "customers.user_id"]], ['orders.*', 'customers.full_name'], ['customer_id' => session('id')]);
            }
            return view("content.order", compact('data', 'selectedstatus', 'search'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function customer(Request $request)
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $search = $request->input('search');
            if ($search) {
                $data = $model->getFilteredData('customers', [['users', 'customers.user_id', 'users.id']], null, $search, ['customers.full_name'], ['customers.*', 'users.*']);
            } else {
                $data = $model->getJoinData("customers", [["users", "customers.user_id", "users.id"]], ['users.*', 'customers.*']);
            }
            return view("content.customer", compact('data', 'search'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function restock(Request $request)
    {
        $model = new DataModel();
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        if (session('role') == 3) {
            if ($startDate || $endDate) {
                $data = $data = $model->getJoinData("restock", [["users", "users.id", "restock.manager_id"], ["products", "products.id", "restock.product_id"]], ['restock.*', 'users.name as managername', 'products.name as productname'], null, false, ['restock.created_at', 'DESC'], null, $startDate, $endDate, 'restock.created_at');
            } else {
                $data = $model->getJoinData("restock", [["users", "users.id", "restock.manager_id"], ["products", "products.id", "restock.product_id"]], ['restock.*', 'users.name as managername', 'products.name as productname']);
            }
            return view("content.restock", compact('data', 'startDate', 'endDate'));
        } elseif (session('role') == 2) {
            if ($startDate || $endDate) {
                $data = $data = $model->getJoinData("restock", [["users", "users.id", "restock.manager_id"], ["products", "products.id", "restock.product_id"], ["shops", "shops.id", "products.shop_id"]], ['restock.*', 'users.name as managername', 'products.name as productname'], ['products.shop_id' => session('shop')], false, ['restock.created_at', 'DESC'], null, $startDate, $endDate, 'restock.created_at');
            } else {
                $data = $model->getJoinData("restock", [["users", "users.id", "restock.manager_id"], ["products", "products.id", "restock.product_id"]], ['restock.*', 'users.name as managername', 'products.name as productname'], ['products.shop_id' => session('shop')]);
            }
            return view("content.restock", compact('data', 'startDate', 'endDate'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function restockadd()
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $products = $model->getData('products');
            return view("operate.editrestock", compact('products'));
        } elseif (session('role') == 2) {
            $model = new DataModel();
            $products = $model->getJoinData('products', [['shops', 'shops.id', 'products.shop_id']], ['products.*'], ['products.shop_id' => session('shop')]);
            return view("operate.editrestock", compact('products'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function login()
    {
        return view("login");
    }
    function register()
    {
        return view("register");
    }
    function tambahproduct()
    {
        if (session("role") == 2 || session('role') == 3) {
            $model = new DataModel();
            $kategori = $model->getData("categories");
            $toko = $model->getData("shops");
            $data = null;
            return view("operate.editproduct", compact('toko', 'kategori', 'data'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function editproduct($id)
    {
        $model = new DataModel();
        if (session('role') == 3 || session('role') == 2) {
            $toko = null;
            $data = $model->getJoinData('products', [
                ['categories', 'products.category', 'categories.id']
            ], ['products.*', 'categories.name as catname'], ['products.id' => $id], true);
            $kategori = $model->getData("categories");
            return view("operate.editproduct", compact('data', 'kategori', 'toko'));
        } elseif (session('role') == 2) {
            $test = $model->getWhere('products', ['products.shop_id' => session('shop'), 'products.id' == $id]);
            if ($test) {
                $data = $model->getJoinData('products', [
                    ['categories', 'products.category', 'categories.id']
                ], ['products.*', 'categories.name as catname'], ['products.id' => $id], true);
                $kategori = $model->getData("categories");
                return view("operate.editproduct", compact('data', 'kategori', 'toko'));
            } else {
                return redirect("/")->with("error", "You are not allowed to edit others product!");
            }
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('content.cart', compact('cart'));
    }
    public function viewLogs()
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $logs = $model->getJoinData('logs', [
                ['users', 'logs.user_id', 'users.id']
            ], ['logs.*', 'users.name as username'], orderBy: ['logs.created_at', 'desc'], paginate: 20);
            return view('logs', compact('logs'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    public function detailcustomer($id)
    {
        $model = new DataModel();
        $data = $model->getJoinData('customers', [
            ['users', 'customers.user_id', 'users.id']
        ], ['users.*', 'customers.*'], ['customers.id' => $id], true);

        // Choose which fields to show (label => value)
        $fields = [
            'Name' => $data->full_name,
            'Username' => $data->name,
            'Email' => $data->email,
            'Gender' => $data->gender,
            'Birthdate' => $data->birth_date,
            'Address' => $data->address,
            'Phone' => $data->phone
        ];

        return view('content.detail', [
            'title' => 'Customer Details',
            'table' => 'customer',
            'data' => $data,
            'fields' => $fields
        ]);
    }
    function editcustomer($id)
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $data = $model->getJoinData('customers', [
                ['users', 'customers.user_id', 'users.id']
            ], ['users.*', 'customers.*'], ['customers.id' => $id], true);
            return view("operate.editcustomer", compact('data'));
        } elseif (session('role') && (session('id') == $id)) {
            $model = new DataModel();
            $data = $model->getJoinData('customers', [
                ['users', 'customers.user_id', 'users.id']
            ], ['users.*', 'customers.*'], ['users.id' => $id], true);
            return view("operate.editcustomer", compact('data'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    function salesrseport(Request $request)
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            if ($startDate || $endDate) {
                $sales = $model->getJoinData("orders", [["customers", "orders.customer_id", "customers.user_id"]], ['orders.*', 'customers.*'], orderBy: ['orders.created_at', 'DESC'], startDate: $startDate, endDate: $endDate, dateColumn: 'orders.created_at');
            } else {
                $sales = $model->getJoinData("orders", [["customers", "orders.customer_id", "customers.user_id"]], ['orders.*', 'customers.*']);
            }
            return view("content.salesreport", compact('sales', 'startDate', 'endDate'));
        } elseif (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect("/login")->with("error", "Please login first");
        }
    }
    public function salesReport(Request $request)
    {
        $startDate = $request->input('startDate', now()->startOfMonth());
        $endDate = $request->input('endDate', now());
        $model = new DataModel();
        $sales = $model->getJoinData('orders', [
            ['order_details', 'orders.id', 'order_details.order_id'],
            ['products', 'order_details.product_id', 'products.id'],
            ['payments', 'payments.order_id', 'orders.id']
        ], [
            'orders.id as order_id',
            'orders.created_at as order_date',
            'products.name as product_name',
            'order_details.quantity',
            'order_details.price',
            DB::raw('order_details.quantity * order_details.price as subtotal')
        ],['payments.status' => 'paid'], false, ['orders.created_at', 'desc'], null, $startDate, $endDate, 'orders.created_at');

        $totalSales = $sales->sum('subtotal');

        return view('content.salesreport', compact('sales', 'startDate', 'endDate', 'totalSales'));
    }
    public function sellerdetail($id)
    {
        if (session('role') == 3 || session('role') == 4) {
            $model = new DataModel();
            $data = $model->getJoinData('sellers', [
                ['users', 'sellers.user_id', 'users.id'],
                ['seller_tiers', 'sellers.role', 'seller_tiers.id']
            ], ['sellers.*', 'users.*', 'seller_tiers.name as tiername'], ['sellers.id' => $id], true);

            // Choose which fields to show (label => value)
            $fields = [
                'Name' => $data->full_name,
                'Username' => $data->name,
                'Tiers' => $data->tiername,
                'Email' => $data->email,
                'Gender' => $data->gender,
                'Birthdate' => $data->birth_date,
                'Address' => $data->address,
                'Phone' => $data->phone,
            ];

            return view('content.detail', [
                'title' => 'Seller Details',
                'table' => 'seller',
                'data' => $data,
                'fields' => $fields
            ]);
        } else if (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect('/a');
        }
    }
    public function orderDetail($id)
    {
        $model = new DataModel();
        // Get main order
        $order = $model->getJoinData('orders', [
            ['users', 'orders.customer_id', 'users.id'],
        ], ['orders.*', 'users.name'], ['orders.id' => $id], true);
        $orderDetails = $model->getJoinData('order_details', [
            ['products', 'order_details.product_id', 'products.id'],
        ], [
            'order_details.*',
            'products.name as product_name',
            'products.description',
            'products.price'
        ], ['order_details.order_id' => $id], false);
        // Get order details
        if (session('role') == 3 || session('role') == 4 || session('role') == 2) {
            return view('content.order_detail', compact('order', 'orderDetails'));
        } else if (session('role') == 1 && ($order->customer_id == session('id'))) {
            $payment = $model->getWhere('payments', ['payments.order_id' => $id]);
            return view('content.order_detail', compact('order', 'orderDetails', 'payment'));
        } else if (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect('/a');
        }
    }
    public function productDetail($id)
    {
        $product = DB::table('products')
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->select('products.*', 'shops.name as shop_name')
            ->where('products.id', $id)
            ->first();
        $feedbacks = DB::table('feedback')
            ->join('users', 'feedback.user_id', '=', 'users.id')
            ->select('feedback.*', 'users.name as username')
            ->where('feedback.product_id', $id)
            ->orderBy('feedback.created_at', 'desc')
            ->get();

        return view('content.productdetail', compact('product', 'feedbacks'));
    }
    public function addFeedback(Request $request)
    {
        $userId = session('id');
        $model = new DataModel();
        $request->validate([
            'product_id' => 'required|integer',
            'order_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        // Check if the user actually purchased this product in that order
        $purchased = $model->getJoinData('order_details', [
            ['orders', 'orders.id', 'order_details.order_id']
        ], ['order_details.*'], ['orders.customer_id' => $userId, 'order_details.product_id' => $request->product_id, 'orders.id' => $request->order_id], true);

        if (!$purchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }

        // Prevent duplicate feedback per order
        $alreadyReviewed = $model->getWhere('feedback', ['user_id' => $userId, 'product_id' => $request->product_id, 'order_id' => $request->order_id]);
        if ($alreadyReviewed) {
            return redirect()->back()->with('error', 'You already reviewed this product for that order.');
        }

        // Save feedback
        $model->insertData('feedback', [
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Thanks for your feedback!');
    }
    public function manage($id)
    {
        $model = new DataModel();

        // Get shop info
        $shop = $model->getJoinData('shops', [['sellers', 'sellers.id', 'shops.seller_id']], ['*'], ['shops.id' => $id, 'sellers.user_id' => Session('id')], true);

        if (!$shop) {
            return redirect('/')->with('error', 'Shop not found or not yours.');
        }

        // Get products belonging to this shop
        $products = $model->getWhere('products', ['shop_id' => $id], 2);
        session()->put('shop', $id);
        return view('content.shopdetail', compact('shop', 'products'));
    }
    function seller(Request $request)
    {
        if (session('role') == 3 || session('role') == 4) {
            $model = new DataModel();
            $selectedtier = $request->input('selectedtier');
            $search = $request->input('search');
            $tiers = $model->getData('seller_tiers');

            if ($selectedtier || $search) {
                $sellers = $model->getFilteredData('sellers', [
                    ['users', 'sellers.user_id', 'users.id'],
                    ['seller_tiers', 'sellers.role', 'seller_tiers.id']
                ], ['tier_id' => $selectedtier], $search, ['users.name', 'sellers.full_name'], ['sellers.*', 'users.name as username', 'users.email', 'seller_tiers.name as tiername']);
            } else {
                $sellers = $model->getJoinData('sellers', [
                    ['users', 'sellers.user_id', 'users.id'],
                    ['seller_tiers', 'sellers.role', 'seller_tiers.id']
                ], ['sellers.*', 'users.name as username', 'users.email', 'seller_tiers.name as tiername']);
            }

            return view('content.seller', compact('sellers', 'tiers', 'selectedtier', 'search'));
        } else if (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect('/a');
        }
    }
    public function createShop()
    {
        if (session('role') == 2) {
            return view('operate.editshop');
        } else if (session('role')) {
            return redirect("/")->with("error", "Unauthorized account, Access not allowed");
        } else {
            return redirect('/a');
        }
    }
}
