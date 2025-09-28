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
                // Super admin dashboard logic...
                return view('dashboard.super_admin', /* ... */);
            } else {

                // --- Owner/Admin Dashboard Logic ---

                // ✅ FIX: Calculate stats for ACTIVE properties and their units
                $result['totalProperty'] = Property::where('is_active', 1)->count();
                $result['totalUnit'] = PropertyUnit::whereHas('property', function ($q) {
                    $q->where('is_active', 1);
                })->count();

                // Get all active property IDs
                $activePropertyIds = Property::where('is_active', 1)->pluck('id');

                // ✅ FIX: Calculate income and expense based only on active properties
                $result['totalIncome'] = InvoiceItem::whereHas('invoice', function ($q) use ($activePropertyIds) {
                    $q->whereIn('property_id', $activePropertyIds);
                })->sum('amount');

                $result['totalExpense'] = Expense::whereIn('property_id', $activePropertyIds)->sum('amount');

                // ✅ NEW: Fetch upcoming installments for this month and next month
                $result['dueThisMonth'] = Installment::with(['buyer.user', 'buyer.linked_property', 'buyer.propertyUnit'])
                    ->where('status', '!=', 'paid')
                    ->whereMonth('due_date', now()->month)
                    ->whereYear('due_date', now()->year)
                    ->orderBy('due_date', 'asc')
                    ->get();

                $result['dueNextMonth'] = Installment::with(['buyer.user', 'buyer.linked_property', 'buyer.propertyUnit'])
                    ->where('status', '!=', 'paid')
                    ->whereMonth('due_date', now()->addMonth()->month)
                    ->whereYear('due_date', now()->addMonth()->year)
                    ->orderBy('due_date', 'asc')
                    ->get();

                // Chart data and settings
                $result['incomeExpenseByMonth'] = $this->incomeByMonth();
                $result['settings'] = settings();

                return view('dashboard.index', compact('result'));
            }
        } else {
            // Landing page logic for guests...
            return redirect()->route('login');
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
            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $payment['label'][] = date('M', $currentdate);

            // Correctly sum from InvoiceItem for income
            $payment['income'][] = InvoiceItem::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
            $payment['expense'][] = Expense::whereMonth('date', $month)->whereYear('date', $year)->sum('amount');

            $currentdate = strtotime('+1 month', $currentdate);
        }

        return $payment;
    }
}
