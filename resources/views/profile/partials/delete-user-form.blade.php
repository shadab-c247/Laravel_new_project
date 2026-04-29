<section>
    <header>
        <h2>Delete Account</h2>
        <p class="muted">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="form-stack" style="margin-top: 22px;">
        @csrf
        @method('delete')

        <div>
            <label for="password">Confirm Password</label>
            <input id="password" name="password" type="password" placeholder="Password">
            @foreach ($errors->userDeletion->get('password') as $message)
                <div class="field-error">{{ $message }}</div>
            @endforeach
        </div>

        <div class="actions">
            <button class="btn btn-danger" type="submit">Delete Account</button>
        </div>
    </form>
</section>
