<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PersonalAccountingController extends Controller
{
    public function index()
    {
        // محاسبه آمار کلی
        $transactions = Transaction::whereHas('person', function($q) {
            $q->where('personal_accounting', true);
        })->get();

        $totalIncome = $transactions->whereIn('type', ['income', 'receive', 'credit'])
            ->sum('amount');

        $totalExpense = $transactions->whereIn('type', ['expense', 'pay', 'debt'])
            ->sum('amount');

        $totalDebt = $transactions->where('type', 'debt')->sum('amount');
        $totalCredit = $transactions->where('type', 'credit')->sum('amount');

        $thisMonth = Carbon::now()->startOfMonth();
        $thisMonthTransactions = $transactions->where('created_at', '>=', $thisMonth);

        $thisMonthIncome = $thisMonthTransactions->whereIn('type', ['income', 'receive', 'credit'])
            ->sum('amount');

        $thisMonthExpense = $thisMonthTransactions->whereIn('type', ['expense', 'pay', 'debt'])
            ->sum('amount');

        // تعداد بدهکاران
        $debtorsCount = Person::where('personal_accounting', true)
            ->whereHas('transactions', function($q) {
                $q->where('type', 'debt');
            })->count();

        // مانده کل
        $totalBalance = $totalIncome - $totalExpense;

        // لیست اشخاص
        $people = Person::where('personal_accounting', true)
            ->with('transactions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('personal_accounting.index', compact(
            'people',
            'totalIncome',
            'totalExpense',
            'totalDebt',
            'totalBalance',
            'thisMonthIncome',
            'thisMonthExpense',
            'debtorsCount'
        ));
    }

    public function searchPerson(Request $request)
    {
        $q = $request->get('q');
        $query = Person::query();

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('first_name', 'like', "%$q%")
                    ->orWhere('last_name', 'like', "%$q%")
                    ->orWhere('mobile', 'like', "%$q%");
            });
        }

        $list = $query->limit(10)->get(['id', 'first_name', 'last_name', 'mobile']);
        return response()->json($list);
    }

    public function addPerson(Request $request)
    {
        $person = Person::findOrFail($request->person_id);
        $person->personal_accounting = true;
        $person->save();
        return response()->json(['success' => true]);
    }

    public function removePerson(Person $person)
    {
        $person->personal_accounting = false;
        $person->save();
        return redirect()
            ->route('personal_accounting.index')
            ->with('success', 'شخص با موفقیت از حسابداری حذف شد.');
    }

    public function exportData()
    {
        $data = [
            'people' => Person::where('personal_accounting', true)->with('transactions')->get(),
            'exported_at' => now()
        ];
        return response()->json($data);
    }

    public function importData(Request $request)
    {
        try {
            foreach ($request->people as $personData) {
                $person = Person::find($personData['id']);
                if ($person) {
                    $person->personal_accounting = true;
                    $person->save();

                    foreach ($personData['transactions'] as $trx) {
                        if (!Transaction::find($trx['id'])) {
                            $person->transactions()->create([
                                'type' => $trx['type'],
                                'amount' => $trx['amount'],
                                'description' => $trx['description'],
                                'created_at' => $trx['created_at']
                            ]);
                        }
                    }
                }
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function addReminder(Request $request)
    {
        try {
            $reminder = new \App\Models\Reminder([
                'person_id' => $request->person_id,
                'date' => Carbon::parse($request->date),
                'text' => $request->text,
            ]);
            $reminder->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
