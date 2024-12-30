@extends('Auth.layout')


@section('content')
    <div class="container">
        <div class="row justify-content-center min-vh-80 align-items-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="text-center mb-0">Đăng nhập</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="Nhập email" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="font-weight-bold">Mật khẩu</label>
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Nhập mật khẩu" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Đăng nhập</button>
                            </div>
                            <div class="text-center mt-4">
                                <p class="mb-0">Chưa có tài khoản?
                                    <a href="{{ route('register') }}" class="text-primary font-weight-bold">Đăng ký ngay</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
