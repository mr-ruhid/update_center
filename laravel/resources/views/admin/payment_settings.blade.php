@extends('admin.layout')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ödəmə Ayarları</h1>
            <p class="text-sm text-gray-500">Cryptomus (Kripto), Stripe və Bank hesablarını idarə edin.</p>
        </div>
    </div>

    <!-- Mesajlar -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.payment.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- SOL: Valyuta və Cryptomus -->
            <div class="space-y-6">

                <!-- Valyuta -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fa-solid fa-coins mr-2 text-yellow-500"></i> Əsas Valyuta
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valyuta Kodu</label>
                            <input type="text" name="currency" value="{{ $payment->currency ?? 'AZN' }}" placeholder="AZN" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none uppercase font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Simvol</label>
                            <input type="text" name="currency_symbol" value="{{ $payment->currency_symbol ?? '₼' }}" placeholder="₼" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Cryptomus (ƏSAS) -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-orange-500 rounded-bl-full -mr-8 -mt-8 opacity-10"></div>

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fa-solid fa-bitcoin-sign mr-2 text-orange-500 text-xl"></i> Cryptomus
                        </h3>

                        <!-- Aktiv/Deaktiv Toggle -->
                        <div class="flex items-center gap-2 z-10">
                            <span class="text-xs text-gray-500 font-medium">Status:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_cryptomus_active" value="1" class="sr-only peer" {{ ($payment->is_cryptomus_active ?? false) ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4 relative z-10">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Merchant ID</label>
                            <input type="text" name="cryptomus_merchant_id" value="{{ $payment->cryptomus_merchant_id ?? '' }}" placeholder="m_..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-orange-500 outline-none font-mono text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Key (API Key)</label>
                            <input type="password" name="cryptomus_payment_key" value="{{ $payment->cryptomus_payment_key ?? '' }}" placeholder="API Key..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-orange-500 outline-none font-mono text-sm">
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-orange-50 rounded text-xs text-orange-800 border border-orange-100 relative z-10">
                        <i class="fa-solid fa-shield-cat mr-1"></i>
                        VÖEN tələb etmir. <a href="https://cryptomus.com" target="_blank" class="underline font-bold hover:text-orange-900">Cryptomus.com</a>-da qeydiyyatdan keçib məlumatları əldə edin.
                    </div>
                </div>

            </div>

            <!-- SAĞ: Stripe və Bank -->
            <div class="space-y-6">

                <!-- Stripe -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <i class="fa-brands fa-stripe mr-2 text-indigo-600 text-xl"></i> Stripe
                        </h3>

                        <!-- Aktiv/Deaktiv Toggle -->
                        <div class="flex items-center gap-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_stripe_active" value="1" class="sr-only peer" {{ ($payment->is_stripe_active ?? false) ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Public Key</label>
                            <input type="text" name="stripe_public_key" value="{{ $payment->stripe_public_key ?? '' }}" placeholder="pk_test_..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 outline-none font-mono text-xs">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                            <input type="password" name="stripe_secret_key" value="{{ $payment->stripe_secret_key ?? '' }}" placeholder="sk_test_..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 outline-none font-mono text-xs">
                        </div>
                    </div>
                </div>

                <!-- Bank Hesabları -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <i class="fa-solid fa-building-columns mr-2 text-slate-500"></i> Bank Transfer
                        </h3>

                        <!-- Aktiv/Deaktiv Toggle -->
                        <div class="flex items-center gap-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_bank_active" value="1" class="sr-only peer" {{ ($payment->is_bank_active ?? false) ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-slate-600"></div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hesab Məlumatları</label>
                        <textarea name="bank_account_info" rows="3" placeholder="Bank Adı... IBAN..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none text-sm">{{ $payment->bank_account_info }}</textarea>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors flex items-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Yadda Saxla
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection
