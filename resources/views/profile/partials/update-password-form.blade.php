<section>
    <header>
        <h2>Update Password</h2>
        <p class="muted">Use a strong password to keep your account secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="form-stack" style="margin-top: 22px;">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
            @foreach ($errors->updatePassword->get('current_password') as $message)
                <div class="field-error">{{ $message }}</div>
            @endforeach
        </div>

        <div>
            <label for="update_password_password">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password">
            @foreach ($errors->updatePassword->get('password') as $message)
                <div class="field-error">{{ $message }}</div>
            @endforeach
        </div>

        <div>
            <label for="update_password_password_confirmation">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
            @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                <div class="field-error">{{ $message }}</div>
            @endforeach
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Save Password</button>

            @if (session('status') === 'password-updated')
                <span class="success-note">Saved.</span>
            @endif
        </div>
    </form>
</section>
