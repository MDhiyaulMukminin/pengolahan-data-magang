<div>
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-4 mb-2 text-white-50">
                        <div>
                            <a href="{{ route('auth.login') }}" class="d-inline-block auth-logo">
                                <img src="assets/images/logo-pa-2.png" alt="" height="100" width="120">
                            </a>
                        </div>
                        <h1 class="text-white mt-3 fw-medium" style="font-size: 20px;">Pengolahan Data Magang (PEDAGANG)</h1>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4 card-bg-fill">
                        <div class="card-body p-4">
                            <div class="p-2 mt-2">
                                <form wire:submit.prevent="login()">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email"
                                            placeholder="Masukkan email anda" wire:model="email">
                                        @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="password-input">Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5 password-input"
                                                placeholder="Masukkan password anda" id="password-input"
                                                wire:model="password">
                                            @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                type="button" id="password-addon"><i
                                                    class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">Masuk</button>
                                    </div>
                                </form>
                                <div class="mt-4 text-center">
                                    <p class="mb-0">Belum punya Akun?
                                        <a href="{{ route('auth.register') }}"
                                           class="fw-semibold text-primary text-decoration-underline">
                                            Daftar
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
</div>