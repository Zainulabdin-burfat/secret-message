<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            @if(session('message'))
                <h1 class="alert alert-{{ session('status') ? 'success' : 'danger' }}">{{ session('message') }}</h1>
            @endif
            <div class="row g-4">
                <!-- Left Form: Send Message -->
                <div class="col-md-6">
                    <div class="card shadow-sm rounded">
                        <div class="card-header bg-primary text-white">
                            {{ __('Send Message') }}
                        </div>
                        @php
                            $users = \App\Models\User::whereNot('id', auth()->user()->id)->get();
                        @endphp
                        <div class="card-body">
                            <form method="POST" action="{{ route('messages.store') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="recipient" class="form-label">{{ __('Recipient') }}</label>
                                    <select class="form-select @error('receiver_id') is-invalid @enderror" id="recipient" name="receiver_id" required>
                                        @forelse($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @empty
                                            <option value="" disabled>No users available</option>
                                        @endforelse
                                    </select>
                                    @error('receiver_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="text" class="form-label">{{ __('Message Text') }}</label>
                                    <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text" rows="3" required></textarea>
                                    @error('text')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="read_once" name="read_once">
                                    <label class="form-check-label" for="read_once">{{ __('Read Once') }}</label>
                                </div>

                                <div class="mb-3">
                                    <label for="expires_at" class="form-label">{{ __('Expiry Date and Time') }}</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at">
                                    @error('expires_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">{{ __('Send Message') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Form: Retrieve Message -->
                <div class="col-md-6">
                    <div class="card shadow-sm rounded">
                        <div class="card-header bg-primary text-white">
                            {{ __('Retrieve Message') }}
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('messages.show') }}">
                                @csrf

                                <!-- Display validation errors -->

                                <div class="mb-3">
                                    <label for="message_id" class="form-label">{{ __('Message ID') }}</label>
                                    <input type="text" class="form-control @error('message_id') is-invalid @enderror" id="message_id" name="message_id" required>
                                    @error('message_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="key" class="form-label">{{ __('Encryption Key') }}</label>
                                    <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" required>
                                    @error('key')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">{{ __('Retrieve Message') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
