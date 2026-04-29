<section>
    <header>
        <h2>Profile Information</h2>
        <p class="muted">Update your account profile information and email address.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="form-stack" style="margin-top: 22px;">
        @csrf
        @method('patch')

        <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert" style="margin-top: 14px; margin-bottom: 0;">
                    Your email address is unverified.
                    <button form="send-verification" class="btn btn-secondary" style="margin-top: 10px;" type="submit">
                        Re-send verification email
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="success-note" style="margin-top: 10px;">A new verification link has been sent to your email address.</p>
                @endif
            @endif
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Save Profile</button>

            @if (session('status') === 'profile-updated')
                <span class="success-note">Saved.</span>
            @endif
        </div>
    </form>
</section>
