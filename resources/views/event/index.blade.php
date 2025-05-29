<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $event->title ?? 'Event Landing Page' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 2rem;
        }

        .ticket-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 1.5rem;
        }

        iframe {
            border-radius: 8px;
            width: 100%;
            height: 450px;
            border: 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            {{-- Left Column: Description and Map --}}
            <div class="col-lg-12 col-md-12">
                <h2>{{ $event->title }}</h2>
            </div>
        </div>
        {{-- Carousel --}}
        <div id="eventCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($event->images ?? [] as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('images/events/' . $event->id . '/' . $image) }}" class="d-block w-100"
                            alt="Slide {{ $index + 1 }}">
                    </div>
                @endforeach
            </div>
            @if(!empty($event->images) && count($event->images) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>

        {{-- Main Content --}}
        <div class="row">
            {{-- Left Column: Description and Map --}}
            <div class="col-lg-8 col-md-7">
                <h2>{{ $event->category->name }}</h2>
                <p>{!! $event->description !!}</p>

               {{-- Google Map --}}
                @if($event->latitude && $event->longitude)
                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode($event->venue_name) }}%20{{ $event->latitude }},{{ $event->longitude }}&output=embed"
                        width="100%" height="500" frameborder="0" style="border:0" allowfullscreen loading="lazy">
                    </iframe>

                @endif

            </div>

            {{-- Right Column: Ticket Price --}}
            <div class="col-lg-4 col-md-5">
                <div class="ticket-box">
                    <h4>🎟 Ticket Price</h4>
                    <p class="fs-5">
                        {{ $event->ticket_price ? '$' . number_format($event->ticket_price, 2) : 'Free' }}
                    </p>
                    @if($event->buy_link)
                        <a href="{{ $event->buy_link }}" class="btn btn-primary w-100 mt-3">Buy Ticket</a>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div style="height: 200px;">
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>