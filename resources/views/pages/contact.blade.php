<x-layouts.app>
    <section class="bg-brand-950 text-white py-16">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">Get in Touch</span>
            <h1 class="display-lg text-white">Contact Cross Rivers <span class="text-lime-400 italic">Ministries.</span></h1>
            <p class="text-lg text-white/80 font-light">
                Whether you have a prayer request, questions about our coaching tracks, or need to connect with Jane Audrey, we are here to walk with you.
            </p>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Contact Information Column -->
                <div class="lg:col-span-5 space-y-8">
                    <div class="space-y-4">
                        <span class="eyebrow">Connect Directly</span>
                        <h2 class="display-sm">We are eager to hear from you.</h2>
                        <p class="text-slate text-sm leading-relaxed">
                            Cross Rivers Ministries is based in the USA, providing Christ-centered coaching, speaking, and spiritual mentoring nationwide.
                        </p>
                    </div>

                    <div class="space-y-5 text-sm">
                        <div class="p-5 bg-paper border border-line rounded-[2px] space-y-1">
                            <span class="text-xs uppercase tracking-wider text-muted font-semibold block">Email Address</span>
                            <a href="mailto:{{ setting('general.contact_email', 'crossriversministriesinfo@gmail.com') }}" class="font-medium text-brand-500 hover:text-green-600 transition-colors">
                                {{ setting('general.contact_email', 'crossriversministriesinfo@gmail.com') }}
                            </a>
                        </div>

                        <div class="p-5 bg-paper border border-line rounded-[2px] space-y-1">
                            <span class="text-xs uppercase tracking-wider text-muted font-semibold block">Coaching Advice Line</span>
                            <a href="{{ setting('general.advice_line_url', 'http://webcall.paypercall.com/00003825') }}" target="_blank" rel="noopener" class="font-medium text-green-600 hover:underline">
                                Start a Telephonic Meeting (WebCall)
                            </a>
                        </div>

                        <div class="p-5 bg-paper border border-line rounded-[2px] space-y-1">
                            <span class="text-xs uppercase tracking-wider text-muted font-semibold block">Location</span>
                            <span class="text-slate">{{ setting('general.location', 'Serving clients across the United States') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Column -->
                <div class="lg:col-span-7 card p-8 md:p-12 bg-paper border border-line">
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" required class="form-input" value="{{ old('first_name') }}">
                                @error('first_name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" required class="form-input" value="{{ old('last_name') }}">
                                @error('last_name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" required class="form-input" value="{{ old('email') }}">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-input" value="{{ old('subject', request('subject')) }}" placeholder="e.g. Spiritual Coaching Inquiry">
                        </div>

                        <!-- Mandated Reminder (Preserved from the live website) -->
                        <div class="p-5 bg-white border border-line rounded-[2px] space-y-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-green-700">
                                The mandated reminder that I am… *
                            </label>
                            <div class="flex flex-wrap gap-6 pt-1 text-sm text-ink">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="reminder" value="Loved" required {{ old('reminder') === 'Loved' ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500">
                                    <span>Loved</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="reminder" value="Special" {{ old('reminder') === 'Special' ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500">
                                    <span>Special</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="reminder" value="Resilient" {{ old('reminder') === 'Resilient' ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500">
                                    <span>Resilient</span>
                                </label>
                            </div>
                            @error('reminder')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">Your Message *</label>
                            <textarea name="message" rows="5" required class="form-input" placeholder="How can we partner with you or pray with you today?">{{ old('message') }}</textarea>
                            @error('message')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-full py-4 text-base">
                            <span>Send Message</span>
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
