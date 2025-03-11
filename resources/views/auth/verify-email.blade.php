<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email Address - JobMatch Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-indigo-600 py-6">
                <h1 class="text-center text-white text-2xl font-bold">SEEK</h1>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Verify Your Email Address</h2>

                <!-- Success message (hidden by default) -->
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 hidden"
                    id="success-message">
                    A fresh verification link has been sent to your email address.
                </div>

                <p class="text-center text-gray-600 mb-2">
                    Before proceeding, please check your email for a verification link.
                </p>
                <p class="text-center text-gray-600 mb-8">
                    If you did not receive the email, click the button below.
                </p>

                <!-- Email Icon -->
                <div class="flex justify-center mb-8">
                    <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Resend Button -->
                <div class="flex justify-center">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" id="resend-btn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Resend
                            Verification Email</button>
                    </form>
                </div>

                <!-- Footer -->
                <p class="text-center text-gray-500 mt-8 text-sm">
                    Having trouble? Contact <a href="mailto:support@seek.com"
                        class="text-indigo-600 hover:underline">support@seek.com</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Demo functionality to show success message
        document.getElementById('resend-btn').addEventListener('click', function () {
            var successMessage = document.getElementById('success-message');
            successMessage.classList.remove('hidden');

            // In a real app, this would be an actual form submission
            // that sends a POST request to your verification.resend route
        });
    </script>
</body>

</html>