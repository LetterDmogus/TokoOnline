@extends('app')

@section('title', 'Register')

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100 bg-glass">
        <div class="card shadow-lg p-4 text-white bg-glass-card overflow-hidden" style="width: 450px;">
            <h3 class="text-center mb-4">Create Account</h3>

            <div class="form-wrapper position-relative">
                <form id="registerForm" action="/register" method="POST">
                    @csrf

                    <!-- Step 1: Basic Info -->
                    <div class="step step-1 active">
                        <div class="mb-3">
                            <label for="username" class="form-label text-light">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-light">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-light">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="button" class="btn btn-primary w-100 next-btn">Next</button>
                    </div>

                    <!-- Step 2: Additional Info -->
                    <div class="step step-2">
                        <div class="mb-3">
                            <label for="fn" class="form-label text-light">Full Name</label>
                            <input type="text" class="form-control" id="fn" name="fn">
                        </div>

                        <div class="mb-3">
                            <label for="bd" class="form-label text-light">Birth Date</label>
                            <input type="date" class="form-control" id="bd" name="bd">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label ">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                                <option value="PNS">Prefer not to say</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-secondary w-100 mb-2 back-btn">Back</button>
                        <button type="button" class="btn btn-primary w-100 next-btn">Next</button>
                    </div>

                    <div class="step step-3">
                        <div class="mb-3">
                            <input type="checkbox" class="form-check" name="sel" id="sel">
                            <label for="sel" class="form-label ">I am a seller</label>
                        </div>
                        <div class="mb-3">
                            <label for="ad" class="form-label ">Adress</label>
                            <input type="text" class="form-control" id="ad" name="ad" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label ">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <button type="button" class="btn btn-secondary w-100 mb-2 back-btn">Back</button>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </div>
                </form>
            </div>

            <p class="text-center mt-3 text-light">
                Already have an account? <a href="/login" class="text-info">Login here</a>
            </p>
        </div>
    </div>

    <!-- Slide transition logic -->
    <script>
        const steps = document.querySelectorAll('.step');
        let currentStep = 0;

        function showStep(index, direction) {
            const current = steps[currentStep];
            const next = steps[index];

            // Slide out current
            current.classList.remove('active');
            current.classList.add(direction === 'next' ? 'slide-left' : 'slide-right');

            // Slide in next
            next.classList.add('active', direction === 'next' ? 'slide-in' : 'slide-back');

            // Reset after animation
            steps.forEach(step => {
                step.addEventListener('animationend', () => {
                    step.classList.remove('slide-left', 'slide-right', 'slide-in', 'slide-back');
                });
            });

            currentStep = index;
        }

        document.addEventListener('click', e => {
            if (e.target.classList.contains('next-btn') && currentStep < steps.length - 1) {
                showStep(currentStep + 1, 'next');
            }
            if (e.target.classList.contains('back-btn') && currentStep > 0) {
                showStep(currentStep - 1, 'back');
            }
        });
    </script>

@endsection
