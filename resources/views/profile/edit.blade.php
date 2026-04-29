@extends('layouts.panel', ['title' => 'Profile'])

@section('body')
<div class="panel-shell">
    @if (auth()->user()->isAdmin())
        @include('admin.partials.sidebar')
    @else
        @include('user.partials.sidebar')
    @endif

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Profile</h1>
                <p>Update your account information, password and account access settings.</p>
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
        </header>

        <section class="content-area">
            <div class="grid" style="max-width: 760px;">
                <div class="card">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="card">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="card">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
