<x-guest-layout>
    <x-auth-card>

        <!-- CUSTOM HEADER -->
        <x-slot name="logo">
            <div class="text-center">
                <h2 style="font-weight: 600; font-size: 18px; letter-spacing: 1px;">
                    SDO VALENZUELA
                </h2>
                <p style="font-size: 13px; color: #6c757d; margin-top: -5px;">
                    HR DEPARTMENT
                </p>
            </div>
        </x-slot>

        <!-- MESSAGE -->
        <div class="mb-4 text-sm text-gray-600 text-center">
            Please verify your email address by clicking the link sent to your email.
            If you didn’t receive it, you can request another one below.
        </div>

        <!-- SUCCESS MESSAGE -->
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 text-center">
                A new verification link has been sent to your email.
            </div>
        @endif

        <!-- ACTIONS -->
        <div class="mt-4 flex items-center justify-between">

            <!-- RESEND -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <x-button>
                        Resend Email
                    </x-button>
                </div>
            </form>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm text-gray-500 hover:text-gray-800"
                    style="text-decoration: none;">
                    Logout
                </button>
            </form>

        </div>

    </x-auth-card>
</x-guest-layout>