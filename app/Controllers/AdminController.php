<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\User;
use Config\Database;

class AdminController extends BaseController
{
    private function input(?string $key = null, $default = null)
    {
        $json = $this->request->getJSON(true);
        $data = is_array($json) && !empty($json)
            ? $json
            : ($this->request->getPost() ?: []);

        if ($key === null) return $data;
        return $data[$key] ?? $default;
    }

    private function validateInput(array $rules): bool
    {
        $data       = $this->input();
        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            $this->validationErrors = $validation->getErrors();
            return false;
        }
        return true;
    }

    private array $validationErrors = [];

    public function index()
    {
        return view('dashboard/admin/index');
    }

    // -------------------------------------------------------
    // PRODUCTS
    // -------------------------------------------------------

    public function products()
    {
        $db      = Database::connect();
        $perPage = $this->perPage();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $offset  = ($page - 1) * $perPage;

        $builder = $db->table('products')
            ->select('products.id, products.category_id, products.name, products.price, products.stock, products.status, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->orderBy('products.id', 'DESC');

        $total = (clone $builder)->countAllResults(false);
        $data  = (clone $builder)->limit($perPage, $offset)->get()->getResultArray();

        return $this->paginatedResponse($data, $total, $page, $perPage);
    }

    public function product_add()
    {
        if (!$this->validateInput([
            'name'        => 'required|max_length[255]',
            'category_id' => 'required|integer|is_not_unique[categories.id]',
            'price'       => 'required|decimal|greater_than_equal_to[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'status'      => 'required|in_list[1,0]',
        ])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validationErrors,
            ])->setStatusCode(422);
        }

        $db = Database::connect();
        $db->transStart();

        $productModel = new Product();
        $productModel->insert([
            'name'        => $this->input('name'),
            'category_id' => $this->input('category_id'),
            'price'       => $this->input('price'),
            'stock'       => $this->input('stock'),
            'status'      => $this->input('status'),
        ]);

        $productId = $productModel->getInsertID();
        $stock     = (int) $this->input('stock');

        $this->createStockLog($productId, 'in', $stock, 0, $stock, 'Stok awal produk');

        $db->transComplete();

        $product = $productModel->find($productId);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product,
        ]);
    }

    public function product_update(int $id)
    {
        $db           = Database::connect();
        $productModel = new Product();
        $product      = $productModel->find($id);

        if (!$product) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Produk tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Bug #1 Fix: is_not_unique[categories.id]
        // Bug #3 & #4 Fix: pakai validateInput()
        if (!$this->validateInput([
            'name'        => 'required|max_length[255]',
            'category_id' => 'required|integer|is_not_unique[categories.id]',
            'price'       => 'required|decimal|greater_than_equal_to[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'status'      => 'required|in_list[1,0]',
        ])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validationErrors,
            ])->setStatusCode(422);
        }

        $db->transStart();

        $beforeStock = (int) $product['stock'];
        $afterStock  = (int) $this->input('stock');

        $productModel->update($id, [
            'name'        => $this->input('name'),
            'category_id' => $this->input('category_id'),
            'price'       => $this->input('price'),
            'stock'       => $afterStock,
            'status'      => $this->input('status'),
        ]);

        if ($beforeStock !== $afterStock) {
            $difference = $afterStock - $beforeStock;
            $type       = $difference > 0 ? 'in' : 'out';
            $this->createStockLog($id, $type, abs($difference), $beforeStock, $afterStock, 'Perubahan stok dari edit produk');
        }

        $db->transComplete();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Produk berhasil diperbarui',
            'data'    => $productModel->find($id),
        ]);
    }

    public function product_delete(int $id)
{
    $db           = Database::connect();
    $productModel = new Product();
    $product      = $productModel->find($id);

    if (!$product) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Produk tidak ditemukan',
        ])->setStatusCode(404);
    }

    $db->transStart();

    $stock = (int) $product['stock'];
    if ($stock > 0) {
        $this->createStockLog($id, 'out', $stock, $stock, 0, 'Produk dihapus');
    }

    $db->table('stock_logs')->where('product_id', $id)->delete();

    $db->table('order_items')->where('product_id', $id)->update(['product_id' => null]);

    $db->table('transaction_details')->where('product_id', $id)->update(['product_id' => null]);

    $productModel->delete($id);

    $db->transComplete();

    if (!$db->transStatus()) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Gagal menghapus produk',
        ])->setStatusCode(500);
    }

    return $this->response->setJSON([
        'status'  => true,
        'message' => 'Produk berhasil dihapus',
    ]);
}

    // -------------------------------------------------------
    // STOCK LOGS
    // -------------------------------------------------------

    public function stock_logs()
    {
        $db      = Database::connect();
        $perPage = $this->perPage();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $offset  = ($page - 1) * $perPage;

        $builder = $db->table('stock_logs')
            ->select('stock_logs.id, stock_logs.product_id, stock_logs.user_id, stock_logs.type, stock_logs.quantity, stock_logs.before_stock, stock_logs.after_stock, stock_logs.note, stock_logs.created_at, products.name as product_name, users.name as user_name')
            ->join('products', 'products.id = stock_logs.product_id', 'left')
            ->join('users',    'users.id = stock_logs.user_id',       'left')
            ->orderBy('stock_logs.id', 'DESC');

        $total = (clone $builder)->countAllResults(false);
        $data  = (clone $builder)->limit($perPage, $offset)->get()->getResultArray();

        return $this->paginatedResponse($data, $total, $page, $perPage);
    }

    // -------------------------------------------------------
    // ORDERS
    // -------------------------------------------------------

    public function orders()
    {
        $db      = Database::connect();
        $perPage = $this->perPage();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $offset  = ($page - 1) * $perPage;
        $status  = $this->request->getGet('status');
        $period  = $this->request->getGet('period');

        $builder = $db->table('orders')->orderBy('id', 'DESC');

        if (!empty($status) && $status !== 'all') {
            $builder->where('status', $status);
        }

        if (!empty($period) && $period !== 'all') {
            match ($period) {
                'today' => $builder->where('DATE(created_at)', date('Y-m-d')),
                'week'  => $builder->where('created_at >=', date('Y-m-d', strtotime('monday this week')))
                                   ->where('created_at <=', date('Y-m-d', strtotime('sunday this week'))),
                'month' => $builder->where('MONTH(created_at)', date('m'))
                                   ->where('YEAR(created_at)',  date('Y')),
                default => null,
            };
        }

        $total  = (clone $builder)->countAllResults(false);
        $orders = (clone $builder)->limit($perPage, $offset)->get()->getResultArray();

        $orderModel = new Order();
        foreach ($orders as &$order) {
            $order['items'] = $orderModel->getItems($order['id']);
        }
        unset($order);

        return $this->paginatedResponse($orders, $total, $page, $perPage);
    }

    // -------------------------------------------------------
    // TRANSACTIONS
    // -------------------------------------------------------

    public function transactions()
    {
        $db      = Database::connect();
        $perPage = $this->perPage();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $offset  = ($page - 1) * $perPage;
        $date    = $this->request->getGet('date');

        $hasOrderId         = $db->fieldExists('order_id',         'transactions');
        $hasInvoiceCode     = $db->fieldExists('invoice_code',      'transactions');
        $hasPaymentMethodId = $db->fieldExists('payment_method_id', 'transactions');
        $hasPayment         = $db->fieldExists('payment',           'transactions');
        $hasChangeAmount    = $db->fieldExists('change_amount',     'transactions');
        $hasCreatedAt       = $db->fieldExists('created_at',        'transactions');
        $hasTransDetails    = $db->tableExists('transaction_details');

        $builder = $db->table('transactions')->select('transactions.id, transactions.total');

        if ($hasInvoiceCode)     $builder->select('transactions.invoice_code');
        if ($hasPayment)         $builder->select('transactions.payment');
        if ($hasChangeAmount)    $builder->select('transactions.change_amount');
        if ($hasCreatedAt)       $builder->select('transactions.created_at');

        if ($hasOrderId) {
            $builder->select('transactions.order_id, orders.code as order_code, orders.item_count as order_item_count')
                    ->join('orders', 'orders.id = transactions.order_id', 'left');
        }

        if ($hasPaymentMethodId) {
            $builder->select('transactions.payment_method_id, payments.name as payment_method_name')
                    ->join('payments', 'payments.id = transactions.payment_method_id', 'left');
        }

        if ($hasTransDetails) {
            $builder->select('COALESCE(SUM(transaction_details.qty), 0) as detail_item_count', false)
                    ->join('transaction_details', 'transaction_details.transaction_id = transactions.id', 'left');

            $groupFields = ['transactions.id', 'transactions.total'];
            if ($hasInvoiceCode)     $groupFields[] = 'transactions.invoice_code';
            if ($hasOrderId)         array_push($groupFields, 'transactions.order_id', 'orders.code', 'orders.item_count');
            if ($hasPaymentMethodId) array_push($groupFields, 'transactions.payment_method_id', 'payments.name');
            if ($hasPayment)         $groupFields[] = 'transactions.payment';
            if ($hasChangeAmount)    $groupFields[] = 'transactions.change_amount';
            if ($hasCreatedAt)       $groupFields[] = 'transactions.created_at';

            $builder->groupBy(implode(', ', $groupFields));
        }

        if (!empty($date)) {
            $builder->where('DATE(transactions.created_at)', $date);
        }

        $total = (clone $builder)->countAllResults(false);
        $rows  = (clone $builder)->orderBy('transactions.id', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        $transactions = array_map(function ($trx) use ($hasPaymentMethodId, $hasPayment, $hasChangeAmount, $hasCreatedAt, $hasOrderId) {
            $paymentMethodName = $hasPaymentMethodId ? ($trx['payment_method_name'] ?? null) : null;

            return [
                'id'                  => $trx['id'],
                'code'                => $trx['invoice_code'] ?? ('TRX-' . str_pad((string) $trx['id'], 4, '0', STR_PAD_LEFT)),
                'order_code'          => $hasOrderId ? ($trx['order_code'] ?? null) : null,
                'item_count'          => (int) ($trx['order_item_count'] ?? $trx['detail_item_count'] ?? 0),
                'payment_method_id'   => $trx['payment_method_id'] ?? null,
                'payment_method_name' => $paymentMethodName ?? '-',
                'total'               => (float) $trx['total'],
                'payment'             => $hasPayment ? (float) ($trx['payment'] ?? 0) : (float) $trx['total'],
                'change_amount'       => $hasChangeAmount ? (float) ($trx['change_amount'] ?? 0) : 0,
                'status'              => 'success',
                'created_at'          => $hasCreatedAt ? ($trx['created_at'] ?? null) : null,
            ];
        }, $rows);

        return $this->paginatedResponse($transactions, $total, $page, $perPage);
    }

    // -------------------------------------------------------
    // REPORTS
    // -------------------------------------------------------

    public function reports_data()
    {
        $db           = Database::connect();
        $startOfMonth = date('Y-m-01 00:00:00');
        $endOfMonth   = date('Y-m-t 23:59:59');

        $monthlyTransactions = $db->table('transactions')
            ->select('total, created_at as transaction_date')
            ->where('created_at >=', $startOfMonth)
            ->where('created_at <=', $endOfMonth)
            ->get()->getResultArray();

        $daysInMonth = (int) date('t');
        $weeklySales = [];

        for ($week = 1; $week <= 4; $week++) {
            $startDay = (($week - 1) * 7) + 1;
            $endDay   = $week === 4 ? $daysInMonth : $week * 7;
            $total    = 0;

            foreach ($monthlyTransactions as $trx) {
                if (empty($trx['transaction_date'])) continue;
                $day = (int) date('j', strtotime($trx['transaction_date']));
                if ($day >= $startDay && $day <= $endDay) {
                    $total += (float) $trx['total'];
                }
            }

            $weeklySales[] = ['label' => 'Minggu ' . $week, 'total' => $total];
        }

        $categorySales = $db->table('categories')
            ->select('categories.name, COALESCE(SUM(order_items.subtotal), 0) as total', false)
            ->join('products',    'products.category_id = categories.id',  'left')
            ->join('order_items', 'order_items.product_id = products.id',  'left')
            ->join('orders',      'orders.id = order_items.order_id',      'left')
            ->groupStart()
                ->where('orders.id IS NULL', null, false)
                ->orGroupStart()
                    ->where('orders.status', 'completed')
                    ->where('orders.created_at >=', $startOfMonth)
                    ->where('orders.created_at <=', $endOfMonth)
                ->groupEnd()
            ->groupEnd()
            ->groupBy('categories.id, categories.name')
            ->get()->getResultArray();

        $monthRevenue    = array_sum(array_column($monthlyTransactions, 'total'));
        $totalRevenue    = $db->table('transactions')->selectSum('total')->get()->getRowArray()['total'] ?? 0;
        $totalOrders     = $db->table('orders')->countAllResults();
        $completedOrders = $db->table('orders')->where('status', 'completed')->countAllResults();
        $totalTrx        = $db->table('transactions')->countAllResults();

        return $this->response->setJSON([
            'status' => true,
            'data'   => [
                'summary' => [
                    'total_revenue'      => (float) $totalRevenue,
                    'month_revenue'      => (float) $monthRevenue,
                    'total_orders'       => $totalOrders,
                    'completed_orders'   => $completedOrders,
                    'total_transactions' => $totalTrx,
                ],
                'weekly_sales'   => $weeklySales,
                'category_sales' => $categorySales,
            ],
        ]);
    }

    // -------------------------------------------------------
    // DASHBOARD
    // -------------------------------------------------------

    public function dashboard_data()
    {
        $db    = Database::connect();
        $today = date('Y-m-d');

        $totalProducts    = $db->table('products')->countAllResults();
        $activeProducts   = $db->table('products')->where('status', 1)->countAllResults();
        $inactiveProducts = $db->table('products')->where('status', 0)->countAllResults();
        $totalStock       = $db->table('products')->selectSum('stock')->get()->getRowArray()['stock'] ?? 0;
        $lowStockProducts = $db->table('products')->where('stock <=', 5)->countAllResults();

        $stockInToday = $db->table('stock_logs')
            ->selectSum('quantity')
            ->where('DATE(created_at)', $today)
            ->where('type', 'in')
            ->get()->getRowArray()['quantity'] ?? 0;

        $stockOutToday = $db->table('stock_logs')
            ->selectSum('quantity')
            ->where('DATE(created_at)', $today)
            ->where('type', 'out')
            ->get()->getRowArray()['quantity'] ?? 0;

        $stockActivitiesToday = $db->table('stock_logs')
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        $categoryStats = $db->table('categories')
            ->select('categories.id, categories.name, COUNT(products.id) as total_products', false)
            ->join('products', 'products.category_id = categories.id', 'left')
            ->groupBy('categories.id, categories.name')
            ->orderBy('categories.name')
            ->get()->getResultArray();

        $latestProducts = $db->table('products')
            ->select('products.id, products.name, products.price, products.stock, products.status, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->orderBy('products.id', 'DESC')
            ->limit(5)->get()->getResultArray();

        $topStockProducts = $db->table('products')
            ->select('products.id, products.name, products.price, products.stock, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->orderBy('products.stock', 'DESC')
            ->limit(5)->get()->getResultArray();

        $lowStockItems = $db->table('products')
            ->select('products.id, products.name, products.stock, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.stock <=', 5)
            ->orderBy('products.stock', 'ASC')
            ->limit(5)->get()->getResultArray();

        $recentStockLogs = $db->table('stock_logs')
            ->select('stock_logs.id, stock_logs.type, stock_logs.quantity, stock_logs.before_stock, stock_logs.after_stock, stock_logs.note, stock_logs.created_at, products.name as product_name, users.name as user_name')
            ->join('products', 'products.id = stock_logs.product_id', 'left')
            ->join('users',    'users.id = stock_logs.user_id',       'left')
            ->orderBy('stock_logs.id', 'DESC')
            ->limit(5)->get()->getResultArray();

        return $this->response->setJSON([
            'status' => true,
            'data'   => [
                'summary' => [
                    'total_products'         => $totalProducts,
                    'active_products'        => $activeProducts,
                    'inactive_products'      => $inactiveProducts,
                    'total_stock'            => (int) $totalStock,
                    'low_stock_products'     => $lowStockProducts,
                    'stock_in_today'         => (int) $stockInToday,
                    'stock_out_today'        => (int) $stockOutToday,
                    'stock_activities_today' => $stockActivitiesToday,
                ],
                'category_stats'     => $categoryStats,
                'latest_products'    => $latestProducts,
                'top_stock_products' => $topStockProducts,
                'low_stock_items'    => $lowStockItems,
                'recent_stock_logs'  => $recentStockLogs,
            ],
        ]);
    }

    // -------------------------------------------------------
    // CATEGORIES
    // -------------------------------------------------------

    public function categories()
    {
        $db   = Database::connect();
        $data = $db->table('categories')->select('id, name')->get()->getResultArray();

        return $this->response->setJSON(['status' => true, 'data' => $data]);
    }

    // -------------------------------------------------------
    // PAYMENTS
    // -------------------------------------------------------

    public function payments()
    {
        $db      = Database::connect();
        $perPage = $this->perPage();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $offset  = ($page - 1) * $perPage;

        $total = $db->table('payments')->countAllResults();
        $data  = $db->table('payments')->select('id, name')->orderBy('id')->limit($perPage, $offset)->get()->getResultArray();

        return $this->paginatedResponse($data, $total, $page, $perPage);
    }

    public function payment_add()
    {
        if (!$this->validateInput(['name' => 'required|max_length[50]'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validationErrors,
            ])->setStatusCode(422);
        }

        $db   = Database::connect();
        $name = $this->input('name');
        $db->table('payments')->insert(['name' => $name]);
        $id = $db->insertID();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Metode pembayaran berhasil ditambahkan',
            'data'    => ['id' => $id, 'name' => $name],
        ]);
    }

    public function payment_update(int $id)
    {
        $db     = Database::connect();
        $exists = $db->table('payments')->where('id', $id)->countAllResults() > 0;

        if (!$exists) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Metode pembayaran tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Bug #3 & #4 Fix: pakai validateInput()
        if (!$this->validateInput(['name' => 'required|max_length[50]'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validationErrors,
            ])->setStatusCode(422);
        }

        $db->table('payments')->where('id', $id)->update(['name' => $this->input('name')]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Metode pembayaran berhasil diperbarui',
        ]);
    }

    public function payment_delete(int $id)
    {
        $db     = Database::connect();
        $exists = $db->table('payments')->where('id', $id)->countAllResults() > 0;

        if (!$exists) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Metode pembayaran tidak ditemukan',
            ])->setStatusCode(404);
        }

        if ($db->fieldExists('payment_method_id', 'transactions')) {
            $used = $db->table('transactions')->where('payment_method_id', $id)->countAllResults() > 0;

            if ($used) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Metode pembayaran sudah digunakan transaksi dan tidak bisa dihapus',
                ])->setStatusCode(422);
            }
        }

        $db->table('payments')->where('id', $id)->delete();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Metode pembayaran berhasil dihapus',
        ]);
    }

    // -------------------------------------------------------
    // ROLES
    // -------------------------------------------------------

    public function roles()
    {
        $db = Database::connect();

        $data = $db->table('roles')
            ->select('id, name')
            ->whereIn('name', ['owner', 'admin', 'kasir'])
            ->orderBy("FIELD(name, 'owner', 'admin', 'kasir')", '', false)
            ->get()->getResultArray();

        return $this->response->setJSON(['status' => true, 'data' => $data]);
    }

    // -------------------------------------------------------
    // ACCOUNTS
    // -------------------------------------------------------

    public function accounts()
    {
        $db      = Database::connect();
        $perPage = $this->perPage();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $offset  = ($page - 1) * $perPage;

        $total = $db->table('users')->countAllResults();
        $data  = $db->table('users')
            ->select('users.id, users.name, users.username, users.email, users.role_id, users.work_hours, users.status, roles.name as role_name, users.created_at')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->orderBy('users.id', 'DESC')
            ->limit($perPage, $offset)
            ->get()->getResultArray();

        return $this->paginatedResponse($data, $total, $page, $perPage);
    }

    public function account_add()
    {
        // Bug #1 Fix: is_not_unique[roles.id] — titik, bukan koma
        // Bug #3 & #4 Fix: pakai validateInput() yang baca JSON body
        if (!$this->validateInput([
            'name'       => 'required|max_length[255]',
            'username'   => 'required|max_length[255]|alpha_dash|is_unique[users.username]',
            'email'      => 'required|valid_email|max_length[255]|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'role_id'    => 'required|integer|is_not_unique[roles.id]',
            'work_hours' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[10000]',
            'status'     => 'permit_empty|in_list[online,offline]',
        ])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validationErrors,
            ])->setStatusCode(422);
        }

        $db    = Database::connect();
        $valid = $db->table('roles')
            ->whereIn('name', ['owner', 'admin', 'kasir'])
            ->where('id', (int) $this->input('role_id', 0))
            ->countAllResults() > 0;

        if (!$valid) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => ['role_id' => 'Role tidak valid'],
            ])->setStatusCode(422);
        }

        $userModel = new User();
        $userModel->insert([
            'name'       => $this->input('name'),
            'username'   => $this->input('username'),
            'email'      => $this->input('email'),
            'password'   => $this->input('password'),
            'role_id'    => (int) $this->input('role_id'),
            'work_hours' => (int) $this->input('work_hours', 0),
            'status'     => $this->input('status', 'offline'),
        ]);

        // Bug #2 Fix: pakai getInsertID() dari model, bukan $db->insertID()
        $newId   = $userModel->getInsertID();
        $account = $userModel->find($newId);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Akun berhasil ditambahkan',
            'data'    => $account,
        ]);
    }

    public function account_update(int $id)
    {
        $userModel = new User();
        $user      = $userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Akun tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Bug #1 Fix: is_not_unique[roles.id]
        // Bug #3 & #4 Fix: pakai validateInput()
        if (!$this->validateInput([
            'name'       => 'required|max_length[255]',
            'username'   => "required|max_length[255]|alpha_dash|is_unique[users.username,id,{$id}]",
            'email'      => "required|valid_email|max_length[255]|is_unique[users.email,id,{$id}]",
            'password'   => 'permit_empty|min_length[6]',
            'role_id'    => 'required|integer|is_not_unique[roles.id]',
            'work_hours' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[10000]',
            'status'     => 'permit_empty|in_list[online,offline]',
        ])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validationErrors,
            ])->setStatusCode(422);
        }

        $data = [
            'name'       => $this->input('name'),
            'username'   => $this->input('username'),
            'email'      => $this->input('email'),
            'role_id'    => (int) $this->input('role_id'),
            'work_hours' => (int) $this->input('work_hours', 0),
            'status'     => $this->input('status', 'offline'),
        ];

        // Password hanya diupdate jika diisi
        if ($this->input('password')) {
            $data['password'] = $this->input('password');
        }

        $userModel->update($id, $data);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Akun berhasil diperbarui',
            'data'    => $userModel->find($id),
        ]);
    }

    public function account_delete(int $id)
    {
        $userModel = new User();
        $user      = $userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Akun tidak ditemukan',
            ])->setStatusCode(404);
        }

        if ((int) session()->get('user_id') === $id) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Akun yang sedang digunakan tidak bisa dihapus',
            ])->setStatusCode(422);
        }

        $userModel->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Akun berhasil dihapus',
        ]);
    }

    // -------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------

    private function createStockLog(int $productId, string $type, int $quantity, int $beforeStock, int $afterStock, string $note): void
    {
        $stockLogModel = new StockLog();
        $stockLogModel->insert([
            'product_id'   => $productId,
            'user_id'      => session()->get('user_id'),
            'type'         => $type,
            'quantity'     => $quantity,
            'before_stock' => $beforeStock,
            'after_stock'  => $afterStock,
            'note'         => $note,
        ]);
    }

    private function perPage(): int
    {
        return max(1, min((int) ($this->request->getGet('per_page') ?? 10), 10));
    }

    private function paginatedResponse(array $data, int $total, int $page, int $perPage)
    {
        $lastPage = (int) ceil($total / $perPage);
        $from     = $total > 0 ? (($page - 1) * $perPage) + 1 : null;
        $to       = $total > 0 ? min($page * $perPage, $total) : null;

        return $this->response->setJSON([
            'status'     => true,
            'data'       => $data,
            'pagination' => [
                'current_page' => $page,
                'last_page'    => $lastPage,
                'per_page'     => $perPage,
                'total'        => $total,
                'from'         => $from,
                'to'           => $to,
            ],
        ]);
    }
}