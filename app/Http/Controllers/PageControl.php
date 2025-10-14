<?php

namespace App\Http\Controllers;
use App\Models\DataModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PageControl extends Controller
{
    function index()
    {
        if (session('role') == 3 || session('role') == 2) {
            return view("home");
        } else if (session("role") == 1) {
            $model = new DataModel();
            $order = $model->getWhere('orders', ['customer_id' => Session('id')], 3,['orders.id','total_order']);
            return view("customerhome", compact("order"));
        } else {
            return redirect('/login')->with("error", "Access denied, please login.");
        }
    }

    function product(Request $request)
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $category = $request->input('category');
            $search = $request->input('search');
            $categories = $model->getData('categories');
            if ($category || $search) {
                $products = $model->getFilteredData('products', [['categories', 'products.category', 'categories.id']], ['category' => $category], $search, ['products.name', 'products.description'], ['products.*', 'categories.name as categoryname']);
            } else {
                $products = $model->getJoinData("products", [["categories", "products.category", "categories.id"]], ['products.*', 'categories.name as categoryname']);
            }
            return view("content.product", compact('products', 'categories', 'category', 'search'));

        } else {
            $model = new DataModel();
            $category = $request->input('category');
            $search = $request->input('search');
            $categories = $model->getData('categories');
            if ($category || $search) {
                $products = $model->getFilteredData('products', [['categories', 'products.category', 'categories.id']], ['category' => $category], $search, ['products.name', 'products.description'], ['products.*', 'categories.name as categoryname']);
            } else {
                $products = $model->getJoinData("products", [["categories", "products.category", "categories.id"]], ['products.*', 'categories.name as categoryname']);
            }
            return view("content.customerproduct", compact('products', 'categories', 'category', 'search'));
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

        } else {
            $model = new DataModel();
            $selectedrole = $request->input('selectedrole');
            $search = $request->input('search');
            $roles = $model->getData('roles');
            if ($selectedrole || $search) {
                $data = $model->getFilteredData('users', [['roles', 'users.role', 'roles.id']], ['role' => $selectedrole], $search, ['users.name', 'users.email'], ['users.*', 'roles.name as rolename']);
            } else {
                $data = $model->getJoinData("users", [["roles", "users.role", "roles.id"]], ['users.*', 'roles.name as rolename']);
            }
            return view("content.customeruser", compact('data', 'roles', 'selectedrole', 'search'));
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
        if (session('role') == 3) {
            $model = new DataModel();
            $kategori = $model->getData("categories");
            $data=null;
            return view("operate.editproduct", compact('kategori', 'data'));
        } else {
            return redirect('/')->with("error", "Access denied");
        }
    }
    function editproduct($id)
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $data = $model->getJoinData('products', [
                ['categories', 'products.category', 'categories.id']
            ], ['products.*', 'categories.name as catname'], ['products.id' => $id], true);
            $kategori = $model->getData("categories");
            return view("operate.editproduct", compact('data', 'kategori'));
        } else {
            return redirect('/')->with("error", "Access denied");
        }
    }
    function tambahuser()
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $role = $model->getData("roles");
            return view("operate.tambahuser", compact('role'));
        } else {
            return redirect('/')->with("error", "Access denied");
        }
    }
    function edituser($id)
    {
        if (session('role') == 3) {
            $model = new DataModel();
            $data = $model->getJoinData('users', [
                ['roles', 'users.role', 'roles.id']
            ], ['users.*', 'roles.name as rolename'], ['users.id' => $id], true);
            $role = $model->getData("roles");
            return view("operate.edituser", compact('data', 'role'));
        } else {
            return redirect('/')->with("error", "Access denied");
        }
    }
    public function monitorcategory()
    {
        if (session('role') == 3) {
            $data = DB::table('categories')
                ->leftJoin('products', 'categories.id', '=', 'products.category')
                ->select('categories.id', 'categories.name', DB::raw('COUNT(products.id) as total_items'))
                ->groupBy('categories.id', 'categories.name')
                ->get();

            session()->put("back", "product");
            return view("monitor", compact('data'));
        } else {
            return redirect('/')->with("error", "Access denied");
        }
    }
    public function monitorrole()
    {
        if (session('role') == 3) {
            $data = DB::table('roles')
                ->leftJoin('users', 'roles.id', '=', 'users.role')
                ->select('roles.id', 'roles.name', DB::raw('COUNT(users.id) as total_items'))
                ->groupBy('roles.id', 'roles.name')
                ->get();

            session()->put("back", "user");
            return view("monitor", compact('data'));

        } else {
            return redirect('/')->with("error", "Access denied");
        }
    }
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('content.cart', compact('cart'));
    }
}
