<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-md-4 mb-3">
                <h5>About Us</h5>
                <p class="small">
                    Sistem Kami makes systems for your business.
                    We build ready-made or custom software to help you work better.

                    Simple. Fast. For you.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 mb-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/') }}" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="{{ url('/about') }}" class="text-light text-decoration-none">About</a></li>
                    <li><a href="{{ url('/events') }}" class="text-light text-decoration-none">Events</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-light text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-md-4 mb-3">
                <h5>Contact Us</h5>
                <address class="small">
                    Ipoh<br>
                    Perak, Malaysia<br>
                    Phone: 011-2406-9291<br>
                    Email: <a href="mailto:admin@sistemkami.com" class="text-light">admin@sistemkami.com</a>
                </address>
            </div>
        </div>

        <hr class="bg-secondary" />

        <div class="d-flex justify-content-between align-items-center pt-3">
            <small>&copy; {{ date('Y') }} Sistem Kami. All rights reserved.</small>
            <div>
                <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>