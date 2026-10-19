<?php

/*
|--------------------------------------------------------------------------
| Cross Rivers Ministries — editable content schema
|--------------------------------------------------------------------------
| Each group is rendered as a form in Admin → Content. Values saved in the
| `settings` table override these defaults. Field types:
| text | textarea | richtext | image | email | url | lines (one item per line)
*/

return [

    'groups' => [

        'general' => [
            'label' => 'Site Settings',
            'description' => 'Organisation details, contact information and social links used across the site.',
            'fields' => [
                'site_name' => ['label' => 'Site name', 'type' => 'text', 'default' => 'Cross Rivers Ministries', 'rules' => 'required|max:120'],
                'site_tagline' => ['label' => 'Tagline', 'type' => 'text', 'default' => 'Helping you cross over'],
                'site_description' => ['label' => 'Default meta description', 'type' => 'textarea', 'default' => 'Cross Rivers Ministries is a Christ-centered coaching ministry helping individuals, families and leaders experience purpose, fulfillment and lasting change through spiritual, family, managerial, vocational and educational coaching.'],
                'contact_email' => ['label' => 'Contact email', 'type' => 'email', 'default' => 'crossriversministriesinfo@gmail.com', 'rules' => 'nullable|email'],
                'contact_phone' => ['label' => 'Phone number', 'type' => 'text', 'default' => ''],
                'advice_line_url' => ['label' => 'Advice line call link', 'type' => 'url', 'default' => 'http://webcall.paypercall.com/00003825', 'rules' => 'nullable|url'],
                'location' => ['label' => 'Location', 'type' => 'text', 'default' => 'Serving clients across the United States'],
                'office_hours' => ['label' => 'Availability', 'type' => 'text', 'default' => 'Mon – Sat · by appointment'],
                'facebook_url' => ['label' => 'Facebook URL', 'type' => 'url', 'default' => 'https://facebook.com', 'rules' => 'nullable|url'],
                'instagram_url' => ['label' => 'Instagram URL', 'type' => 'url', 'default' => 'https://instagram.com', 'rules' => 'nullable|url'],
                'twitter_url' => ['label' => 'X / Twitter URL', 'type' => 'url', 'default' => 'https://twitter.com', 'rules' => 'nullable|url'],
                'youtube_url' => ['label' => 'YouTube URL', 'type' => 'url', 'default' => '', 'rules' => 'nullable|url'],
                'footer_about' => ['label' => 'Footer description', 'type' => 'textarea', 'default' => 'A Christ-centered ministry committed to helping you experience the life of purpose and fulfillment that is your divine right as a child of God — in the here and now.'],
                'og_image' => ['label' => 'Default social share image', 'type' => 'image', 'default' => 'images/site/hero.jpg'],
            ],
        ],

        'home' => [
            'label' => 'Homepage',
            'description' => 'Hero, introduction, vision and call-to-action copy for the homepage.',
            'fields' => [
                'hero_eyebrow' => ['label' => 'Hero eyebrow', 'type' => 'text', 'default' => 'Your personal spiritual coach for a better life'],
                'hero_title' => ['label' => 'Hero headline (wrap a word in *asterisks* for italic accent)', 'type' => 'text', 'default' => 'Every life has rivers. You don’t have to cross them *alone.*'],
                'hero_text' => ['label' => 'Hero supporting statement', 'type' => 'textarea', 'default' => 'Cross Rivers Ministries is a Christ-centered coaching ministry. We walk alongside individuals, families and leaders — through spiritual, family, managerial, vocational and educational coaching — toward a life of purpose, fulfillment and lasting change.'],
                'hero_image' => ['label' => 'Hero image', 'type' => 'image', 'default' => 'images/site/hero.jpg'],
                'hero_scripture' => ['label' => 'Hero scripture', 'type' => 'text', 'default' => 'When you pass through the rivers, they will not overwhelm you.'],
                'hero_scripture_ref' => ['label' => 'Hero scripture reference', 'type' => 'text', 'default' => 'Isaiah 43:2'],

                'intro_title' => ['label' => 'Who We Are — heading', 'type' => 'text', 'default' => 'A ministry built on faith, guided by wisdom, devoted to *you.*'],
                'intro_text' => ['label' => 'Who We Are — text', 'type' => 'textarea', 'default' => 'We are a Christ-centered ministry based in the USA. We are committed to helping you experience the life of purpose and fulfillment that is your divine right as a child of God. That fulfilling life is possible for you in the here and now.'],
                'intro_text_2' => ['label' => 'Who We Are — secondary text', 'type' => 'textarea', 'default' => 'Thank you for your interest in Cross Rivers Ministries. We pray you will see the amazing, life-changing work God is doing here — and that you will find a place where you can be heard, encouraged and equipped.'],
                'intro_image' => ['label' => 'Who We Are — image', 'type' => 'image', 'default' => 'images/site/who-we-are.jpg'],

                'vision' => ['label' => 'Vision statement', 'type' => 'textarea', 'default' => 'To envision a world in which every individual has the tools they need to live a fruitful life.'],
                'mission' => ['label' => 'Mission statement', 'type' => 'textarea', 'default' => 'To coach and equip people through biblical principles — strengthening their relationship with God, rebuilding identity on His Word, and guiding them from purposeless to purposeful.'],
                'promise' => ['label' => 'Promise statement', 'type' => 'textarea', 'default' => 'We want to partner with you, as you navigate life, to help you cross life’s rivers. This will allow you to live God’s best life.'],

                'rivers_title' => ['label' => 'Cross Life’s Rivers — heading', 'type' => 'text', 'default' => 'Cross life’s *rivers.*'],
                'rivers_text' => ['label' => 'Cross Life’s Rivers — text', 'type' => 'textarea', 'default' => 'As we traverse through life, each of us will face rivers that must be crossed. Some are wide, some are deep, and some arrive without warning. You were never meant to stand on the bank alone.'],
                'rivers_list' => ['label' => 'Rivers (one per line, "Title | description")', 'type' => 'lines', 'default' => "Relationships | Marriage, family and friendships under strain\nFinances | Uncertainty, debt and the pressure to provide\nEmotions | Stress, anxiety, grief and loneliness\nWork & Life | Careers, calling and the search for balance"],
                'rivers_image' => ['label' => 'Cross Life’s Rivers — background image', 'type' => 'image', 'default' => 'images/site/rivers-wide.jpg'],

                'cta_title' => ['label' => 'Final CTA heading', 'type' => 'text', 'default' => 'Your crossing starts with a *conversation.*'],
                'cta_text' => ['label' => 'Final CTA text', 'type' => 'textarea', 'default' => 'Our coaches eagerly await your call. Whatever river you are facing, take the first step today — we will walk with you every step of the way.'],
            ],
        ],

        'about' => [
            'label' => 'About Us',
            'description' => 'Story, mission, values and promise shown on the About page.',
            'fields' => [
                'hero_title' => ['label' => 'Hero headline', 'type' => 'text', 'default' => 'We exist to help you live *God’s best life.*'],
                'hero_text' => ['label' => 'Hero text', 'type' => 'textarea', 'default' => 'Cross Rivers Ministries is a Christ-centered ministry offering the spiritually, physically and emotionally broken a place where they can connect, be heard and be restored.'],
                'hero_image' => ['label' => 'Hero image', 'type' => 'image', 'default' => 'images/site/about-hero.jpg'],
                'who_title' => ['label' => 'Who We Are — heading', 'type' => 'text', 'default' => 'There is a need, now more than ever, for guidance rooted in truth.'],
                'who_text' => ['label' => 'Who We Are — text', 'type' => 'richtext', 'default' => '<p>We exist because we believe that there is a need, now more than ever, to help coach you through a myriad of life challenges. We will help show you how to live God’s best life.</p><p>How would you like to enjoy healthy living, loving relationships, work-life balance, refreshing fellowship, financial abundance and much more on a day-to-day basis? The secrets to attaining these are revealed in the Word of God, the Bible. Cross Rivers Ministries is here to guide you on the journey to discovering these time-tested principles — and we will encourage you every step of the way.</p>'],
                'story_title' => ['label' => 'Our Story — heading', 'type' => 'text', 'default' => 'From a nurse’s calling to a ministry of restoration.'],
                'story_text' => ['label' => 'Our Story — text', 'type' => 'richtext', 'default' => '<p>Cross Rivers Ministries grew out of a lifetime of quiet, faithful service. Our founder, life coach Jane Audrey, spent years as a professional nurse and clinical educator — meeting people where they stood and providing what they needed, often beyond what they could put into words.</p><p>Colleagues, friends and strangers alike found in her a listening ear, a prayer partner and a trusted confidante. Those conversations became a calling: to build a ministry where anyone facing life’s rivers could find biblical guidance, practical coaching and genuine care.</p><p>Today, CRM serves individuals, families, churches, schools and organisations — but the heart remains the same: every person is highly valuable, fearfully and wonderfully made (Psalm 139:14).</p>'],
                'story_image' => ['label' => 'Our Story — image', 'type' => 'image', 'default' => 'images/site/spiritual.jpg'],
                'mission' => ['label' => 'Mission', 'type' => 'textarea', 'default' => 'Restoration is our ultimate outcome — the journey you take from being purposeless to being purposeful. We first concentrate on your relationship with God, then rebuild your identity on His Word, enabling lifelong growth and continuous transformation.'],
                'vision' => ['label' => 'Vision', 'type' => 'textarea', 'default' => 'To envision a world in which every individual has the tools they need to live a fruitful life.'],
                'promise' => ['label' => 'Promise', 'type' => 'textarea', 'default' => 'We want to partner with you, as you navigate life, to help you cross life’s rivers. This will allow you to live God’s best life.'],
                'values' => ['label' => 'Values (one per line, "Title | description")', 'type' => 'lines', 'default' => "Christ-Centered | Every conversation is anchored in the truth and love of Jesus Christ.\nEvery Person Is Valuable | We meet each person with dignity — fearfully and wonderfully made.\nRestoration | We pursue wholeness: spiritually, relationally, emotionally and vocationally.\nWalking Alongside | We don’t direct from a distance; we journey with you, step by step.\nPractical Wisdom | Time-tested biblical principles applied to real, everyday decisions.\nConfidential Care | A safe, judgement-free space to be honest and be heard."],
                'why' => ['label' => 'Why CRM (one per line, "Title | description")', 'type' => 'lines', 'default' => "Biblically grounded | Guidance drawn from Scripture — not trends or guesswork.\nWhole-life coaching | Five disciplines that address the whole person, not one symptom.\nPersonal & tailored | Advice and mentoring shaped to your unique circumstances.\nProven compassion | Decades of caring for people as a nurse, educator and coach."],
                'scripture' => ['label' => 'Featured scripture', 'type' => 'textarea', 'default' => 'For I know the plans I have for you, declares the Lord, plans to prosper you and not to harm you, plans to give you hope and a future.'],
                'scripture_ref' => ['label' => 'Featured scripture reference', 'type' => 'text', 'default' => 'Jeremiah 29:11'],
            ],
        ],

        'advice_line' => [
            'label' => 'Coaching Advice Line',
            'description' => 'Content for the Coaching Advice Line page.',
            'fields' => [
                'hero_title' => ['label' => 'Hero headline', 'type' => 'text', 'default' => 'Someone is ready to *listen.*'],
                'hero_text' => ['label' => 'Hero text', 'type' => 'textarea', 'default' => 'The Coaching Advice Line connects you by phone with an experienced Christian coach who will listen attentively to your situation and offer personalised advice and mentoring tailored to your unique circumstances.'],
                'hero_image' => ['label' => 'Hero image', 'type' => 'image', 'default' => 'images/site/sunrise-hands.jpg'],
                'what_text' => ['label' => 'What the advice line is', 'type' => 'richtext', 'default' => '<p>Your current life is the sum total of your thoughts and attitudes. As the Bible says, “As a man thinketh in his heart, so is he” (Proverbs 23:7).</p><p>Our coaches will help you turn around negative, self-limiting thought patterns through the power of the Holy Spirit. Through their spiritual guidance and your faith, God will set you free — and when the Lord sets you free, you are free indeed (John 8:36).</p>'],
                'who_list' => ['label' => 'Who can use it (one per line)', 'type' => 'lines', 'default' => "Anyone facing stress, anxiety, depression or uncertainty\nIndividuals navigating relationship or family challenges\nProfessionals seeking clarity on work-life balance or calling\nParents, students and young adults at a crossroads\nAnyone who feels lost, lonely or left behind\nBelievers wanting to grow deeper in their walk with God"],
                'expect_list' => ['label' => 'What to expect (one per line, "Title | description")', 'type' => 'lines', 'default' => "Attentive listening | Describe your situation in your own words, at your own pace.\nPersonalised guidance | Advice and mentoring tailored to your unique circumstances.\nBiblical decision-making | Learn to base decisions on sound, Scripture-based principles.\nGuarding your mind | Practical ways to protect what you read, watch and hear.\nStories of overcoming | Hear how others faced similar rivers — and crossed them."],
                'steps' => ['label' => 'How it works (one per line, "Title | description")', 'type' => 'lines', 'default' => "Place your call | Tap “Call now” to start a secure telephonic meeting.\nShare your story | Your coach listens without judgement.\nReceive guidance | Practical, prayerful, biblically grounded next steps.\nKeep walking | Book follow-ups or explore one of our coaching programmes."],
            ],
        ],
    ],

    // Coaching process shown on the homepage & coaching page
    'process' => [
        ['title' => 'Connect', 'text' => 'Reach out by phone, form or email. We listen first — no agenda, no judgement.'],
        ['title' => 'Understand', 'text' => 'Together we explore the river in front of you: its history, its depth and what is at stake.'],
        ['title' => 'Guide', 'text' => 'We apply time-tested biblical principles and practical coaching to chart your crossing.'],
        ['title' => 'Transform', 'text' => 'You step into restored identity and purpose — equipped for the rivers still to come.'],
    ],

    // Primary navigation
    'nav' => [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Blog', 'route' => 'blog.index'],
        ['label' => 'Testimonials', 'route' => 'testimonials'],
        ['label' => 'Store', 'route' => 'store.index'],
        ['label' => 'Contact', 'route' => 'contact'],
    ],
];
