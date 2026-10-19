<x-layouts.app>
    <section class="bg-brand-950 text-white py-20">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">Corporate & Ministry Engagements</span>
            <h1 class="display-lg text-white">Consulting & <span class="text-lime-400 italic">Speaking.</span></h1>
            <p class="text-lg text-white/80 font-light">
                Empowering corporations, non-profits, schools, and churches through biblically anchored keynote speaking, organizational leadership consulting, and team workshops.
            </p>
        </div>
    </section>

    <!-- Keynote Topics -->
    <section class="section bg-white">
        <div class="container-x">
            <div class="max-w-2xl mb-14 space-y-3">
                <span class="eyebrow">Signature Speaking</span>
                <h2 class="display-md">Keynote & Seminar Topics</h2>
                <p class="text-slate">Jane Audrey brings profound clinical, relational, and spiritual insights to your conference, retreat, or church gathering.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($speaking as $item)
                    <div class="card p-8 bg-paper flex flex-col justify-between reveal">
                        <div class="space-y-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-green-600">Keynote</span>
                            <h3 class="font-display text-2xl font-normal text-ink">{{ $item->title }}</h3>
                            <p class="text-xs text-slate leading-relaxed">{{ $item->excerpt }}</p>
                            @if(!empty($item->points))
                                <ul class="space-y-1.5 text-xs text-slate pt-2 border-t border-line/60">
                                    @foreach($item->points as $pt)
                                        <li class="flex items-center gap-2">
                                            <span class="size-1 rounded-full bg-green-500"></span>
                                            <span>{{ $pt }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Workshops & Consulting -->
    <section class="section bg-surface border-t border-line">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <span class="eyebrow">Interactive Seminars</span>
                    <h2 class="display-sm">Team Building & Leadership Workshops</h2>
                    <p class="text-slate leading-relaxed">
                        We help companies, summer camps, youth ministries, and schools unlock collaboration using biblical team building, gift assessments, and conflict resolution activities.
                    </p>
                    @foreach($workshops as $w)
                        <div class="p-6 bg-white border border-line rounded-[2px] space-y-3">
                            <h3 class="font-display text-xl font-normal text-ink">{{ $w->title }}</h3>
                            <p class="text-xs text-slate">{{ $w->excerpt }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-6">
                    <span class="eyebrow">Strategic Consulting</span>
                    <h2 class="display-sm">Organizational Development</h2>
                    <p class="text-slate leading-relaxed">
                        Helping Christian leadership teams evaluate internal dynamics, align organizational culture with eternal values, and develop resilient leaders.
                    </p>
                    @foreach($consulting as $c)
                        <div class="p-6 bg-white border border-line rounded-[2px] space-y-3">
                            <h3 class="font-display text-xl font-normal text-ink">{{ $c->title }}</h3>
                            <p class="text-xs text-slate">{{ $c->excerpt }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Corporate Inquiry Form -->
    <section class="section bg-white border-t border-line">
        <div class="container-narrow">
            <div class="text-center max-w-xl mx-auto mb-12 space-y-3">
                <span class="eyebrow mx-auto justify-center">Request an Engagement</span>
                <h2 class="display-md">Book a Consultation or Speaking Event</h2>
                <p class="text-slate text-sm">Submit your event details below to inquire about dates, speaking topics, and consulting availability.</p>
            </div>

            <form action="{{ route('consulting.store') }}" method="POST" class="card p-8 md:p-12 space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Your Name *</label>
                        <input type="text" name="name" required class="form-input" value="{{ old('name') }}">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" required class="form-input" value="{{ old('email') }}">
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <label class="form-label">Organization / Church Name</label>
                        <input type="text" name="organization" class="form-input" value="{{ old('organization') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Event Type *</label>
                        <select name="event_type" required class="form-input">
                            <option value="Keynote Speaking">Keynote Speaking</option>
                            <option value="Corporate Workshop">Corporate / Team Workshop</option>
                            <option value="Church Conference / Retreat">Church Conference / Retreat</option>
                            <option value="Leadership Consulting">Leadership Consulting</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Estimated Date / Season</label>
                        <input type="text" name="estimated_date" placeholder="e.g. November 2026" class="form-input" value="{{ old('estimated_date') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Event Details & Specific Needs *</label>
                    <textarea name="message" rows="5" required class="form-input" placeholder="Tell us about your audience, event goals, and timeline...">{{ old('message') }}</textarea>
                    @error('message')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn-primary w-full py-4">
                    <span>Submit Consultation Request</span>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>
        </div>
    </section>
</x-layouts.app>
