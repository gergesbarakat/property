<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\Maintainer;
use App\Models\MaintenanceRequest;
use App\Models\NoticeBoard;
use App\Models\PackageTransaction;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Subscription;
use App\Models\Support;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Installment;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        if (\Auth::check()) {
            if (\Auth::user()->type == 'super admin') {
                // ... (super admin logic remains the same)
                $result['totalOrganization'] = User::where('type', 'owner')->count();
                $result['totalSubscription'] = Subscription::count();
                $result['totalTransaction'] = PackageTransaction::count();
                $result['totalIncome'] = PackageTransaction::sum('amount');
                $result['totalNote'] = NoticeBoard::count();
                $result['totalContact'] = Contact::count();
                $result['organizationByMonth'] = $this->organizationByMonth();
                $result['paymentByMonth'] = $this->paymentByMonth();
                return view('dashboard.super_admin', compact('result'));
            } else {
                // --- Logic for Owner/Admin Dashboard ---

                // ✅ FIX: Removed all instances of where('parent_id', parentId())
                $result['totalProperty'] = Property::count();
                $result['totalUnit'] = PropertyUnit::count();
                $result['totalIncome'] = InvoiceItem::sum('amount');
                $result['totalExpense'] = Expense::sum('amount');
                $result['recentProperty'] = Property::orderby('id', 'desc')->limit(5)->get();
                $result['recentTenant'] = Tenant::orderby('id', 'desc')->limit(5)->get();
                $result['incomeExpenseByMonth'] = $this->incomeByMonth();
                $result['settings'] = settings();

                // Upcoming Installments Data
                $now = Carbon::now();

                $dueThisWeek = Installment::with('buyer.user', 'buyer.propertyUnit.property')
                    ->where('status', 'pending')
                    ->whereBetween('due_date', [$now->copy()->startOfDay(), $now->copy()->endOfWeek()])
                    ->orderBy('due_date', 'asc')
                    ->get();

                $dueThisMonth = Installment::with('buyer.user', 'buyer.propertyUnit.property')
                    ->where('status', 'pending')
                    ->whereBetween('due_date', [$now->copy()->startOfDay(), $now->copy()->endOfMonth()])
                    ->orderBy('due_date', 'asc')
                    ->get();

                return view('dashboard.index', compact('result', 'dueThisWeek', 'dueThisMonth'));
            }
        } else {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            } else {
                $landingPage = getSettingsValByName('landing_page');
                if ($landingPage == 'on') {
                    $subscriptions = Subscription::get();
                    return view('layouts.landing', compact('subscriptions'));
                } else {
                    return redirect()->route('login');
                }
            }
        }
    }

    public function organizationByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));
        $currentdate = $start;
        $organization = [];
        while ($currentdate <= $end) {
            $organization['label'][] = date('M-Y', $currentdate);
            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $organization['data'][] = User::where('type', 'owner')->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
            $currentdate = strtotime('+1 month', $currentdate);
        }
        return $organization;
    }

    public function paymentByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));
        $currentdate = $start;
        $payment = [];
        while ($currentdate <= $end) {
            $payment['label'][] = date('M-Y', $currentdate);
            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $payment['data'][] = PackageTransaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
            $currentdate = strtotime('+1 month', $currentdate);
        }
        return $payment;
    }

    public function incomeByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));
        $currentdate = $start;
        $payment = [];
        while ($currentdate <= $end) {
            $payment['label'][] = date('M-Y', $currentdate);
            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);

            // ✅ FIX: Removed where('parent_id', parentId()) from these queries
            $payment['income'][] = InvoiceItem::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
            $payment['expense'][] = Expense::whereMonth('date', $month)->whereYear('date', $year)->sum('amount');
            $currentdate = strtotime('+1 month', $currentdate);
        }
        return $payment;
    }
}
