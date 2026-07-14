@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">Contact Us</h1>
                <p class="text-muted mb-4">Have a question, issue, or suggestion? Send us a message and our team will get back to you.</p>

                <form method="POST" action="{{ route('contact.store') }}" id="contactForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone (Optional)</label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="category">Category</label>
                            <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ ucwords(str_replace('_', ' ', $category)) }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="message">Message</label>
                            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="6" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="contactSubmitBtn">
                            <span class="btn-label">Send Message</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('contactForm')?.addEventListener('submit', function () {
        const button = document.getElementById('contactSubmitBtn');
        button?.querySelector('.btn-label')?.classList.add('d-none');
        button?.querySelector('.spinner-border')?.classList.remove('d-none');
        button?.setAttribute('disabled', 'disabled');
    });
</script>
@endsection
