<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class OwnerController extends BaseController
{
    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    public function index()
    {
        return view('dashboard/owner/index');
    }

    // -------------------------------------------------------------------------
    // API – Dashboard
    // -------------------------------------------------------------------------

    public function dashboard_data()
    {
        $period = $this->request->getGet('period') ?? 'month';
        $trend  = $this->request->getGet('trend')  ?? '7d';

        [$currentStart, $currentEnd, $previousStart, $previousEnd] = $this->periodRanges($period);

        $current  = $this->transactionSummary($currentStart, $currentEnd);
        $previous = $this->transactionSummary($previousStart, $previousEnd);

        $db             = \Config\Database::connect();
        $totalProducts  = $this->tableExists('products') ? $db->table('products')->countAll() : 0;
        $activeProducts = $this->tableExists('products') && $this->columnExists('products', 'status')
            ? $db->table('products')->where('status', 1)->countAllResults()
            : $totalProducts;
        $activeProductPercent = $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100, 1) : 0;

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'period'                  => $period,
                    'revenue'                 => $current['revenue'],
                    'previous_revenue'        => $previous['revenue'],
                    'revenue_change'          => $this->percentageChange($current['revenue'], $previous['revenue']),
                    'orders'                  => $current['orders'],
                    'previous_orders'         => $previous['orders'],
                    'order_change'            => $this->percentageChange($current['orders'], $previous['orders']),
                    'average_order'           => $current['average_order'],
                    'previous_average_order'  => $previous['average_order'],
                    'average_order_change'    => $this->percentageChange($current['average_order'], $previous['average_order']),
                    'active_products'         => $activeProducts,
                    'total_products'          => $totalProducts,
                    'active_product_percent'  => $activeProductPercent,
                ],
                'revenue_trend'      => $this->revenueTrend($trend),
                'category_revenue'   => $this->categoryRevenue($currentStart, $currentEnd),
                'top_products'       => $this->topProducts($currentStart, $currentEnd),
                'recent_activities'  => $this->recentActivities(),
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Revenue
    // -------------------------------------------------------------------------

    public function revenue_data()
    {
        $now = new \DateTime();

        $todayStart     = $this->fmt((clone $now)->setTime(0, 0, 0));
        $todayEnd       = $this->fmt((clone $now)->setTime(23, 59, 59));
        $yesterdayStart = $this->fmt((clone $now)->modify('-1 day')->setTime(0, 0, 0));
        $yesterdayEnd   = $this->fmt((clone $now)->modify('-1 day')->setTime(23, 59, 59));
        $twoDaysStart   = $this->fmt((clone $now)->modify('-2 days')->setTime(0, 0, 0));
        $twoDaysEnd     = $this->fmt((clone $now)->modify('-2 days')->setTime(23, 59, 59));

        [$weekStart, $weekEnd]         = $this->currentWeekRange();
        [$lastWeekStart, $lastWeekEnd] = $this->lastWeekRange();
        [$twoWeekStart, $twoWeekEnd]   = $this->twoWeeksAgoRange();
        [$monthStart, $monthEnd]       = $this->currentMonthRange();
        [$lastMonthStart, $lastMonthEnd] = $this->lastMonthRange();
        [$yearStart, $yearEnd]         = $this->currentYearRange();
        [$lastYearStart, $lastYearEnd] = $this->lastYearRange();

        $today       = $this->transactionSummary($todayStart, $todayEnd);
        $yesterday   = $this->transactionSummary($yesterdayStart, $yesterdayEnd);
        $twoDaysAgo  = $this->transactionSummary($twoDaysStart, $twoDaysEnd);
        $thisWeek    = $this->transactionSummary($weekStart, $weekEnd);
        $lastWeek    = $this->transactionSummary($lastWeekStart, $lastWeekEnd);
        $twoWeeksAgo = $this->transactionSummary($twoWeekStart, $twoWeekEnd);
        $thisMonth   = $this->transactionSummary($monthStart, $monthEnd);
        $lastMonth   = $this->transactionSummary($lastMonthStart, $lastMonthEnd);
        $thisYear    = $this->transactionSummary($yearStart, $yearEnd);
        $lastYear    = $this->transactionSummary($lastYearStart, $lastYearEnd);

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'today' => $this->revenueSummaryCard($today,    $yesterday, 'dari kemarin'),
                    'week'  => $this->revenueSummaryCard($thisWeek, $lastWeek,  'dari minggu lalu'),
                    'month' => $this->revenueSummaryCard($thisMonth,$lastMonth, 'dari bulan lalu'),
                    'year'  => $this->revenueSummaryCard($thisYear, $lastYear,  'dari tahun lalu'),
                ],
                'daily_revenue'  => $this->revenueTrend('7d'),
                'hourly_revenue' => $this->hourlyRevenue(),
                'details' => [
                    $this->revenueDetailRow('Hari Ini',      $today,     $yesterday),
                    $this->revenueDetailRow('Kemarin',       $yesterday, $twoDaysAgo),
                    $this->revenueDetailRow('Minggu Ini',    $thisWeek,  $lastWeek),
                    $this->revenueDetailRow('Minggu Lalu',   $lastWeek,  $twoWeeksAgo),
                    $this->revenueDetailRow('Bulan Ini',     $thisMonth, $lastMonth),
                ],
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Products
    // -------------------------------------------------------------------------

    public function products_data()
    {
        [$currentStart, $currentEnd]   = $this->currentMonthRange();
        [$previousStart, $previousEnd] = $this->lastMonthRange();

        $products      = $this->productList();
        $currentSales  = array_column($this->productSales($currentStart, $currentEnd),  null, 'id');
        $previousSales = array_column($this->productSales($previousStart, $previousEnd), null, 'id');

        $productRows = array_map(function ($product) use ($currentSales, $previousSales) {
            $current          = $currentSales[$product['id']]  ?? null;
            $previous         = $previousSales[$product['id']] ?? null;
            $quantity         = (int)   ($current['quantity']  ?? 0);
            $revenue          = (float) ($current['revenue']   ?? 0);
            $previousQuantity = (int)   ($previous['quantity'] ?? 0);

            return [
                'id'            => $product['id'],
                'name'          => $product['name'],
                'category_name' => $product['category_name'] ?? 'Tanpa Kategori',
                'price'         => (float) ($product['price'] ?? 0),
                'stock'         => (int)   ($product['stock'] ?? 0),
                'quantity'      => $quantity,
                'revenue'       => $revenue,
                'change'        => $this->percentageChange($quantity, $previousQuantity),
                'created_at'    => $product['created_at'],
            ];
        }, $products);

        // Sort helpers
        usort($productRows, fn($a, $b) => $b['quantity'] <=> $a['quantity']);
        $bestSeller = $productRows[0] ?? null;

        $hasPrevious = array_filter($productRows, fn($p) => isset($previousSales[$p['id']]) || $p['quantity'] > 0);
        usort($hasPrevious, fn($a, $b) => $a['change'] <=> $b['change']);
        $biggestDrop = $hasPrevious[0] ?? null;

        usort($products, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
        $newestProduct = $products[0] ?? null;

        usort($productRows, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
        $details = $this->paginateArray(array_values($productRows), 'details_page');

        usort($productRows, fn($a, $b) => $b['quantity'] <=> $a['quantity']);
        $performance = array_slice($productRows, 0, 5);

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'total_products'  => count($products),
                    'best_seller'     => ($bestSeller && $bestSeller['quantity'] > 0) ? $bestSeller['name'] : '-',
                    'biggest_drop'    => $biggestDrop ? $biggestDrop['name'] : '-',
                    'newest_product'  => $newestProduct['name'] ?? '-',
                ],
                'performance'        => $performance,
                'categories'         => $this->productCategoryAnalysis($currentStart, $currentEnd, $previousStart, $previousEnd),
                'details'            => $details['data'],
                'details_pagination' => $details['pagination'],
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Customers
    // -------------------------------------------------------------------------

    public function customers_data()
    {
        $now = new \DateTime();

        $todayStart   = $this->fmt((clone $now)->setTime(0, 0, 0));
        $todayEnd     = $this->fmt((clone $now)->setTime(23, 59, 59));
        $yesterdayStart = $this->fmt((clone $now)->modify('-1 day')->setTime(0, 0, 0));
        $yesterdayEnd   = $this->fmt((clone $now)->modify('-1 day')->setTime(23, 59, 59));

        [$monthStart, $monthEnd]           = $this->currentMonthRange();
        [$prevMonthStart, $prevMonthEnd]   = $this->lastMonthRange();

        $todayBuyers     = $this->buyerCount($todayStart,    $todayEnd);
        $yesterdayBuyers = $this->buyerCount($yesterdayStart, $yesterdayEnd);
        $monthBuyers     = $this->buyerCount($monthStart,    $monthEnd);
        $prevMonthBuyers = $this->buyerCount($prevMonthStart, $prevMonthEnd);

        $activeDays = max(1, (int) (new \DateTime())->format('j'));
        $target     = (int) ($this->ownerSettings()['new_customer_target'] ?? 80);

        $dailyRows = $this->dailyBuyerRows(30);
        $pagedRows = $this->paginateArray($dailyRows, 'daily_page');

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'today_buyers'         => $todayBuyers,
                    'today_change'         => $this->percentageChange($todayBuyers, $yesterdayBuyers),
                    'month_buyers'         => $monthBuyers,
                    'month_change'         => $this->percentageChange($monthBuyers, $prevMonthBuyers),
                    'average_daily_buyers' => round($monthBuyers / $activeDays, 1),
                    'target_progress'      => $target > 0 ? round(min(100, ($monthBuyers / $target) * 100), 1) : 0,
                    'target'               => $target,
                ],
                'trend'            => array_slice($this->dailyBuyerRows(7), 0, 7),
                'daily'            => $pagedRows['data'],
                'daily_pagination' => $pagedRows['pagination'],
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Staff
    // -------------------------------------------------------------------------

    public function staff_data()
    {
        $staff = $this->staffUsers();

        [$currentStart, $currentEnd] = $this->currentMonthRange();
        [$todayStart, $todayEnd]     = $this->todayRange();

        $cashierIds = array_values(array_map(
            fn($u) => $u['id'],
            array_filter($staff, fn($u) => strtolower($u['role_name'] ?? '') === 'kasir')
        ));

        $monthlyStats = empty($cashierIds) ? [] : array_column(
            $this->staffTransactionStats($currentStart, $currentEnd, $cashierIds), null, 'user_id'
        );
        $todayStats = empty($cashierIds) ? [] : $this->staffTransactionStats($todayStart, $todayEnd, $cashierIds);

        $staffIds      = array_column($staff, 'id');
        $onlineUserIds = $this->columnExists('users', 'status') ? [] : $this->onlineStaffUserIds($staffIds);

        $rows = array_map(function ($user, $index) use ($monthlyStats, $onlineUserIds) {
            $isCashier    = strtolower($user['role_name'] ?? '') === 'kasir';
            $stats        = $monthlyStats[$user['id']] ?? null;
            $transactions = $isCashier ? (int)   ($stats['transactions'] ?? 0) : 0;
            $revenue      = $isCashier ? (float) ($stats['revenue']      ?? 0) : 0;
            $avgOrder     = $transactions > 0 ? $revenue / $transactions : 0;
            $workHours    = isset($user['work_hours'])
                ? (int) $user['work_hours']
                : $this->estimatedWorkHours($transactions, $user['created_at']);
            $score  = $this->staffPerformanceScore($transactions, $revenue);
            $status = isset($user['status'])
                ? ucfirst($user['status'])
                : (in_array((int) $user['id'], $onlineUserIds) ? 'Online' : 'Offline');

            return [
                'id'            => $user['id'],
                'name'          => $user['name'],
                'username'      => $user['username'] ?? null,
                'email'         => $user['email'],
                'role_name'     => $user['role_name'] ?: 'Karyawan',
                'is_cashier'    => $isCashier,
                'initials'      => $this->initials($user['name']),
                'work_hours'    => $workHours,
                'transactions'  => $transactions,
                'revenue'       => $revenue,
                'average_order' => $avgOrder,
                'score'         => $score,
                'status'        => $status,
                'schedule'      => $this->staffSchedule($index),
            ];
        }, $staff, array_keys($staff));

        // Group by role
        $groups = [];
        foreach ($rows as $row) {
            $groups[$row['role_name']][] = $row;
        }

        $roleGroups = [];
        foreach ($groups as $role => $members) {
            usort($members, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
            $pageName = 'role_' . strtolower(preg_replace('/[^a-z0-9]+/i', '_', $role)) . '_page';
            $paged = $this->paginateArray(array_values($members), $pageName);
            $roleGroups[] = [
                'role'       => $role,
                'is_cashier' => strtolower($role) === 'kasir',
                'staff'      => $paged['data'],
                'pagination' => $paged['pagination'],
                'page_param' => $pageName,
            ];
        }
        usort($roleGroups, fn($a, $b) => $a['is_cashier'] <=> $b['is_cashier']);
        $roleGroups = array_reverse($roleGroups); // kasir first

        usort($rows, fn($a, $b) => sprintf(
            '%d-%012d-%s',
            strtolower($a['role_name']) === 'kasir' ? 0 : 1,
            999999999999 - (int) $a['revenue'],
            $a['name']
        ) <=> sprintf(
            '%d-%012d-%s',
            strtolower($b['role_name']) === 'kasir' ? 0 : 1,
            999999999999 - (int) $b['revenue'],
            $b['name']
        ));

        $todayTransactions = array_sum(array_column($todayStats, 'transactions'));

        $scheduleRows = array_map(fn($s) => [
            'name'     => explode(' ', trim($s['name']))[0] ?: $s['name'],
            'schedule' => $s['schedule'],
        ], array_slice($rows, 0, 6));

        $onlineCount = count(array_filter($rows, fn($r) => $r['status'] === 'Online'));
        $avgScore    = count($rows) ? round(array_sum(array_column($rows, 'score')) / count($rows), 1) : 0;

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'total_staff'        => count($staff),
                    'online_today'       => $onlineCount,
                    'average_score'      => $avgScore,
                    'today_transactions' => (int) $todayTransactions,
                ],
                'staff'       => array_values($rows),
                'role_groups' => $roleGroups,
                'schedule'    => array_values($scheduleRows),
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Financial
    // -------------------------------------------------------------------------

    public function financial_data()
    {
        [$monthStart, $monthEnd] = $this->currentMonthRange();
        [$yearStart, $yearEnd]   = $this->currentYearRange();

        $month            = $this->transactionSummary($monthStart, $monthEnd);
        $year             = $this->transactionSummary($yearStart, $yearEnd);
        $expenseBreakdown = $this->estimatedExpenseBreakdown($month['revenue']);
        $totalExpense     = array_sum(array_column($expenseBreakdown, 'amount'));
        $netProfit        = $month['revenue'] - $totalExpense;
        $margin           = $month['revenue'] > 0 ? round(($netProfit / $month['revenue']) * 100, 1) : 0;

        $records = $this->paginateArray(
            array_values($this->financialRecords($expenseBreakdown)),
            'records_page'
        );

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'revenue'    => $month['revenue'],
                    'expense'    => $totalExpense,
                    'net_profit' => $netProfit,
                    'margin'     => $margin,
                    'year_profit'=> $year['revenue'] - ($year['revenue'] * 0.58),
                ],
                'expenses'           => $expenseBreakdown,
                'monthly_profit'     => $this->monthlyProfitTrend(),
                'records'            => $records['data'],
                'records_pagination' => $records['pagination'],
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Inventory
    // -------------------------------------------------------------------------

    public function inventory_data()
    {
        $products = $this->productList();

        $items = array_map(function ($product) {
            $stock = (int) ($product['stock'] ?? 0);
            return [
                'id'            => $product['id'],
                'name'          => $product['name'],
                'category_name' => $product['category_name'] ?? 'Tanpa Kategori',
                'stock'         => $stock,
                'min_stock'     => 10,
                'price'         => (float) ($product['price'] ?? 0),
                'value'         => $stock * (float) ($product['price'] ?? 0),
                'status'        => $stock <= 0 ? 'Habis' : ($stock <= 10 ? 'Rendah' : 'Aman'),
                'updated_at'    => $product['updated_at'] ?? $product['created_at'],
            ];
        }, $products);

        usort($items, fn($a, $b) => $a['stock'] <=> $b['stock']);
        $pagedItems = $this->paginateArray(array_values($items), 'items_page');
        $stockLogs  = $this->stockLogs();

        $lowStockItems = array_filter($items, fn($i) => $i['stock'] <= 10);
        usort($lowStockItems, fn($a, $b) => $a['stock'] <=> $b['stock']);

        $outOfStock   = count(array_filter($items, fn($i) => $i['stock'] <= 0));
        $lowStock     = count(array_filter($items, fn($i) => $i['stock'] > 0 && $i['stock'] <= 10));
        $totalValue   = array_sum(array_column($items, 'value'));

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'total_items'     => count($items),
                    'low_stock'       => $lowStock,
                    'out_of_stock'    => $outOfStock,
                    'inventory_value' => $totalValue,
                ],
                'low_stock_items'  => array_values($lowStockItems),
                'items'            => $pagedItems['data'],
                'items_pagination' => $pagedItems['pagination'],
                'logs'             => $stockLogs['data'],
                'logs_pagination'  => $stockLogs['pagination'],
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Performance
    // -------------------------------------------------------------------------

    public function performance_data()
    {
        [$monthStart, $monthEnd]   = $this->currentMonthRange();
        [$prevStart, $prevEnd]     = $this->lastMonthRange();

        $month    = $this->transactionSummary($monthStart, $monthEnd);
        $previous = $this->transactionSummary($prevStart, $prevEnd);

        $settings      = $this->ownerSettings();
        $targetRevenue = (float) ($settings['monthly_revenue_target'] ?? 48000000);
        $expense       = array_sum(array_column($this->estimatedExpenseBreakdown($month['revenue']), 'amount'));
        $margin        = $month['revenue'] > 0 ? round((($month['revenue'] - $expense) / $month['revenue']) * 100, 1) : 0;

        $successful = $this->transactionCountByStatus('success');
        $failed     = $this->transactionCountByStatus('failed');
        $paySuccess = ($successful + $failed) > 0 ? round(($successful / ($successful + $failed)) * 100, 1) : 100;

        $serviceSpeed = $month['orders'] > 0 ? max(3.2, round(7 - min($month['orders'], 120) / 40, 1)) : 0;
        $rating       = $margin > 0 ? min(5, round(4 + ($margin / 100), 1)) : 0;
        $repeatRate   = min(100, round($month['orders'] > 0 ? 35 + min($month['orders'], 200) / 4 : 0, 1));

        return $this->respond([
            'status' => true,
            'data'   => [
                'summary' => [
                    'service_speed' => $serviceSpeed,
                    'rating'        => $rating,
                    'repeat_rate'   => $repeatRate,
                    'growth_rate'   => $this->percentageChange($month['revenue'], $previous['revenue']),
                ],
                'operational' => [
                    ['label' => 'Rata-rata Waktu Pesanan', 'description' => 'Estimasi dari volume transaksi',       'value' => $serviceSpeed . ' min'],
                    ['label' => 'Peak Hour Traffic',       'description' => 'Jam transaksi tertinggi hari ini',     'value' => $this->peakHour()],
                    ['label' => 'Rata-rata Order',         'description' => 'Nilai transaksi rata-rata bulan ini',  'value' => $this->formatRupiah($month['average_order'])],
                    ['label' => 'Payment Success Rate',    'description' => 'Rasio transaksi berhasil',             'value' => $paySuccess . '%'],
                ],
                'kpis' => [
                    ['label' => 'Target Pendapatan Bulanan', 'percent' => $targetRevenue > 0 ? round(($month['revenue'] / $targetRevenue) * 100, 1) : 0, 'detail' => $this->formatRupiah($month['revenue']) . ' / ' . $this->formatRupiah($targetRevenue)],
                    ['label' => 'Target Transaksi Bulanan',  'percent' => round(min(100, ($month['orders'] / 160) * 100), 1), 'detail' => $month['orders'] . ' / 160 transaksi'],
                    ['label' => 'Target Margin Operasional', 'percent' => round(min(100, ($margin / 40) * 100), 1), 'detail' => $margin . '% / 40% margin'],
                    ['label' => 'Produk Aktif',              'percent' => $this->activeProductPercent(), 'detail' => 'Produk siap jual'],
                ],
                'insights' => $this->performanceInsights($month, $margin),
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // API – Settings
    // -------------------------------------------------------------------------

    public function settings_data()
    {
        return $this->respond([
            'status' => true,
            'data'   => $this->ownerSettings(),
        ]);
    }

    public function settings_update()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'business_name'          => 'required|max_length[255]',
            'business_address'       => 'permit_empty|max_length[1000]',
            'business_phone'         => 'permit_empty|max_length[50]',
            'business_email'         => 'permit_empty|valid_email|max_length[255]',
            'tax_percent'            => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
            'currency'               => 'permit_empty|max_length[10]',
            'monthly_revenue_target' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'new_customer_target'    => 'permit_empty|integer|greater_than_equal_to[0]',
            'current_password'       => 'permit_empty',
            'new_password'           => 'permit_empty|min_length[6]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->respond([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ], ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $db = \Config\Database::connect();

        if (! empty($data['new_password'])) {
            $userId = session()->get('user_id');
            $user   = $db->table('users')->where('id', $userId)->get()->getRowArray();

            if (! $user || ! password_verify($data['current_password'] ?? '', $user['password'])) {
                return $this->respond(['status' => false, 'message' => 'Password saat ini salah'], 422);
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

        return $this->respond(['status' => true, 'message' => 'Pengaturan berhasil disimpan']);
    }

    // =========================================================================
    // Private Helpers – Date Ranges
    // =========================================================================

    /** Format DateTime ke string MySQL */
    private function fmt(\DateTime $d): string
    {
        return $d->format('Y-m-d H:i:s');
    }

    private function todayRange(): array
    {
        $now = new \DateTime();
        return [
            $this->fmt((clone $now)->setTime(0, 0, 0)),
            $this->fmt((clone $now)->setTime(23, 59, 59)),
        ];
    }

    private function currentWeekRange(): array
    {
        $now = new \DateTime();
        return [
            $this->fmt((clone $now)->modify('monday this week')->setTime(0, 0, 0)),
            $this->fmt((clone $now)->modify('sunday this week')->setTime(23, 59, 59)),
        ];
    }

    private function lastWeekRange(): array
    {
        $now = new \DateTime();
        return [
            $this->fmt((clone $now)->modify('monday last week')->setTime(0, 0, 0)),
            $this->fmt((clone $now)->modify('sunday last week')->setTime(23, 59, 59)),
        ];
    }

    private function twoWeeksAgoRange(): array
    {
        $now = new \DateTime();
        return [
            $this->fmt((clone $now)->modify('monday this week')->modify('-14 days')->setTime(0, 0, 0)),
            $this->fmt((clone $now)->modify('sunday this week')->modify('-14 days')->setTime(23, 59, 59)),
        ];
    }

    private function currentMonthRange(): array
    {
        $now = new \DateTime();
        return [
            $this->fmt((clone $now)->modify('first day of this month')->setTime(0, 0, 0)),
            $this->fmt((clone $now)->modify('last day of this month')->setTime(23, 59, 59)),
        ];
    }

    private function lastMonthRange(): array
    {
        $now = new \DateTime();
        return [
            $this->fmt((clone $now)->modify('first day of last month')->setTime(0, 0, 0)),
            $this->fmt((clone $now)->modify('last day of last month')->setTime(23, 59, 59)),
        ];
    }

    private function currentYearRange(): array
    {
        $year = (int) (new \DateTime())->format('Y');
        return ["$year-01-01 00:00:00", "$year-12-31 23:59:59"];
    }

    private function lastYearRange(): array
    {
        $year = (int) (new \DateTime())->format('Y') - 1;
        return ["$year-01-01 00:00:00", "$year-12-31 23:59:59"];
    }

    private function periodRanges(string $period): array
    {
        return match ($period) {
            'today' => [...$this->todayRange(),   ...$this->todayYesterdayRange()],
            'week'  => [...$this->currentWeekRange(), ...$this->lastWeekRange()],
            'year'  => [...$this->currentYearRange(), ...$this->lastYearRange()],
            default => [...$this->currentMonthRange(), ...$this->lastMonthRange()],
        };
    }

    private function todayYesterdayRange(): array
    {
        $now = new \DateTime();
        return [
            $this->fmt((clone $now)->modify('-1 day')->setTime(0, 0, 0)),
            $this->fmt((clone $now)->modify('-1 day')->setTime(23, 59, 59)),
        ];
    }

    // =========================================================================
    // Private Helpers – Transactions
    // =========================================================================

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
        if (! $this->tableExists('transactions')) {
            return ['revenue' => 0, 'orders' => 0, 'average_order' => 0];
        }

        $dateColumn = $this->transactionDateColumn();

        $revenueRow = $this->successfulTransactionsQuery()
            ->selectSum('total')
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->get()->getRowArray();

        $revenue = (float) ($revenueRow['total'] ?? 0);

        $orders = (int) $this->successfulTransactionsQuery()
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->countAllResults();

        return [
            'revenue'       => $revenue,
            'orders'        => $orders,
            'average_order' => $orders > 0 ? $revenue / $orders : 0,
        ];
    }

    private function buyerCount(string $start, string $end): int
    {
        if (! $this->tableExists('transactions')) return 0;

        $dateColumn = $this->transactionDateColumn();
        return (int) $this->successfulTransactionsQuery()
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->countAllResults();
    }

    private function transactionCountByStatus(string $status): int
    {
        if (! $this->tableExists('transactions')) return 0;

        $db    = \Config\Database::connect();
        $query = $db->table('transactions');
        if ($this->columnExists('transactions', 'status')) {
            $query->where('status', $status);
        }
        return (int) $query->countAllResults();
    }

    private function dailyBuyerRows(int $days): array
    {
        if (! $this->tableExists('transactions')) {
            return array_map(function ($offset) {
                $date = (new \DateTime())->modify("-$offset days");
                return [
                    'date'          => $date->format('Y-m-d'),
                    'label'         => $date->format('d M'),
                    'buyers'        => 0,
                    'revenue'       => 0,
                    'average_order' => 0,
                ];
            }, range($days - 1, 0));
        }

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();
        $now        = new \DateTime();
        $start      = $this->fmt((clone $now)->modify("-" . ($days - 1) . " days")->setTime(0, 0, 0));
        $end        = $this->fmt((clone $now)->setTime(23, 59, 59));

        $rows = $this->successfulTransactionsQuery()
            ->select("DATE($dateColumn) as date, COUNT(*) as buyers, COALESCE(SUM(total), 0) as revenue")
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy("DATE($dateColumn)")
            ->get()->getResultArray();

        $keyed = array_column($rows, null, 'date');

        return array_map(function ($offset) use ($keyed) {
            $date = (new \DateTime())->modify("-$offset days");
            $key  = $date->format('Y-m-d');
            $row  = $keyed[$key] ?? null;
            $buyers  = (int)   ($row['buyers']  ?? 0);
            $revenue = (float) ($row['revenue'] ?? 0);
            return [
                'date'          => $key,
                'label'         => $date->format('d M'),
                'buyers'        => $buyers,
                'revenue'       => $revenue,
                'average_order' => $buyers > 0 ? $revenue / $buyers : 0,
            ];
        }, range($days - 1, 0));
    }

    // =========================================================================
    // Private Helpers – Revenue
    // =========================================================================

    private function revenueSummaryCard(array $current, array $previous, string $comparison): array
    {
        return [
            'revenue'       => $current['revenue'],
            'orders'        => $current['orders'],
            'average_order' => $current['average_order'],
            'change'        => $this->percentageChange($current['revenue'], $previous['revenue']),
            'comparison'    => $comparison,
        ];
    }

    private function revenueDetailRow(string $label, array $current, array $previous): array
    {
        return [
            'label'         => $label,
            'orders'        => $current['orders'],
            'revenue'       => $current['revenue'],
            'average_order' => $current['average_order'],
            'change'        => $this->percentageChange($current['revenue'], $previous['revenue']),
        ];
    }

    private function revenueTrend(string $trend = '7d'): array
    {
        if (! $this->tableExists('transactions')) {
            return $this->emptyRevenueTrend($trend);
        }

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();
        $isMonthly  = $trend === '12m';
        $days       = $trend === '30d' ? 30 : 7;
        $now        = new \DateTime();

        $start = $isMonthly
            ? $this->fmt((clone $now)->modify('-11 months')->modify('first day of this month')->setTime(0, 0, 0))
            : $this->fmt((clone $now)->modify('-' . ($days - 1) . ' days')->setTime(0, 0, 0));
        $end = $isMonthly
            ? $this->fmt((clone $now)->modify('last day of this month')->setTime(23, 59, 59))
            : $this->fmt((clone $now)->setTime(23, 59, 59));

        if ($isMonthly) {
            $rows = $this->successfulTransactionsQuery()
                ->select("YEAR($dateColumn) as year, MONTH($dateColumn) as month, COALESCE(SUM(total), 0) as total")
                ->where("$dateColumn >=", $start)
                ->where("$dateColumn <=", $end)
                ->groupBy("YEAR($dateColumn), MONTH($dateColumn)")
                ->get()->getResultArray();

            $keyed = [];
            foreach ($rows as $r) {
                $keyed[$r['year'] . '-' . $r['month']] = $r['total'];
            }

            return array_map(function ($offset) use ($keyed, $now) {
                $date = (clone $now)->modify("-$offset months");
                $key  = $date->format('Y') . '-' . (int) $date->format('n');
                return ['label' => $date->format('M'), 'total' => (float) ($keyed[$key] ?? 0)];
            }, range(11, 0));
        }

        $rows = $this->successfulTransactionsQuery()
            ->select("DATE($dateColumn) as transaction_date, COALESCE(SUM(total), 0) as total")
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy("DATE($dateColumn)")
            ->get()->getResultArray();

        $keyed = array_column($rows, 'total', 'transaction_date');

        return array_map(function ($offset) use ($keyed, $now) {
            $date = (clone $now)->modify("-$offset days");
            $key  = $date->format('Y-m-d');
            return ['label' => $date->format('d M'), 'total' => (float) ($keyed[$key] ?? 0)];
        }, range($days - 1, 0));
    }

    private function emptyRevenueTrend(string $trend = '7d'): array
    {
        $now = new \DateTime();
        if ($trend === '12m') {
            return array_map(fn($offset) => [
                'label' => (clone $now)->modify("-$offset months")->format('M'),
                'total' => 0,
            ], range(11, 0));
        }
        $days = $trend === '30d' ? 30 : 7;
        return array_map(fn($offset) => [
            'label' => (clone $now)->modify("-$offset days")->format('d M'),
            'total' => 0,
        ], range($days - 1, 0));
    }

    private function hourlyRevenue(): array
    {
        $hours = range(8, 22);
        if (! $this->tableExists('transactions')) {
            return array_map(fn($h) => ['label' => (string) $h, 'total' => 0], $hours);
        }

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();
        [$start, $end] = $this->todayRange();

        $rows = $this->successfulTransactionsQuery()
            ->select("HOUR($dateColumn) as hour, COALESCE(SUM(total), 0) as total")
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy("HOUR($dateColumn)")
            ->get()->getResultArray();

        $keyed = array_column($rows, 'total', 'hour');

        return array_map(fn($h) => [
            'label' => (string) $h,
            'total' => (float) ($keyed[$h] ?? 0),
        ], $hours);
    }

    private function peakHour(): string
    {
        if (! $this->tableExists('transactions')) return '-';

        $dateColumn = $this->transactionDateColumn();
        [$start, $end] = $this->todayRange();

        $row = \Config\Database::connect()->table('transactions')
            ->select("HOUR($dateColumn) as hour, COUNT(*) as total")
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy("HOUR($dateColumn)")
            ->orderBy('total', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        return $row ? str_pad($row['hour'], 2, '0', STR_PAD_LEFT) . '.00' : '-';
    }

    // =========================================================================
    // Private Helpers – Products & Categories
    // =========================================================================

    private function productList(): array
    {
        if (! $this->tableExists('products')) return [];

        $db            = \Config\Database::connect();
        $hasUpdatedAt  = $this->columnExists('products', 'updated_at');
        $updatedSelect = $hasUpdatedAt ? 'products.updated_at' : 'products.created_at as updated_at';

        return $db->table('products')
            ->select("products.id, products.name, products.price, products.stock, products.created_at, $updatedSelect, categories.name as category_name")
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('products.name')
            ->get()->getResultArray();
    }

    private function productSales(string $start, string $end): array
    {
        if (
            ! $this->tableExists('transaction_details') ||
            ! $this->tableExists('transactions') ||
            ! $this->tableExists('products')
        ) return [];

        $db         = \Config\Database::connect();
        $dateColumn = 'transactions.' . $this->transactionDateColumn();

        $query = $db->table('transaction_details')
            ->select('products.id, products.name, categories.name as category_name, COALESCE(SUM(transaction_details.qty), 0) as quantity, COALESCE(SUM(transaction_details.subtotal), 0) as revenue')
            ->join('products', 'products.id = transaction_details.product_id')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('transactions', 'transactions.id = transaction_details.transaction_id')
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy('products.id, products.name, categories.name');

        if ($this->columnExists('transactions', 'status')) {
            $query->where('transactions.status', 'success');
        }

        return $query->get()->getResultArray();
    }

    private function topProducts(string $start, string $end): array
    {
        if (! $this->tableExists('transaction_details') || ! $this->tableExists('products')) return [];

        $db         = \Config\Database::connect();
        $dateColumn = $this->columnExists('transactions', 'paid_at') ? 'transactions.paid_at' : 'transactions.created_at';

        $query = $db->table('transaction_details')
            ->select('products.name, categories.name as category_name, COALESCE(SUM(transaction_details.qty), 0) as quantity, COALESCE(SUM(transaction_details.subtotal), 0) as total')
            ->join('products', 'products.id = transaction_details.product_id')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('transactions', 'transactions.id = transaction_details.transaction_id', 'left')
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy('products.id, products.name, categories.name')
            ->orderBy('quantity', 'DESC')
            ->limit(5);

        return $query->get()->getResultArray();
    }

    private function categoryRevenue(string $start, string $end): array
    {
        if (
            ! $this->tableExists('transaction_details') ||
            ! $this->tableExists('products') ||
            ! $this->tableExists('categories')
        ) return [];

        $db         = \Config\Database::connect();
        $dateColumn = $this->columnExists('transactions', 'paid_at') ? 'transactions.paid_at' : 'transactions.created_at';

        return $db->table('categories')
            ->select('categories.name, COALESCE(SUM(transaction_details.subtotal), 0) as total')
            ->join('products', 'products.category_id = categories.id', 'left')
            ->join('transaction_details', 'transaction_details.product_id = products.id', 'left')
            ->join('transactions', 'transactions.id = transaction_details.transaction_id', 'left')
            ->groupStart()
                ->where('transactions.id IS NULL')
                ->orWhere("$dateColumn >=", $start)
            ->groupEnd()
            ->where("$dateColumn <=", $end)
            ->groupBy('categories.id, categories.name')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();
    }

    private function productCategoryAnalysis(string $currentStart, string $currentEnd, string $prevStart, string $prevEnd): array
    {
        if (! $this->tableExists('categories')) return [];

        $db       = \Config\Database::connect();
        $current  = array_column($this->categoryRevenue($currentStart, $currentEnd),  'total', 'name');
        $previous = array_column($this->categoryRevenue($prevStart, $prevEnd),         'total', 'name');
        $totalRevenue = max((float) array_sum($current), 1);

        $categories = $db->table('categories')->select('id, name')->orderBy('name')->get()->getResultArray();

        $result = array_map(function ($cat) use ($current, $previous, $totalRevenue) {
            $curr = (float) ($current[$cat['name']] ?? 0);
            $prev = (float) ($previous[$cat['name']] ?? 0);
            return [
                'name'    => $cat['name'],
                'revenue' => $curr,
                'percent' => round(($curr / $totalRevenue) * 100, 1),
                'change'  => $this->percentageChange($curr, $prev),
            ];
        }, $categories);

        usort($result, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
        return array_values($result);
    }

    // =========================================================================
    // Private Helpers – Staff
    // =========================================================================

    private function staffUsers(): array
    {
        if (! $this->tableExists('users')) return [];

        $db        = \Config\Database::connect();
        $hasRoles  = $this->tableExists('roles') && $this->columnExists('users', 'role_id');
        $username  = $this->columnExists('users', 'username')   ? 'users.username'   : 'NULL as username';
        $workHours = $this->columnExists('users', 'work_hours') ? 'users.work_hours' : 'NULL as work_hours';
        $status    = $this->columnExists('users', 'status')     ? 'users.status'     : 'NULL as status';
        $roleExpr  = $hasRoles ? "COALESCE(roles.name, 'Karyawan') as role_name" : "'Karyawan' as role_name";

        $query = $db->table('users')
            ->select("users.id, users.name, $username, users.email, $workHours, $status, users.created_at, $roleExpr");

        if ($hasRoles) {
            $query->join('roles', 'roles.id = users.role_id', 'left')
                  ->groupStart()
                      ->where('roles.name IS NULL')
                      ->orWhereIn('roles.name', ['admin', 'kasir'])
                  ->groupEnd();
        }

        return $query->orderBy('users.name')->get()->getResultArray();
    }

    private function staffTransactionStats(string $start, string $end, array $staffIds = []): array
    {
        if (! $this->tableExists('transactions') || ! $this->columnExists('transactions', 'user_id')) return [];

        $db         = \Config\Database::connect();
        $dateColumn = $this->transactionDateColumn();

        $query = $this->successfulTransactionsQuery()
            ->select('user_id, COUNT(*) as transactions, COALESCE(SUM(total), 0) as revenue')
            ->where('user_id IS NOT NULL')
            ->where("$dateColumn >=", $start)
            ->where("$dateColumn <=", $end)
            ->groupBy('user_id');

        if (! empty($staffIds)) {
            $query->whereIn('user_id', $staffIds);
        }

        return $query->get()->getResultArray();
    }

    private function onlineStaffUserIds(array $staffIds): array
    {
        if (! $this->tableExists('sessions') || ! $this->columnExists('sessions', 'user_id') || empty($staffIds)) {
            return [];
        }

        $db = \Config\Database::connect();
        $todayTimestamp = (new \DateTime())->setTime(0, 0, 0)->getTimestamp();

        return array_map('intval', $db->table('sessions')
            ->select('user_id')
            ->whereIn('user_id', $staffIds)
            ->where('last_activity >=', $todayTimestamp)
            ->distinct()
            ->get()->getResultArray());
    }

    private function estimatedWorkHours(int $transactions, ?string $createdAt): int
    {
        $daysSince  = $createdAt ? max(1, (int) (new \DateTime())->diff(new \DateTime($createdAt))->days + 1) : 1;
        $activeDays = min((int) (new \DateTime())->format('j'), $daysSince);
        return min(208, max(0, $activeDays * 8 + (int) floor($transactions / 8)));
    }

    private function staffPerformanceScore(int $transactions, float $revenue): float
    {
        if ($transactions === 0 && $revenue === 0.0) return 0;
        return round((min(0.7, $transactions / 300) + min(0.3, $revenue / 15000000)) * 5, 1);
    }

    private function staffSchedule(int $index): array
    {
        $patterns = [
            ['08-16', '08-16', 'Off', '16-23', '08-16', '09-17', '09-17'],
            ['Off', '16-23', '08-16', '08-16', 'Off', '09-17', '17-00'],
            ['16-23', 'Off', '16-23', '08-16', '08-16', 'Off', '09-17'],
            ['08-16', '08-16', '16-23', 'Off', '16-23', '09-17', 'Off'],
        ];
        return $patterns[$index % count($patterns)];
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));
        return implode('', array_map(fn($p) => strtoupper(substr($p, 0, 1)), array_filter(array_slice($parts, 0, 2))));
    }

    // =========================================================================
    // Private Helpers – Financial
    // =========================================================================

    private function estimatedExpenseBreakdown(float $revenue): array
    {
        return [
            ['category' => 'Bahan Baku',      'amount' => round($revenue * 0.35), 'description' => 'Estimasi biaya pokok penjualan'],
            ['category' => 'Gaji Karyawan',   'amount' => $this->payrollEstimate(), 'description' => 'Berdasarkan jam kerja user'],
            ['category' => 'Operasional',     'amount' => round($revenue * 0.08), 'description' => 'Listrik, air, dan kebutuhan toko'],
            ['category' => 'Lainnya',         'amount' => round($revenue * 0.05), 'description' => 'Biaya tak langsung'],
        ];
    }

    private function payrollEstimate(): float
    {
        if (! $this->tableExists('users') || ! $this->columnExists('users', 'work_hours')) return 0;
        $db = \Config\Database::connect();
        return (float) ($db->table('users')->selectSum('work_hours')->get()->getRowArray()['work_hours'] ?? 0) * 15000;
    }

    private function monthlyProfitTrend(): array
    {
        $now = new \DateTime();
        return array_map(function ($offset) use ($now) {
            $date    = (clone $now)->modify("-$offset months");
            $start   = $this->fmt((clone $date)->modify('first day of this month')->setTime(0, 0, 0));
            $end     = $this->fmt((clone $date)->modify('last day of this month')->setTime(23, 59, 59));
            $summary = $this->transactionSummary($start, $end);
            $expense = array_sum(array_column($this->estimatedExpenseBreakdown($summary['revenue']), 'amount'));
            return [
                'label'  => $date->format('M Y'),
                'profit' => $summary['revenue'] - $expense,
            ];
        }, range(5, 0));
    }

    private function financialRecords(array $expenses): array
    {
        [$todayStart, $todayEnd] = $this->todayRange();
        $today   = $this->transactionSummary($todayStart, $todayEnd);
        $records = [];

        if ($today['revenue'] > 0) {
            $records[] = [
                'date'        => (new \DateTime())->format('Y-m-d'),
                'category'    => 'Pendapatan',
                'description' => 'Penjualan harian',
                'type'        => 'Masuk',
                'amount'      => $today['revenue'],
            ];
        }

        foreach ($expenses as $expense) {
            if ($expense['amount'] > 0) {
                $records[] = [
                    'date'        => (new \DateTime())->modify('first day of this month')->format('Y-m-d'),
                    'category'    => $expense['category'],
                    'description' => $expense['description'],
                    'type'        => 'Keluar',
                    'amount'      => $expense['amount'],
                ];
            }
        }

        return $records;
    }

    private function stockLogs(): array
    {
        if (! $this->tableExists('stock_logs')) {
            return ['data' => [], 'pagination' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 10, 'total' => 0, 'from' => null, 'to' => null]];
        }

        $db      = \Config\Database::connect();
        $perPage = $this->getPerPage();
        $page    = max(1, (int) ($this->request->getGet('logs_page') ?? 1));
        $offset  = ($page - 1) * $perPage;

        $base = $db->table('stock_logs')
            ->select('stock_logs.type, stock_logs.quantity, stock_logs.before_stock, stock_logs.after_stock, stock_logs.note, stock_logs.created_at, products.name as product_name, users.name as user_name')
            ->join('products', 'products.id = stock_logs.product_id', 'left')
            ->join('users', 'users.id = stock_logs.user_id', 'left')
            ->orderBy('stock_logs.id', 'DESC');

        $total = $base->countAllResults(false);
        $data  = $base->limit($perPage, $offset)->get()->getResultArray();

        return [
            'data'       => $data,
            'pagination' => [
                'current_page' => $page,
                'last_page'    => max(1, (int) ceil($total / $perPage)),
                'per_page'     => $perPage,
                'total'        => $total,
                'from'         => $total > 0 ? $offset + 1 : null,
                'to'           => $total > 0 ? min($offset + $perPage, $total) : null,
            ],
        ];
    }

    // =========================================================================
    // Private Helpers – Settings & Performance
    // =========================================================================

    private function ownerSettings(): array
    {
        if (! $this->tableExists('owner_settings')) return [];
        $db   = \Config\Database::connect();
        $rows = $db->table('owner_settings')->get()->getResultArray();
        return array_column($rows, 'value', 'key');
    }

    private function activeProductPercent(): float
    {
        if (! $this->tableExists('products')) return 0;
        $db    = \Config\Database::connect();
        $total = $db->table('products')->countAll();
        if ($total === 0) return 0;
        $active = $this->columnExists('products', 'status')
            ? $db->table('products')->where('status', 1)->countAllResults()
            : $total;
        return round(($active / $total) * 100, 1);
    }

    private function performanceInsights(array $month, float $margin): array
    {
        $insights   = [];
        [$ms, $me]  = $this->currentMonthRange();
        $topProduct = $this->topProducts($ms, $me)[0] ?? null;

        if ($topProduct) {
            $insights[] = ['type' => 'Opportunity', 'message' => $topProduct['name'] . ' sedang paling kuat. Pertahankan stok dan visibilitas menu.'];
        }

        if ($margin < 40 && $month['revenue'] > 0) {
            $insights[] = ['type' => 'Attention', 'message' => 'Margin di bawah target 40%. Cek biaya bahan baku dan promo.'];
        }

        $lowStock = $this->tableExists('products')
            ? \Config\Database::connect()->table('products')->where('stock <=', 10)->countAllResults()
            : 0;

        if ($lowStock > 0) {
            $insights[] = ['type' => 'Recommendation', 'message' => $lowStock . ' produk stok rendah. Prioritaskan restock sebelum jam ramai.'];
        }

        return $insights ?: [['type' => 'Info', 'message' => 'Data operasional stabil. Pantau tren transaksi harian untuk keputusan berikutnya.']];
    }

    private function recentActivities(): array
    {
        $activities = [];

        if ($this->tableExists('transactions')) {
            $db          = \Config\Database::connect();
            $codeColumn  = $this->columnExists('transactions', 'code') ? 'code' : 'invoice_code';
            $dateColumn  = $this->columnExists('transactions', 'paid_at') ? 'paid_at' : 'created_at';

            $txns = $db->table('transactions')
                ->select("id, total, $codeColumn as code, $dateColumn as activity_date")
                ->orderBy('id', 'DESC')
                ->limit(4)
                ->get()->getResultArray();

            foreach ($txns as $t) {
                $activities[] = [
                    'type'        => 'transaction',
                    'title'       => 'Transaksi #' . ($t['code'] ?? str_pad((string) $t['id'], 4, '0', STR_PAD_LEFT)),
                    'description' => 'Total ' . $this->formatRupiah((float) $t['total']),
                    'created_at'  => $t['activity_date'],
                ];
            }
        }

        if ($this->tableExists('products')) {
            $db       = \Config\Database::connect();
            $products = $db->table('products')
                ->select('name, stock, created_at')
                ->where('stock <=', 5)
                ->orderBy('stock', 'ASC')
                ->limit(2)
                ->get()->getResultArray();

            foreach ($products as $p) {
                $activities[] = [
                    'type'        => 'stock',
                    'title'       => 'Stok rendah: ' . $p['name'],
                    'description' => 'Tersisa ' . $p['stock'] . ' unit',
                    'created_at'  => $p['created_at'],
                ];
            }
        }

        usort($activities, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
        return array_values(array_slice($activities, 0, 5));
    }

    // =========================================================================
    // Utility
    // =========================================================================

    private function respond(array $data, int $code = 200)
    {
        return $this->response->setStatusCode($code)->setJSON($data);
    }

    private function percentageChange(float $current, float $previous): float
    {
        if ($previous == 0) return $current > 0 ? 100.0 : 0.0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function formatRupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    private function getPerPage(): int
    {
        return max(1, min((int) ($this->request->getGet('per_page') ?? 10), 100));
    }

    private function paginateArray(array $items, string $pageName = 'page'): array
    {
        $perPage     = $this->getPerPage();
        $total       = count($items);
        $lastPage    = max(1, (int) ceil($total / $perPage));
        $currentPage = max(1, min((int) ($this->request->getGet($pageName) ?? 1), $lastPage));
        $offset      = ($currentPage - 1) * $perPage;

        return [
            'data'       => array_values(array_slice($items, $offset, $perPage)),
            'pagination' => [
                'current_page' => $currentPage,
                'last_page'    => $lastPage,
                'per_page'     => $perPage,
                'total'        => $total,
                'from'         => $total ? $offset + 1 : null,
                'to'           => $total ? min($offset + $perPage, $total) : null,
            ],
        ];
    }

    private function tableExists(string $table): bool
    {
        return \Config\Database::connect()->tableExists($table);
    }

    private function columnExists(string $table, string $column): bool
    {
        return \Config\Database::connect()->fieldExists($column, $table);
    }
}