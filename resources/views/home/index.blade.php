@extends('home.homeLayout')
@push('styles')
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap");

        body {
            font-family: "Poppins", sans-serif;
        }

        /* Modern ticket shape with perforated edges */
        .ticket {
            position: relative;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            border-radius: 1rem;
            color: white;
            width: 320px;
            height: 160px;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
            overflow: hidden;
        }

        .ticket::before,
        .ticket::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 20px;
            height: 40px;
            background: white;
            border-radius: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .ticket::before {
            left: -10px;
        }

        .ticket::after {
            right: -10px;
        }

        .ticket-perforation {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 2px;
            background: repeating-linear-gradient(to bottom,
                    white,
                    white 6px,
                    transparent 6px,
                    transparent 12px);
            transform: translateX(-50%);
            z-index: 5;
        }

        .ticket-content {
            position: relative;
            height: 100%;
            padding: 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-header {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .ticket-subheader {
            font-weight: 600;
            font-size: 0.875rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        .ticket-stars {
            display: flex;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .ticket-stars i {
            color: #facc15;
        }

        .ticket-footer {
            font-weight: 600;
            font-size: 1.125rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-align: center;
            background: rgba(255 255 255 / 0.15);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            user-select: none;
        }

        @media (max-width: 640px) {
            .ticket {
                width: 100%;
                height: 140px;
            }
        }
    </style>
@endpush
@section('content')
    <main class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <section class="container pt-10 pb-16 md:pb-20 lg:pb-24">
            <h1 class="text-4xl sm:text-4xl md:text-5xl font-extrabold leading-tight max-w-4xl">
                Sistem Kami makes systems for your business.
                <br />
                We build ready-made
                <span class="inline-block relative">
                    <img alt="silhouettes of people in an event"
                        class="inline-block w-[120px] h-[40px] object-cover rounded-md mx-2" height="40"
                        src="https://storage.googleapis.com/a1aa/image/81f28ef4-9552-4caa-ff35-969ec25cd091.jpg"
                        width="120" />
                </span>
                or
                <span class="inline-block relative">
                    <img alt="city skyline at night" class="inline-block w-[120px] h-[40px] object-cover rounded-md mx-2"
                        height="40" src="https://storage.googleapis.com/a1aa/image/3d77011a-1bfe-4c66-5275-5e435dda84b5.jpg"
                        width="120" />
                </span>
                custom software to help you work better.
            </h1>
            <p class="mt-4 max-w-xl text-gray-600 text-sm sm:text-base leading-relaxed">
                Simple. Fast. For you.
            </p>
            <button
                class="mt-6 bg-gray-900 text-white text-xs font-semibold px-6 py-2 rounded-md hover:bg-gray-800 transition"
                type="button">
                Contact us NOW
            </button>
            <div class="mt-10 flex items-center space-x-10 max-w-md">
                <img alt="Border company logo" class="h-6 object-contain" height="24"
                    src="https://storage.googleapis.com/a1aa/image/d3fd383c-735d-41e2-d454-d369a5da6204.jpg" width="80" />
                <img alt="hues company logo" class="h-6 object-contain" height="24"
                    src="https://storage.googleapis.com/a1aa/image/75b95c7e-e315-470e-d749-abb4e02c9aad.jpg" width="80" />
                <img alt="Leafe company logo" class="h-6 object-contain" height="24"
                    src="https://storage.googleapis.com/a1aa/image/789f6300-112c-432d-d426-1a285cd1d308.jpg" width="80" />
            </div>
            <!-- <div class="mt-10 max-w-xs relative">
                                                <div class="ticket">
                                                    <div class="ticket-perforation"></div>
                                                    <div class="ticket-content">
                                                        <div>
                                                            <div class="ticket-header">
                                                                ONE WAY TICKET
                                                            </div>
                                                            <div class="ticket-subheader">
                                                                Sistem Kami Exclusive
                                                            </div>
                                                            <div class="ticket-stars" aria-label="3 star rating">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ticket-footer select-none">
                                                            01
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="absolute top-2 right-2 text-xs text-white/80 font-semibold select-none">
                                                    The best ticket category
                                                    <br />
                                                    currently available
                                                </div>
                                            </div> -->
        </section>
        <!-- Decide To Join Section -->
        <section class="container bg-gray-900 text-white px-4 py-12 rounded-lg mb-16">
            <h2 class="text-3xl font-extrabold max-w-4xl leading-tight mb-4 text-white">
                Decide To
                <span class="font-bold">
                    Create
                </span>
                Your system.
            </h2>
            <p class="max-w-3xl text-gray-300 text-sm sm:text-base mb-6 leading-relaxed">
                Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Veniam Dignissim Ullamco Non Aliquat Ex Aliquet
                Porta. Sectetur Adipisicing Elit, Molestie, Veniam Dignissim Ullamco Non Aliquet.
            </p>
            <a class="text-xs font-semibold underline mb-8 inline-block" href="#">
                See Event Schedule
            </a>
            <div class="flex flex-col md:flex-row md:space-x-10 items-center">
                <div class="flex-1 max-w-md mb-6 md:mb-0">
                    <img alt="Back view of a speaker raising hand on stage in front of audience"
                        class="rounded-md w-full object-cover" height="250"
                        src="https://storage.googleapis.com/a1aa/image/dc8a4b65-dbed-4031-a2ce-c4bc52329c0e.jpg"
                        width="400" />
                    <button
                        class="mt-3 bg-white text-gray-900 text-xs font-semibold px-4 py-2 rounded-md hover:bg-gray-100 transition"
                        type="button">
                        Attend The Event 7
                    </button>
                </div>
                <div class="flex-1 max-w-xs text-center md:text-left">
                    <button aria-label="Play video"
                        class="bg-white text-gray-900 rounded-full w-14 h-14 flex items-center justify-center mx-auto md:mx-0 mb-6 hover:bg-gray-100 transition">
                        <i class="fas fa-play text-lg">
                        </i>
                    </button>
                    <div class="bg-gray-800 text-gray-300 text-xs rounded-md p-3">
                        <div class="flex justify-between mb-1">
                            <div>
                                <div class="font-semibold text-white text-sm">
                                    Remaining Times
                                </div>
                                <div class="text-xs">
                                    23 03 06
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-white text-sm">
                                    Concerts
                                </div>
                                <div class="text-xs">
                                    06 &amp; 07th
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Detail Custom Section -->
        <section class="container mb-20 max-w-5xl mx-auto px-4 sm:px-0">
            <div class="mb-6 max-w-2xl">
                <h3 class="text-2xl font-extrabold mb-3 leading-snug">
                    We bring together
                    smart strategies and useful features.
                </h3>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-6">
                    Every detail is designed to help your business run smoothly.
                    Custom or ready-made systems — made simple for you.
                </p>
                <button
                    class="inline-flex items-center bg-gray-900 text-white text-xs font-semibold px-6 py-2 rounded-md hover:bg-gray-800 transition"
                    type="button">
                    Learn More
                    <i class="fas fa-arrow-right ml-2">
                    </i>
                </button>
            </div>
        </section>
        <!-- Meet Our Artist Section -->
        <section
            class="container mb-20 max-w-5xl mx-auto px-4 sm:px-0 flex flex-col md:flex-row items-center md:items-start space-y-8 md:space-y-0 md:space-x-10">
            <div class="relative w-full max-w-xs md:max-w-[280px]">
                <img alt="Silhouette of an artist on stage with spotlight and smoke" class="rounded-md shadow-lg"
                    height="400" src="https://storage.googleapis.com/a1aa/image/1edf274c-0c5d-44a5-8263-c3e6f4be3526.jpg"
                    width="280" />
                <button
                    class="absolute bottom-4 left-4 bg-gray-900 text-white text-xs font-semibold px-4 py-2 rounded-md hover:bg-gray-800 transition"
                    type="button">
                    See More
                </button>
            </div>
            <div class="max-w-xl text-gray-900">
                <h4 class="text-xl font-extrabold mb-3">
                    STRIKE
                    <span class="font-bold">
                        JORAN
                    </span>
                </h4>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-4">
                    Event
                    <br />
                    <br />
                    <!-- Ullamco Sociisget Eget Dolor Amet Dui Dolor Taciti Taciti Ullamco Pretium Ac. Sociisget Amet Sit Id
                                                    Amet Magna Nisi. Ullamco Semper Odio Lorem Molestie Nunc Eget. -->
                </p>
                <!-- <p class="text-xs text-gray-500 mb-6">
                                                    @AnirudhRavichander Tour On March-21
                                                    <br />
                                                    Tickets Open Now: Sitlix.Com
                                                </p> -->
            </div>
            <div class="relative w-full max-w-xs md:max-w-[280px]">
                <img alt="Silhouette of an artist on stage with spotlight and smoke" class="rounded-md shadow-lg"
                    height="400" src="https://storage.googleapis.com/a1aa/image/1edf274c-0c5d-44a5-8263-c3e6f4be3526.jpg"
                    width="280" />
                <button
                    class="absolute bottom-4 left-4 bg-gray-900 text-white text-xs font-semibold px-4 py-2 rounded-md hover:bg-gray-800 transition"
                    type="button">
                    Coming Soon
                </button>
            </div>
            <div class="max-w-xl text-gray-900">
                <h4 class="text-xl font-extrabold mb-3">
                    Dayangyunk
                    <span class="font-bold">
                        <!-- Artist -->
                    </span>
                </h4>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-4">
                    Wedding Planner System
                    <br />
                    <br />
                    <!-- Ullamco Sociisget Eget Dolor Amet Dui Dolor Taciti Taciti Ullamco Pretium Ac. Sociisget Amet Sit Id
                                                    Amet Magna Nisi. Ullamco Semper Odio Lorem Molestie Nunc Eget. -->
                </p>
                <p class="text-xs text-gray-500 mb-6">
                    <!-- @AnirudhRavichander Tour On March-21
                                                    <br />
                                                    Tickets Open Now: Sitlix.Com -->
                </p>
            </div>
        </section>
        <!-- Opinions Section -->
        <section class="container max-w-5xl mx-auto px-4 sm:px-0 mb-20">
            <h3 class="text-xl font-extrabold mb-6">
                We Value Your Feedback
            </h3>
            <p class="text-gray-600 text-sm sm:text-base mb-8">
                Who should join us? Anyone looking for tailored systems and solutions to make their work easier and more
                efficient.
                <br>We listen to your needs and deliver smart, reliable tools built just for you.
            </p>
            <div class="space-y-4 text-gray-900 font-semibold text-sm">
                <button aria-controls="faq1" aria-expanded="false"
                    class="w-full flex justify-between items-center border border-gray-300 rounded-md px-4 py-3 hover:bg-gray-100 transition"
                    type="button">
                    <span>
                        How do I get my first system setup?
                    </span>
                    <i class="fas fa-plus">
                    </i>
                </button>
                <button aria-controls="faq2" aria-expanded="false"
                    class="w-full flex justify-between items-center border border-gray-300 rounded-md px-4 py-3 hover:bg-gray-100 transition"
                    type="button">
                    <span>
                        What features are included?
                    </span>
                    <i class="fas fa-plus">
                    </i>
                </button>
                <button aria-controls="faq3" aria-expanded="false"
                    class="w-full flex justify-between items-center border border-gray-300 rounded-md px-4 py-3 hover:bg-gray-100 transition"
                    type="button">
                    <span>
                        We welcome our local Malaysian business
                    </span>
                    <i class="fas fa-plus">
                    </i>
                </button>
            </div>
        </section>
        <!-- Get Your First Ticket Section -->
        <section class="container bg-gray-900 text-white py-16 px-4 rounded-lg mb-20 relative overflow-hidden">
            <h3 class="text-3xl font-extrabold max-w-4xl leading-tight mb-4 text-center text-white">
                Get Your First Ticket
            </h3>
            <p class="max-w-3xl text-gray-300 text-sm sm:text-base mb-8 leading-relaxed text-center mx-auto">
                Consectetur Adipisicing Eros Ullamcorper Nisl Turpis Id Mi Tristique Purus Nunc Justo Dui Elementum
                Luctus. Ullamcorper Turpis Sit Venenatis Eu Quis Id Tristique In Tristique Urna Non Nec.
            </p>
            <div class="text-center">
                <button
                    class="bg-white text-gray-900 text-xs font-semibold px-6 py-2 rounded-md hover:bg-gray-100 transition"
                    type="button">
                    Get Ticket
                </button>
            </div>
            <img alt="Background silhouettes of people at concert with dark overlay"
                class="absolute inset-0 w-full h-full object-cover opacity-20 pointer-events-none select-none" height="300"
                src="https://storage.googleapis.com/a1aa/image/19541d07-888e-4f94-3539-c0e925b86cee.jpg" style="z-index:0"
                width="1200" />
        </section>
    </main>
@endsection

@push('scripts')
    <script>
    </script>
@endpush