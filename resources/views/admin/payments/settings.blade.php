@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Payment Accounts & Gateway Configuration</h1>
            <p class="text-xs text-slate-500 mt-1">Configure manual payment beneficiary accounts, IBANs, numbers and step-by-step customer instructions</p>
        </div>
        <a href="{{ route('admin.payments') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs px-4 py-2 rounded-lg border border-slate-300 transition flex items-center gap-1.5 self-start sm:self-auto">
            <i class="fa-solid fa-arrow-left"></i> Back to Payments
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-xl flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 text-xs p-4 rounded-xl">
        <div class="font-bold mb-1">Please fix the following validation errors:</div>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Easypaisa Card -->
        @php $ep = $settings['easypaisa'] ?? null; @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-150">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </span>
                    <div>
                        <h2 class="font-extrabold text-sm text-slate-900">Easypaisa Mobile Account</h2>
                        <span class="text-[11px] text-slate-500">Mobile wallet transfer configuration</span>
                    </div>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="settings[easypaisa][is_active]" value="1" class="sr-only peer" {{ ($ep && $ep->is_active) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    <span class="ml-2 text-xs font-bold text-slate-700">Enable</span>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Account Title</label>
                    <input type="text" name="settings[easypaisa][account_title]" value="{{ old('settings.easypaisa.account_title', $ep->account_title ?? '') }}"
                        placeholder="e.g. Juris Sentinel Legal Services"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Easypaisa Account Number / Phone</label>
                    <input type="text" name="settings[easypaisa][account_number]" value="{{ old('settings.easypaisa.account_number', $ep->account_number ?? '') }}"
                        placeholder="e.g. 0300-1234567"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600 font-mono">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Customer Payment Instructions</label>
                    <textarea name="settings[easypaisa][instructions]" rows="3"
                        placeholder="Step-by-step instructions shown to customer on payment screen..."
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">{{ old('settings.easypaisa.instructions', $ep->instructions ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- JazzCash Card -->
        @php $jc = $settings['jazzcash'] ?? null; @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-150">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-wallet"></i>
                    </span>
                    <div>
                        <h2 class="font-extrabold text-sm text-slate-900">JazzCash Mobile Account</h2>
                        <span class="text-[11px] text-slate-500">Mobile account transfer configuration</span>
                    </div>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="settings[jazzcash][is_active]" value="1" class="sr-only peer" {{ ($jc && $jc->is_active) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    <span class="ml-2 text-xs font-bold text-slate-700">Enable</span>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Account Title</label>
                    <input type="text" name="settings[jazzcash][account_title]" value="{{ old('settings.jazzcash.account_title', $jc->account_title ?? '') }}"
                        placeholder="e.g. Juris Sentinel Legal Services"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">JazzCash Mobile Number</label>
                    <input type="text" name="settings[jazzcash][account_number]" value="{{ old('settings.jazzcash.account_number', $jc->account_number ?? '') }}"
                        placeholder="e.g. 0301-7654321"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 font-mono">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Customer Payment Instructions</label>
                    <textarea name="settings[jazzcash][instructions]" rows="3"
                        placeholder="Step-by-step instructions shown to customer on payment screen..."
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('settings.jazzcash.instructions', $jc->instructions ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Bank Transfer Card -->
        @php $bt = $settings['bank_transfer'] ?? null; @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-150">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-building-columns"></i>
                    </span>
                    <div>
                        <h2 class="font-extrabold text-sm text-slate-900">Direct Bank Transfer / Online Wire</h2>
                        <span class="text-[11px] text-slate-500">Commercial bank account configuration</span>
                    </div>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="settings[bank_transfer][is_active]" value="1" class="sr-only peer" {{ ($bt && $bt->is_active) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-2 text-xs font-bold text-slate-700">Enable</span>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Bank Name</label>
                    <input type="text" name="settings[bank_transfer][bank_name]" value="{{ old('settings.bank_transfer.bank_name', $bt->bank_name ?? '') }}"
                        placeholder="e.g. Standard Chartered / HBL"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Account Title / Beneficiary Name</label>
                    <input type="text" name="settings[bank_transfer][account_title]" value="{{ old('settings.bank_transfer.account_title', $bt->account_title ?? '') }}"
                        placeholder="e.g. Juris Sentinel Legal Portal PVT"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Account Number</label>
                    <input type="text" name="settings[bank_transfer][account_number]" value="{{ old('settings.bank_transfer.account_number', $bt->account_number ?? '') }}"
                        placeholder="e.g. 0102938475610023"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 font-mono">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">IBAN Number</label>
                    <input type="text" name="settings[bank_transfer][iban]" value="{{ old('settings.bank_transfer.iban', $bt->iban ?? '') }}"
                        placeholder="e.g. PK36SCBL0000001029384756"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 font-mono uppercase">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Branch Name & Code</label>
                    <input type="text" name="settings[bank_transfer][branch_code]" value="{{ old('settings.bank_transfer.branch_code', $bt->branch_code ?? '') }}"
                        placeholder="e.g. Branch Code 0142 - Corporate Branch"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Customer Payment Instructions</label>
                    <textarea name="settings[bank_transfer][instructions]" rows="3"
                        placeholder="Step-by-step instructions shown to customer on payment screen..."
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">{{ old('settings.bank_transfer.instructions', $bt->instructions ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Save Button Bar -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center justify-between">
            <span class="text-xs text-slate-500">
                <i class="fa-solid fa-circle-info text-blue-600 mr-1"></i> Changes will immediately take effect on customer payment checkout screens.
            </span>
            <button type="submit" class="bg-navy-900 hover:bg-blue-600 text-white font-extrabold text-xs px-6 py-2.5 rounded-lg transition shadow-md flex items-center gap-1.5">
                <i class="fa-solid fa-floppy-disk"></i> Save Payment Settings
            </button>
        </div>

    </form>

</div>
@endsection
