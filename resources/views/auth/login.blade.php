<x-app-layout>
    <style>
        /* CSS Khusus agar halaman Login bersih dan fokus */
        .login-wrapper {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.2);
            background: white;
        }
        .btn-login {
            padding: 0.75rem 1rem;
            font-weight: bold;
            border-radius: 0.5rem;
        }
        .input-group-text {
            background-color: #f8f9fc;
        }
    </style>

    <div class="login-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4">
                    <div class="card login-card p-4">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">SAPRAS</h3>
                            <p class="text-muted small">Aplikasi Pengaduan Sarana</p>
                        </div>

                        {{-- Menampilkan Error Validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger py-2 mb-3 shadow-sm border-0">
                                <ul class="mb-0 small ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <!-- Login Input (NIS atau Username) -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold">NIS / Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person text-primary"></i></span>
                                    <input type="text" 
                                           name="username" 
                                           class="form-control" 
                                           placeholder="Masukkan NIS atau Username" 
                                           value="{{ old('username') }}"
                                           required 
                                           autofocus>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock text-primary"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm uppercase tracking-wide">
                                MASUK
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <small class="text-muted">&copy; 2026 SAPRAS System</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>