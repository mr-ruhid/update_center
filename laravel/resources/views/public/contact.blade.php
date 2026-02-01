@extends('layouts.public')

@section('title', ($settings->site_name ?? 'RJ Site Updater') . ' - ' . \App\Models\Translation::get('contact_page_title'))

@section('content')
    <div class="flex-grow">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    {{ \App\Models\Translation::get('contact_hero_title') }}
                </h1>
                <p class="mt-4 text-lg text-gray-500">
                    {{ \App\Models\Translation::get('contact_hero_subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                <!-- Sol: Məlumatlar -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        {{ \App\Models\Translation::get('contact_info_title') }}
                    </h3>

                    <div class="space-y-6">
                        @if(isset($contact->phone_1) && $contact->phone_1)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ \App\Models\Translation::get('contact_phone_label') }}
                                </p>
                                <p class="text-base text-gray-500">{{ $contact->phone_1 }}</p>
                                @if($contact->phone_2)
                                    <p class="text-base text-gray-500">{{ $contact->phone_2 }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(isset($contact->email_receiver) && $contact->email_receiver)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ \App\Models\Translation::get('contact_email_label') }}
                                </p>
                                <p class="text-base text-gray-500">{{ $contact->email_receiver }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Sosial Media -->
                        <div class="pt-6 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-900 mb-4">
                                {{ \App\Models\Translation::get('contact_social_label') }}
                            </p>
                            <div class="flex flex-wrap gap-4">
                                @if(isset($contact->facebook) && $contact->facebook) <a href="{{ $contact->facebook }}" target="_blank" class="text-gray-400 hover:text-blue-600 text-xl"><i class="fa-brands fa-facebook"></i></a> @endif
                                @if(isset($contact->instagram) && $contact->instagram) <a href="{{ $contact->instagram }}" target="_blank" class="text-gray-400 hover:text-pink-600 text-xl"><i class="fa-brands fa-instagram"></i></a> @endif
                                @if(isset($contact->twitter) && $contact->twitter) <a href="{{ $contact->twitter }}" target="_blank" class="text-gray-400 hover:text-black text-xl"><i class="fa-brands fa-x-twitter"></i></a> @endif
                                @if(isset($contact->linkedin) && $contact->linkedin) <a href="{{ $contact->linkedin }}" target="_blank" class="text-gray-400 hover:text-blue-700 text-xl"><i class="fa-brands fa-linkedin"></i></a> @endif
                                @if(isset($contact->github) && $contact->github) <a href="{{ $contact->github }}" target="_blank" class="text-gray-400 hover:text-gray-900 text-xl"><i class="fa-brands fa-github"></i></a> @endif
                                @if(isset($contact->behance) && $contact->behance) <a href="{{ $contact->behance }}" target="_blank" class="text-gray-400 hover:text-blue-500 text-xl"><i class="fa-brands fa-behance"></i></a> @endif
                                @if(isset($contact->tiktok) && $contact->tiktok) <a href="{{ $contact->tiktok }}" target="_blank" class="text-gray-400 hover:text-black text-xl"><i class="fa-brands fa-tiktok"></i></a> @endif
                                @if(isset($contact->threads) && $contact->threads) <a href="{{ $contact->threads }}" target="_blank" class="text-gray-400 hover:text-black text-xl"><i class="fa-brands fa-threads"></i></a> @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sağ: Form -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        {{ \App\Models\Translation::get('contact_form_title') }}
                    </h3>

                    @if(session('success'))
                        <div class="mb-4 bg-green-50 text-green-700 p-3 rounded text-sm border border-green-100">
                            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ \App\Models\Translation::get('form_name_label') }}
                            </label>
                            <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ \App\Models\Translation::get('form_email_label') }}
                            </label>
                            <input type="email" name="email" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ \App\Models\Translation::get('form_subject_label') }}
                            </label>
                            <input type="text" name="subject" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ \App\Models\Translation::get('form_message_label') }}
                            </label>
                            <textarea name="message" rows="4" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors shadow-sm flex justify-center items-center">
                            {{ \App\Models\Translation::get('form_send_button') }} <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
