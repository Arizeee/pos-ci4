<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockLog;
use CodeIgniter\HTTP\ResponseInterface;

class KasirController extends BaseController
{
    // =========================================================================
    // Pages
    // =========================================================================

    public function index()
    {
        return view('dashboard/kasir/index');
    }

    // =========================================================================
    // API – Products
    // =========================================================================

    public function products()
    {
        $db = \Config\Database::connect();

        $products = $db->table('products')
            ->select('products.id, products.name, products.price, products.stock, products.status, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('products.name')
            ->get()
            ->getResultArray();

        return $this->jsonResponse(['status' => true, 'data' => $products]);
    }

    public function product_add()
    {
        $json = $this->request->getJSON(true);

        $rules = [
            'name'        => 'required|max_length[255]',
            'category_id' => 'required|integer',
            'price'       => 'required|greater_than[0]',
            'status'      => 'required|in_list[0,1]',
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ], 422);
        }

        $db = \Config\Database::connect();
        $db->table('products')->insert([
            'name'        => $json['name'],
            'category_id' => (int) $json['category_id'],
            'price'       => (float) $json['price'],
            'stock'       => (int) ($json['stock'] ?? 0),
            'status'      => (int) $json['status'],
        ]);

        return $this->jsonResponse(['status' => true, 'message' => 'Produk berhasil ditambahkan']);
    }

    public function product_update($id)
    {
        $json = $this->request->getJSON(true);

        $rules = [
            'name'        => 'required|max_length[255]',
            'category_id' => 'required|integer',
            'price'       => 'required|greater_than[0]',
            'status'      => 'required|in_list[0,1]',
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ], 422);
        }

        $db      = \Config\Database::connect();
        $product = $db->table('products')->where('id', $id)->get()->getRowArray();

        if (!$product) {
            return $this->jsonResponse(['status' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $db->table('products')->where('id', $id)->update([
            'name'        => $json['name'],
            'category_id' => (int) $json['category_id'],
            'price'       => (float) $json['price'],
            'stock'       => isset($json['stock']) ? (int) $json['stock'] : (int) $product['stock'],
            'status'      => (int) $json['status'],
        ]);

        return $this->jsonResponse(['status' => true, 'message' => 'Produk berhasil diperbarui']);
    }

    public function product_delete($id)
    {
        $db      = \Config\Database::connect();
        $product = $db->table('products')->where('id', $id)->get()->getRowArray();

        if (!$product) {
            return $this->jsonResponse(['status' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $db->table('products')->where('id', $id)->delete();

        return $this->jsonResponse(['status' => true, 'message' => 'Produk berhasil dihapus']);
    }

    public function product_toggle($id)
    {
        $db      = \Config\Database::connect();
        $product = $db->table('products')->where('id', $id)->get()->getRowArray();

        if (!$product) {
            return $this->jsonResponse(['status' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $newStatus = (int) $product['status'] === 1 ? 0 : 1;
        $db->table('products')->where('id', $id)->update(['status' => $newStatus]);

        return $this->jsonResponse([
            'status'     => true,
            'message'    => 'Status produk diperbarui',
            'new_status' => $newStatus,
        ]);
    }

    // =========================================================================
    // API – Categories
    // =========================================================================

    public function categories()
    {
        $db = \Config\Database::connect();

        $categories = $db->table('categories')
            ->select('id, name')
            ->orderBy('name')
            ->get()
            ->getResultArray();

        return $this->jsonResponse(['status' => true, 'data' => $categories]);
    }

    // =========================================================================
    // API – Payments
    // =========================================================================

    public function payments()
    {
        $db = \Config\Database::connect();

        $payments = $db->table('payments')
            ->select('id, name')
            ->orderBy('id')
            ->get()
            ->getResultArray();

        return $this->jsonResponse(['status' => true, 'data' => $payments]);
    }

    // =========================================================================
    // API – Checkout
    // =========================================================================

    public function checkout()
    {
        $json = $this->request->getJSON(true);

        $rules = [
            'items'             => 'required',
            'payment_method_id' => 'required|integer',
            'payment'           => 'permit_empty|greater_than_equal_to[0]',
            'customer_name'     => 'permit_empty|max_length[255]',
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ], 422);
        }

        $db = \Config\Database::connect();

        try {
            // dd($json);
            $db->transStart();

            $items      = $json['items'];
            $itemCount  = array_sum(array_column($items, 'quantity'));
            $subtotal   = array_sum(array_map(fn($i) => $i['quantity'] * $i['price'], $items));
            $settings   = $this->appSettings();
            $taxPercent = (float) ($settings['tax_percent'] ?? 10);
            $taxAmount  = round($subtotal * ($taxPercent / 100));
            $total      = $subtotal + $taxAmount;

            $payment = $db->table('payments')->where('id', $json['payment_method_id'])->get()->getRowArray();
            if (!$payment) {
                return $this->jsonResponse(['status' => false, 'message' => 'Metode pembayaran tidak ditemukan.'], 422);
            }

            $paymentMethod = $this->normalizePaymentMethod($payment['name']);
            $paymentAmount = $paymentMethod === 'cash' ? (float) ($json['payment'] ?? 0) : $total;
            $changeAmount  = $paymentMethod === 'cash' ? max(0, $paymentAmount - $total) : 0;

            if ($paymentMethod === 'cash' && $paymentAmount < $total) {
                return $this->jsonResponse([
                    'status'  => false,
                    'message' => 'Uang yang diterima kurang dari total pembayaran.',
                    'errors'  => ['payment' => 'Uang yang diterima kurang dari total pembayaran.'],
                ], 422);
            }

            // Buat Order
            $orderModel = new Order();
            $maxId      = (int) ($db->table('orders')->selectMax('id')->get()->getRowArray()['id'] ?? 0);
            $orderCode  = 'ORD-' . str_pad((string) ($maxId + 1), 4, '0', STR_PAD_LEFT);

            $orderModel->insert([
                'code'          => $orderCode,
                'customer_name' => $json['customer_name'] ?? 'Pelanggan',
                'status'        => 'completed',
                'item_count'    => $itemCount,
                'total'         => $total,
                'created_by'    => (int) session()->get('user_id'), 
            ]);
            $orderId = $orderModel->getInsertID();

            // Order Items & Stok
            $orderItemModel = new OrderItem();
            $stockLogModel  = new StockLog();
            $productModel   = new Product();

            foreach ($items as $item) {
                $product = $db->query(
                    'SELECT * FROM products WHERE id = ? FOR UPDATE',
                    [(int) $item['id']]
                )->getRowArray();

                if (!$product) {
                    $db->transRollback();
                    return $this->jsonResponse(['status' => false, 'message' => 'Produk tidak ditemukan.'], 422);
                }

                if ((int) $product['stock'] <= 0) {
                    $db->transRollback();
                    return $this->jsonResponse(['status' => false, 'message' => "Stok {$product['name']} sudah habis."], 422);
                }

                if ((int) $product['stock'] < (int) $item['quantity']) {
                    $db->transRollback();
                    return $this->jsonResponse([
                        'status'  => false,
                        'message' => "Stok {$product['name']} hanya tersisa {$product['stock']}.",
                    ], 422);
                }

                $orderItemModel->insert([
                    'order_id'     => (int) $orderId,
                    'product_id'   => (int) $product['id'],
                    'product_name' => (string) $product['name'],
                    'quantity'     => (int) $item['quantity'],
                    'price'        => (float) $item['price'],
                    'subtotal'     => (float) ((int) $item['quantity'] * (float) $item['price']),
                ]);

                $beforeStock = (int) $product['stock'];
                $afterStock  = $beforeStock - (int) $item['quantity'];

                // Pakai query builder langsung, bukan $productModel->update()
                $db->table('products')
                ->where('id', (int) $product['id'])
                ->update(['stock' => (int) $afterStock]);

                $stockLogModel->insert([
                    'product_id'   => (int) $product['id'],
                    'user_id'      => (int) session()->get('user_id'),
                    'type'         => 'out',
                    'quantity'     => (int) $item['quantity'],
                    'before_stock' => (int) $beforeStock,
                    'after_stock'  => (int) $afterStock,
                    'note'         => (string) ('Penjualan kasir #' . $orderCode),
                ]);
            }

            // Buat Transaksi
            $maxTrxId        = (int) ($db->table('transactions')->selectMax('id')->get()->getRowArray()['id'] ?? 0);
            $transactionCode = 'TRX-' . str_pad((string) ($maxTrxId + 1), 4, '0', STR_PAD_LEFT);

            $fields          = $this->getTableColumns('transactions');
            $transactionData = [];
            $map = [
                'code'              => (string) $transactionCode,
                'invoice_code'      => (string) $transactionCode,
                'order_id'          => (int) $orderId,
                'user_id'           => (int) session()->get('user_id'), // ← ini juga
                'payment_method_id' => (int) $payment['id'],
                'payment_method'    => (string) $paymentMethod,
                'total'             => (float) $total,
                'payment'           => (float) $paymentAmount,
                'change_amount'     => (float) $changeAmount,
                'status'            => 'success',
                'paid_at'           => date('Y-m-d H:i:s'),
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ];

            foreach ($map as $col => $val) {
                if (in_array($col, $fields, true)) {
                    $transactionData[$col] = $val;
                }
            }

            $db->table('transactions')->insert($transactionData);
            $transactionId = $db->insertID();

            if ($this->tableExists('transaction_details')) {
                foreach ($items as $item) {
                    $db->table('transaction_details')->insert([
                        'transaction_id' => (int) $transactionId,
                        'product_id'     => (int) $item['id'],
                        'price'          => (float) $item['price'],
                        'qty'            => (int) $item['quantity'],
                        'subtotal'       => (float) ($item['quantity'] * $item['price']),
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->jsonResponse(['status' => false, 'message' => 'Transaksi gagal disimpan.'], 500);
            }

            $orderItems = $db->table('order_items')->where('order_id', $orderId)->get()->getResultArray();

            return $this->jsonResponse([
                'status'  => true,
                'message' => 'Pembayaran berhasil',
                'data'    => [
                    'order'       => array_merge(
                        $db->table('orders')->where('id', $orderId)->get()->getRowArray(),
                        ['items' => $orderItems]
                    ),
                    'transaction' => [
                        'id'                  => $transactionId,
                        'code'                => $transactionCode,
                        'payment_method_id'   => $payment['id'],
                        'payment_method'      => $paymentMethod,
                        'payment_method_name' => $payment['name'],
                        'subtotal'            => $subtotal,
                        'tax_percent'         => $taxPercent,
                        'tax_amount'          => $taxAmount,
                        'total'               => $total,
                        'payment'             => $paymentAmount,
                        'change_amount'       => $changeAmount,
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
    $db->transRollback();
    log_message('error', '[KasirController::checkout] ' . $e->getMessage());
    return $this->jsonResponse([
        'status'  => false,
        'message' => $e->getMessage(), // ← sementara tampilkan pesan aslinya
    ], 500);
}
    }

    // =========================================================================
    // API – Transactions (list)
    // =========================================================================

    public function transactions()
    {
        $result = $this->getTransactions();
        return $this->jsonResponse([
            'status'     => true,
            'data'       => $result['data'],
            'pagination' => $result['pagination'],
        ]);
    }

    // =========================================================================
    // API – Reports
    // =========================================================================

    public function reports_data()
    {
        $period                            = $this->request->getGet('period') ?? 'today';
        [$start, $end, $prevStart, $prevEnd] = $this->reportPeriodRanges($period);

        $current  = $this->transactionSummary($start, $end);
        $previous = $this->transactionSummary($prevStart, $prevEnd);

        return $this->jsonResponse([
            'status' => true,
            'data'   => [
                'summary' => [
                    'revenue'              => $current['revenue'],
                    'revenue_change'       => $this->percentageChange($current['revenue'], $previous['revenue']),
                    'transactions'         => $current['transactions'],
                    'transactions_change'  => $this->percentageChange($current['transactions'], $previous['transactions']),
                    'items'                => $current['items'],
                    'items_change'         => $this->percentageChange($current['items'], $previous['items']),
                    'average_order'        => $current['average_order'],
                    'average_order_change' => $this->percentageChange($current['average_order'], $previous['average_order']),
                ],
                'sales_chart'  => $this->salesChart($start, $end, $period),
                'top_products' => $this->reportTopProducts($start, $end),
                'categories'   => $this->reportCategories($start, $end),
            ],
        ]);
    }

    // =========================================================================
    // API – Settings
    // =========================================================================

    public function settings_data()
    {
        $db       = \Config\Database::connect();
        $payments = $db->table('payments')->select('id, name')->orderBy('id')->get()->getResultArray();
        $userId   = session()->get('user_id');
        $user     = null;

        if ($userId) {
            $row = $db->table('users')->where('id', $userId)->get()->getRowArray();
            if ($row) {
                $user = [
                    'name'       => $row['name'],
                    'username'   => $row['username'] ?? null,
                    'email'      => $row['email'],
                    'work_hours' => $row['work_hours'] ?? 0,
                    'status'     => $row['status'] ?? 'offline',
                ];
            }
        }

        return $this->jsonResponse([
            'status' => true,
            'data'   => [
                'settings' => $this->appSettings(),
                'payments' => $payments,
                'user'     => $user,
            ],
        ]);
    }

    public function settings_update()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'store_name'       => 'required|max_length[255]',
            'store_address'    => 'permit_empty|max_length[1000]',
            'store_phone'      => 'permit_empty|max_length[50]',
            'store_email'      => 'permit_empty|valid_email|max_length[255]',
            'receipt_footer'   => 'permit_empty|max_length[500]',
            'tax_percent'      => 'permit_empty|greater_than_equal_to[0]|less_than_equal_to[100]',
            'currency'         => 'permit_empty|max_length[10]',
            'paper_size'       => 'permit_empty|in_list[58mm,80mm]',
            'receipt_copies'   => 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[10]',
            'current_password' => 'permit_empty',
            'new_password'     => 'permit_empty|min_length[6]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ], 422);
        }

        $db = \Config\Database::connect();

        if (!empty($data['new_password'])) {
            $userId = session()->get('user_id');
            $user   = $db->table('users')->where('id', $userId)->get()->getRowArray();

            if (!$user || !password_verify($data['current_password'] ?? '', $user['password'])) {
                return $this->jsonResponse(['status' => false, 'message' => 'Password saat ini salah'], 422);
            }

            $db->table('users')->where('id', $userId)->update([
                'password' => password_hash($data['new_password'], PASSWORD_BCRYPT),
            ]);
        }

        unset($data['current_password'], $data['new_password'], $data['new_password_confirmation']);

        $now = date('Y-m-d H:i:s');
        foreach ($data as $key => $value) {
            $db->table('owner_settings')->replace([
                'key'        => $key,
                'value'      => is_bool($value) ? (int) $value : $value,
                'updated_at' => $now,
                'created_at' => $now,
            ]);
        }

        // Alias agar owner_settings juga update key business_*
        $aliases = [
            'business_name'    => $data['store_name']    ?? null,
            'business_address' => $data['store_address'] ?? null,
            'business_phone'   => $data['store_phone']   ?? null,
            'business_email'   => $data['store_email']   ?? null,
        ];

        foreach ($aliases as $key => $value) {
            if ($value === null) continue;
            $db->table('owner_settings')->replace([
                'key'        => $key,
                'value'      => $value,
                'updated_at' => $now,
                'created_at' => $now,
            ]);
        }

        return $this->jsonResponse(['status' => true, 'message' => 'Pengaturan berhasil disimpan']);
    }

    // =========================================================================
    // Private Helpers
    // =========================================================================

    private function jsonResponse(array $data, int $code = 200)
    {
        return $this->response->setStatusCode($code)->setJSON($data);
    }

    private function normalizePaymentMethod(string $name): string
    {
        $n = strtolower($name);
        if (str_contains($n, 'qris') || str_contains($n, 'qr'))   return 'qris';
        if (str_contains($n, 'transfer'))                          return 'transfer';
        if (str_contains($n, 'tunai') || str_contains($n, 'cash')) return 'cash';
        return str_replace(' ', '_', $n);
    }

    private function reportPeriodRanges(string $period): array
    {
        $now = new \DateTime();

        switch ($period) {
            case 'week':
                $start     = (clone $now)->modify('monday this week')->setTime(0, 0, 0);
                $end       = (clone $now)->modify('sunday this week')->setTime(23, 59, 59);
                $prevStart = (clone $start)->modify('-7 days');
                $prevEnd   = (clone $end)->modify('-7 days');
                break;
            case 'month':
                $start     = (clone $now)->modify('first day of this month')->setTime(0, 0, 0);
                $end       = (clone $now)->modify('last day of this month')->setTime(23, 59, 59);
                $prevStart = (clone $now)->modify('first day of last month')->setTime(0, 0, 0);
                $prevEnd   = (clone $now)->modify('last day of last month')->setTime(23, 59, 59);
                break;
            default: // today
                $start     = (clone $now)->setTime(0, 0, 0);
                $end       = (clone $now)->setTime(23, 59, 59);
                $prevStart = (clone $now)->modify('-1 day')->setTime(0, 0, 0);
                $prevEnd   = (clone $now)->modify('-1 day')->setTime(23, 59, 59);
        }

        $fmt = fn(\DateTime $d) => $d->format('Y-m-d H:i:s');
        return [$fmt($start), $fmt($end), $fmt($prevStart), $fmt($prevEnd)];
    }

    private function appSettings(): array
    {
        $db = \Config\Database::connect();
        if (!$this->tableExists('owner_settings')) return [];

        $rows     = $db->table('owner_settings')->get()->getResultArray();
        $settings = array_column($rows, 'value', 'key');

        return array_merge([
            'store_name'             => $settings['business_name']    ?? 'Warkop POS',
            'store_address'          => $settings['business_address'] ?? '',
            'store_phone'            => $settings['business_phone']   ?? '',
            'store_email'            => $settings['business_email']   ?? '',
            'receipt_footer'         => 'Terima kasih sudah berkunjung!',
            'hero_location'          => 'Tapos, Depok',
            'hero_title'             => 'Nongkrong Nyaman',
            'hero_highlight'         => 'Kopi Enak',
            'hero_description'       => 'Warkop Pos adalah tempat nongkrong favorit mahasiswa.',
            'menu_description'       => 'Geser tab kategori, lalu pilih kategori untuk melihat menu.',
            'facilities_subtitle'    => 'Kenapa Warkop Pos?',
            'facilities_title'       => 'Fasilitas Kami',
            'facility_1_title'       => 'WiFi Gratis',
            'facility_1_description' => 'Koneksi cepat buat tugas dan nugas',
            'facility_2_title'       => 'Tempat Nyaman',
            'facility_2_description' => 'Kursi nyaman buat nongkrong',
            'facility_3_title'       => 'Colokan Banyak',
            'facility_3_description' => 'Bisa charge laptop dan HP',
            'facility_4_title'       => 'Harga Mahasiswa',
            'facility_4_description' => 'Terjangkau di kantong mahasiswa',
            'location_subtitle'      => 'Temukan Kami',
            'location_title'         => 'Lokasi Warkop Pos',
            'location_description'   => 'Parkiran luas, dan mudah dijangkau',
            'maps_url'               => '',
            'maps_embed_url'         => '',
            'instagram'              => '',
            'footer_description'     => 'Tempat nongkrong nyaman dengan kopi enak.',
            'tax_percent'            => $settings['tax_percent'] ?? '10',
            'currency'               => $settings['currency']    ?? 'IDR',
            'weekday_open'           => '08:00',
            'weekday_close'          => '23:00',
            'weekend_open'           => '09:00',
            'weekend_close'          => '00:00',
            'paper_size'             => '58mm',
            'receipt_copies'         => '1',
        ], $settings);
    }

    private function transactionDateColumn(): string
    {
        return $this->columnExists('transactions', 'paid_at') ? 'paid_at' : 'created_at';
    }

    private function successfulTransactionsQuery()
    {
        $db    = \Config\Database::connect();
        $query = $db->table('transactions');
        if ($this->columnExists('transactions', 'status')) {
            $query->where('status', 'success');
        }
        return $query;
    }

    private function transactionSummary(string $start, string $end): array
    {
        if (!$this->tableExists('transactions')) {
            return ['revenue' => 0, 'transactions' => 0, 'items' => 0, 'average_order' => 0];
        }

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();

        $revenue = (float) ($this->successfulTransactionsQuery()
            ->selectSum('total')
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->get()->getRowArray()['total'] ?? 0);

        $transactions = (int) $this->successfulTransactionsQuery()
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->countAllResults();

        $items = 0;
        if ($this->tableExists('transaction_details')) {
            $row = $db->table('transaction_details')
                ->selectSum('transaction_details.qty', 'total_qty')
                ->join('transactions', 'transactions.id = transaction_details.transaction_id')
                ->where("transactions.$dateColumn >=", $start)
                ->where("transactions.$dateColumn <=", $end);
            if ($this->columnExists('transactions', 'status')) {
                $row->where('transactions.status', 'success');
            }
            $items = (int) ($row->get()->getRowArray()['total_qty'] ?? 0);
        }

        return [
            'revenue'       => $revenue,
            'transactions'  => $transactions,
            'items'         => $items,
            'average_order' => $transactions > 0 ? $revenue / $transactions : 0,
        ];
    }

    private function salesChart(string $start, string $end, string $period): array
    {
        if (!$this->tableExists('transactions')) return [];

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();

        if ($period === 'today') {
            $rows = $this->successfulTransactionsQuery()
                ->select("HOUR($dateColumn) as label_key, COALESCE(SUM(total), 0) as total")
                ->where("$dateColumn >=", $start)
                ->where("$dateColumn <=", $end)
                ->groupBy("HOUR($dateColumn)")
                ->get()->getResultArray();

            $keyedRows = array_column($rows, 'total', 'label_key');
            return array_map(fn($h) => [
                'label' => str_pad((string) $h, 2, '0', STR_PAD_LEFT) . ':00',
                'total' => (float) ($keyedRows[$h] ?? 0),
            ], range(8, 22, 2));
        }

        $rows = $this->successfulTransactionsQuery()
            ->select("DATE($dateColumn) as label_key, COALESCE(SUM(total), 0) as total")
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy("DATE($dateColumn)")
            ->get()->getResultArray();

        $keyedRows = array_column($rows, 'total', 'label_key');
        $startDt   = new \DateTime($start);
        $endDt     = new \DateTime($end);
        $days      = $period === 'week' ? 7 : min(31, (int) $startDt->diff($endDt)->days + 1);

        return array_map(function ($offset) use ($startDt, $keyedRows) {
            $date = (clone $startDt)->modify("+$offset days");
            return [
                'label' => $date->format('d M'),
                'total' => (float) ($keyedRows[$date->format('Y-m-d')] ?? 0),
            ];
        }, range(0, $days - 1));
    }

    private function reportTopProducts(string $start, string $end): array
    {
        if (!$this->tableExists('transaction_details') || !$this->tableExists('products')) return [];

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();

        $query = $db->table('transaction_details')
            ->select('products.name, categories.name as category_name, SUM(transaction_details.qty) as quantity, SUM(transaction_details.subtotal) as total')
            ->join('products', 'products.id = transaction_details.product_id')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('transactions', 'transactions.id = transaction_details.transaction_id')
            ->where("transactions.$dateColumn >=", $start)
            ->where("transactions.$dateColumn <=", $end)
            ->groupBy('products.id, products.name, categories.name')
            ->orderBy('quantity', 'DESC')
            ->limit(5);

        if ($this->columnExists('transactions', 'status')) {
            $query->where('transactions.status', 'success');
        }

        return $query->get()->getResultArray();
    }

    private function reportCategories(string $start, string $end): array
    {
        if (!$this->tableExists('transaction_details') || !$this->tableExists('categories')) return [];

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();

        $query = $db->table('transaction_details')
            ->select('categories.name, SUM(transaction_details.subtotal) as total')
            ->join('products', 'products.id = transaction_details.product_id')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('transactions', 'transactions.id = transaction_details.transaction_id')
            ->where("transactions.$dateColumn >=", $start)
            ->where("transactions.$dateColumn <=", $end)
            ->groupBy('categories.id, categories.name')
            ->orderBy('total', 'DESC');

        if ($this->columnExists('transactions', 'status')) {
            $query->where('transactions.status', 'success');
        }

        $rows  = $query->get()->getResultArray();
        $total = max((float) array_sum(array_column($rows, 'total')), 1);

        return array_map(fn($row) => [
            'name'    => $row['name'] ?: 'Tanpa Kategori',
            'total'   => (float) $row['total'],
            'percent' => round(((float) $row['total'] / $total) * 100, 1),
        ], $rows);
    }

    private function percentageChange(float $current, float $previous): float
    {
        if ($previous == 0) return $current > 0 ? 100.0 : 0.0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function getTransactions(): array
    {
        $db = \Config\Database::connect();

        $hasOrderId       = $this->columnExists('transactions', 'order_id');
        $hasCode          = $this->columnExists('transactions', 'code');
        $hasInvoiceCode   = $this->columnExists('transactions', 'invoice_code');
        $hasPaymentMethod = $this->columnExists('transactions', 'payment_method');
        $hasPayMethodId   = $this->columnExists('transactions', 'payment_method_id');
        $hasPayment       = $this->columnExists('transactions', 'payment');
        $hasChangeAmount  = $this->columnExists('transactions', 'change_amount');
        $hasStatus        = $this->columnExists('transactions', 'status');
        $hasPaidAt        = $this->columnExists('transactions', 'paid_at');
        $hasCreatedAt     = $this->columnExists('transactions', 'created_at');

        $selects = ['transactions.id', 'transactions.total'];
        if ($hasCode)          $selects[] = 'transactions.code';
        if ($hasInvoiceCode)   $selects[] = 'transactions.invoice_code';
        if ($hasPaymentMethod) $selects[] = 'transactions.payment_method';
        if ($hasPayMethodId)   $selects[] = 'transactions.payment_method_id';
        if ($hasPayment)       $selects[] = 'transactions.payment';
        if ($hasChangeAmount)  $selects[] = 'transactions.change_amount';
        if ($hasStatus)        $selects[] = 'transactions.status';
        if ($hasPaidAt)        $selects[] = 'transactions.paid_at';
        if ($hasCreatedAt)     $selects[] = 'transactions.created_at';

        $query = $db->table('transactions')->select(implode(', ', $selects));

        if ($hasOrderId) {
            $query->select('transactions.order_id, orders.code as order_code, orders.item_count as order_item_count')
                ->join('orders', 'orders.id = transactions.order_id', 'left');
        }

        if ($hasPayMethodId) {
            $query->select('payments.name as payment_method_name')
                ->join('payments', 'payments.id = transactions.payment_method_id', 'left');
        }

        if ($this->tableExists('transaction_details')) {
            $groupCols = array_merge($selects, $hasOrderId ? ['transactions.order_id', 'orders.code', 'orders.item_count'] : []);
            if ($hasPayMethodId) $groupCols[] = 'payments.name';
            $query->select('COALESCE(SUM(transaction_details.qty), 0) as detail_item_count')
                ->join('transaction_details', 'transaction_details.transaction_id = transactions.id', 'left')
                ->groupBy(implode(', ', $groupCols));
        }

        $perPage     = $this->perPage();
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $offset      = ($currentPage - 1) * $perPage;
        $total       = $query->countAllResults(false);

        $items = $query->orderBy('transactions.id', 'DESC')
            ->limit($perPage, $offset)
            ->get()->getResultArray();

        $data = array_map(function ($t) use ($hasPayMethodId, $hasPaymentMethod, $hasStatus, $hasPayment, $hasChangeAmount, $hasPaidAt, $hasCreatedAt, $hasOrderId) {
            $pmName = $hasPayMethodId ? ($t['payment_method_name'] ?? null) : null;
            if (!$pmName && $hasPaymentMethod) {
                $pmName = $this->paymentMethodLabel($t['payment_method'] ?? null);
            }
            return [
                'id'                  => $t['id'],
                'code'                => $t['code'] ?? $t['invoice_code'] ?? ('TRX-' . str_pad((string) $t['id'], 4, '0', STR_PAD_LEFT)),
                'order_code'          => $hasOrderId ? ($t['order_code'] ?? null) : null,
                'item_count'          => (int) ($t['order_item_count'] ?? $t['detail_item_count'] ?? 0),
                'payment_method'      => $t['payment_method'] ?? null,
                'payment_method_id'   => $t['payment_method_id'] ?? null,
                'payment_method_name' => $pmName ?? '-',
                'total'               => (float) $t['total'],
                'payment'             => $hasPayment ? (float) ($t['payment'] ?? 0) : (float) $t['total'],
                'change_amount'       => $hasChangeAmount ? (float) ($t['change_amount'] ?? 0) : 0,
                'status'              => $hasStatus ? ($t['status'] ?? 'success') : 'success',
                'paid_at'             => $hasPaidAt ? ($t['paid_at'] ?? null) : null,
                'created_at'          => $hasCreatedAt ? ($t['created_at'] ?? null) : null,
            ];
        }, $items);

        $lastPage = (int) ceil($total / $perPage);

        return [
            'data'       => $data,
            'pagination' => [
                'current_page' => $currentPage,
                'last_page'    => $lastPage,
                'per_page'     => $perPage,
                'total'        => $total,
                'from'         => $total > 0 ? $offset + 1 : null,
                'to'           => $total > 0 ? min($offset + $perPage, $total) : null,
            ],
        ];
    }

    private function paymentMethodLabel(?string $method): string
    {
        return match ($method) {
            'cash'     => 'Tunai',
            'qris'     => 'QRIS',
            'transfer' => 'Transfer',
            default    => $method ?: '-',
        };
    }

    private function perPage(): int
    {
        return max(1, min((int) ($this->request->getGet('per_page') ?? 10), 100));
    }

    private function tableExists(string $table): bool
    {
        return \Config\Database::connect()->tableExists($table);
    }

    private function columnExists(string $table, string $column): bool
    {
        return \Config\Database::connect()->fieldExists($column, $table);
    }

    private function getTableColumns(string $table): array
    {
        return \Config\Database::connect()->getFieldNames($table) ?: [];
    }
}