<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function getFilter($filter)
    {
        switch ($filter) {
            case 'DAY':
                $date_filter = [now(), now(), now()->subDay(), now()->subDay()];
                break;
            case 'MONTH':
                $last = now()->subMonth();
                $date_filter = [now(), now()->setDay(1), now()->subMonth()->setDay($last->endOfMonth()->format('d')), now()->subMonth()->setDay(1)];
                break;
            default:
                $last = now()->subYear()->setMonth(12);
                $date_filter = [now(), now()->setMonth(1)->setDay(1), now()->subYear()->setMonth(12)->setDay($last->endOfMonth()->format('d')), now()->subYear()->setMonth(1)->setDay(1)];
                break;
        }
        return collect($date_filter)->map(fn ($d, $k) => $d->format('Y-m-d ') . ($k % 2 != 0 ? '00:00:00' : '23:59:59'));
    }

    private function reports()
    {
        $filter_report = request()->get('report_filter', 'DAY');

        $now = substr(now('Asia/Jakarta')->toISOString(true), 0, 26);
        if ($filter_report == 'DAY') {
            $format = ['%Y-%m-%d %H:00:00', 'Y-m-d H:00:00'];
            $period = now()->setHour(1)->range($now, 1, 'hour');
            $between = [now()->setHour(1), now()];
        } else if ($filter_report == 'MONTH') {
            $format = ['%Y-%m-%d', 'Y-m-d'];
            $period = now()->setDay(1)->range($now, 1, 'day');
            $between = [now()->setDay(1), now()];
        } else {
            $format = ['%Y-%m', 'Y-m'];
            $period = now()->setMonth(1)->range($now, 1, 'month');
            $between = [now()->setMonth(1), now()];
        }

        $total = "(SELECT SUM(total) FROM transaction_detail WHERE transaction_id = id) * days + delivery_fee";
        $transaction = DB::table('transactions')
            ->whereBetween('created_at', $between)
            ->selectRaw("DATE_FORMAT(created_at, '$format[0]') AS date, count(*) AS total")
            ->groupByRaw("DATE_FORMAT(created_at, '$format[0]')")->orderBy("date")->get();
        $income = DB::table('transactions')
            ->whereBetween('created_at', $between)->where('status', '!=', 'pending')
            ->selectRaw("DATE_FORMAT(created_at, '$format[0]') AS date, sum($total) AS total")
            ->groupByRaw("DATE_FORMAT(created_at, '$format[0]')")->orderBy("date")->get();
        $customer = DB::table('users')
            ->whereBetween('created_at', $between)->where('role', 'Customer')
            ->selectRaw("DATE_FORMAT(created_at, '$format[0]') AS date, count(*) AS total")
            ->groupByRaw("DATE_FORMAT(created_at, '$format[0]')")->orderBy("date")->get();

        $series = [
            ['name' => 'Transaksi', 'data' => []],
            ['name' => 'Pendapatan', 'data' => []],
            ['name' => 'Pengguna', 'data' => []],
        ];
        $categories = [];
        foreach ($period as $p) {
            $p->timezone('-07:00');
            $t = $transaction->where('date', $p->format($format[1]))->first();
            if ($t) $series[0]['data'][] = $t->total;
            else $series[0]['data'][] = 0;

            $i = $income->where('date', $p->format($format[1]))->first();
            if ($i) $series[1]['data'][] = $i->total / 10000;
            else $series[1]['data'][] = 0;

            $c = $customer->where('date', $p->format($format[1]))->first();
            if ($c) $series[2]['data'][] = $c->total;
            else $series[2]['data'][] = 0;

            $categories[] = $p->timezone('UTC')->format('Y-m-d\TH:00:00.000000\Z');
        }

        return compact('series', 'categories');
    }

    private function productPopular()
    {
        return Product::whereHas('transactionDetails', function ($q) {
            $q->whereHas('transaction', fn ($t) => $t->where('status', '!=', 'pending'));
        })->withCount(['transactionDetails as sold' => function ($q) {
            $q->whereHas('transaction', fn ($t) => $t->where('status', '!=', 'pending'));
        }])->withSum('transactionDetails as revenue', 'total', function ($q) {
            $q->whereHas('transaction', fn ($t) => $t->where('status', '!=', 'pending'));
        })->orderBy('sold', 'desc')->limit(10)->get();
    }

    public function admin(Request $request)
    {
        $transaction_filter = $this->getFilter($request->get('transaction_filter', 'DAY'));
        $transaction = DB::table('transactions')
            ->selectRaw("COUNT(CASE WHEN DATE(created_at) BETWEEN '$transaction_filter[1]' AND '$transaction_filter[0]' THEN 1 END) AS `current`")
            ->selectRaw("COUNT(CASE WHEN DATE(created_at) BETWEEN '$transaction_filter[3]' AND '$transaction_filter[2]' THEN 1 END) AS `last`")
            ->first();

        $income_filter = $this->getFilter($request->get('income_filter', 'DAY'));
        $total = "(SELECT SUM(total) FROM transaction_detail td WHERE td.transaction_id = id) * days + delivery_fee";
        $income = DB::table('transactions')
            ->selectRaw("COALESCE(SUM(CASE WHEN DATE(created_at) BETWEEN '$income_filter[1]' AND '$income_filter[0]' THEN $total END), 0) AS `current`")
            ->selectRaw("COALESCE(SUM(CASE WHEN DATE(created_at) BETWEEN '$income_filter[3]' AND '$income_filter[2]' THEN $total END), 0) AS `last`")
            ->where('status', '!=', 'pending')
            ->first();

        $customer_filter = $this->getFilter($request->get('customer_filter', 'DAY'));
        $customer = DB::table('users')
            ->selectRaw("COUNT(CASE WHEN DATE(created_at) BETWEEN '$customer_filter[1]' AND '$customer_filter[0]' THEN 1 END) AS `current`")
            ->selectRaw("COUNT(CASE WHEN DATE(created_at) BETWEEN '$customer_filter[3]' AND '$customer_filter[2]' THEN 1 END) AS `last`")
            ->where('role', 'Customer')
            ->first();

        $reports = $this->reports();

        $products = $this->productPopular();

        return response()->json(compact('transaction', 'income', 'customer', 'reports', 'products'));
    }
}
