<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\CoachingService;
use App\Models\ConsultingService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.org'],
            [
                'name' => 'Jane Audrey',
                'title' => 'Founder & Lead Spiritual Life Coach',
                'bio' => 'Jane Audrey is a seasoned life coach, former clinical nurse educator, and founder of Cross Rivers Ministries. She has dedicated her life to helping individuals, families, and organizations experience God\'s restoration and purpose in the here and now.',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'role' => 'super_admin',
                'two_factor_enabled' => true,
                'avatar' => 'images/site/who-we-are.jpg',
            ]
        );
        $admin->update([
            'role' => 'super_admin',
            'two_factor_enabled' => true,
        ]);

        // 2. Settings initial population from config/crm.php
        foreach (config('crm.groups', []) as $groupKey => $group) {
            foreach ($group['fields'] ?? [] as $fieldKey => $field) {
                Setting::firstOrCreate(
                    ['key' => "{$groupKey}.{$fieldKey}"],
                    [
                        'group' => $groupKey,
                        'value' => $field['default'] ?? '',
                    ]
                );
            }
        }

        // 3. Coaches
        $coaches = [
            [
                'name' => 'Jane Audrey',
                'role' => 'Founder & Lead Spiritual Life Coach',
                'bio' => 'Jane holds years of experience in personal, spiritual, and clinical life coaching. She brings an empathetic listening ear, deep biblical discernment, and an unwavering commitment to seeing people cross life\'s most challenging rivers.',
                'photo' => 'images/site/who-we-are.jpg',
                'email' => 'crossriversministriesinfo@gmail.com',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Pastor David Mensah',
                'role' => 'Family & Marriage Coach',
                'bio' => 'David specializes in biblical family dynamics, parental guidance, and relationship reconciliation with over 15 years in pastoral counseling.',
                'photo' => 'images/site/managerial.jpg',
                'email' => 'david@crossriversministries.org',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Jenkins, M.Ed',
                'role' => 'Educational & Vocational Coach',
                'bio' => 'Sarah has guided hundreds of students and young professionals into purposeful academic and career trajectories rooted in God-given talents.',
                'photo' => 'images/site/vocational.jpg',
                'email' => 'sarah@crossriversministries.org',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];
        foreach ($coaches as $c) {
            Coach::firstOrCreate(['name' => $c['name']], $c);
        }

        // 4. Coaching Services (The 5 Core Disciplines from the original site)
        $services = [
            [
                'title' => 'Spiritual Coaching',
                'slug' => 'spiritual-coaching',
                'icon' => 'cross',
                'tagline' => 'Strengthening your relationship with God in the here and now',
                'excerpt' => 'Concentrating on your direct relationship with God, establishing a vibrant prayer life, and renewing your mind through Scripture.',
                'description' => '<p>At Cross Rivers Ministries, spiritual coaching is the cornerstone of all transformation. First and foremost, we concentrate on your relationship with God with a goal of strengthening or re-establishing that essential bond.</p><p>We focus on rebuilding your personal identity directly upon the unshakeable Word of God. Rather than relying on temporary positive thinking, our spiritual coaching uncovers biblical truths that break long-standing negative patterns, hastens spiritual growth, and helps you hear God\'s voice clearly amidst life\'s noise.</p>',
                'benefits' => [
                    'Overcome spiritual dryness and re-ignite a passionate prayer life',
                    'Learn sound biblical principles for daily decision-making',
                    'Guard your mind against negative and self-limiting thought patterns',
                    'Experience the true freedom and restoration promised in John 8:36',
                    'Navigate spiritual warfare and doubt with mature, trusted counsel',
                ],
                'audience' => [
                    'Believers feeling distant from God or struggling through a dry season',
                    'Anyone carrying deep guilt, shame, or fear who desires grace and freedom',
                    'New Christians looking for strong biblical discipleship and mentoring',
                    'Those facing spiritual questions, crossroads, or crises of faith',
                ],
                'image' => 'images/site/spiritual.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Family Coaching',
                'slug' => 'family-coaching',
                'icon' => 'users',
                'tagline' => 'Restoring God\'s original design for marriage, parents, and children',
                'excerpt' => 'Navigating marital strain, parenting hurdles, and generational reconciliation through God\'s design for family structure.',
                'description' => '<p>Families were created by God and are therefore very close to His heart because family was His original design from the very beginning. Unlike our friends, we don\'t choose our biological family—God Himself chooses the family we are birthed into so that His purpose and glory can be manifested through us.</p><p>When family dynamics break down, the ripple effects can feel overwhelming. Our family coaching helps you re-evaluate marriage, parenting, and sibling bonds from God\'s perspective, repairing broken trust and establishing peaceful, loving communication.</p>',
                'benefits' => [
                    'Discover and practice God\'s authentic purpose for marriage',
                    'Clarity on parental roles in raising children under God\'s nurture and admonition',
                    'Conflict resolution strategies tailored for blended and extended families',
                    'Healing emotional estrangements between siblings and adult children',
                    'Establish healthy family rhythms, devotions, and mutual respect',
                ],
                'audience' => [
                    'Couples experiencing communication breakdown, drift, or conflict',
                    'Parents feeling exhausted or unsure how to guide children and teens',
                    'Families navigating major transitions, grief, or blended family dynamics',
                    'Individuals seeking to heal past family trauma and build healthy boundaries',
                ],
                'image' => 'images/site/about-hero.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Managerial Coaching',
                'slug' => 'managerial-coaching',
                'icon' => 'briefcase',
                'tagline' => 'Christ-centered leadership, integrity, and organizational wisdom',
                'excerpt' => 'Equipping managers, supervisors, and ministry directors to lead with servant hearts, high competence, and ethical clarity.',
                'description' => '<p>Leading people is a stewardship of eternal significance. Cross Rivers Ministries provides managerial coaching designed for executives, business managers, non-profit directors, and church leaders who desire to lead effectively without compromising Christian virtues.</p><p>We examine what Scripture reveals about leadership, authority, team dynamics, and conflict management in demanding workplace environments.</p>',
                'benefits' => [
                    'Biblically grounded leadership styles that inspire loyalty and high performance',
                    'Mastering difficult personnel conversations with both grace and truth',
                    'Managing pressure, executive burnout, and work-related stress',
                    'Promoting a collegial, healthy work environment built on mutual respect',
                    'Balancing corporate accountability with authentic pastoral care for team members',
                ],
                'audience' => [
                    'Corporate supervisors, department heads, and C-suite leaders',
                    'Pastors, ministry staff, and church committee leaders',
                    'Small business owners managing employees and operational growth',
                    'Newly promoted managers navigating the transition to leadership',
                ],
                'image' => 'images/site/managerial.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Vocational Coaching',
                'slug' => 'vocational-coaching',
                'icon' => 'compass',
                'tagline' => 'Discovering and walking in your God-given calling and life assignment',
                'excerpt' => 'Unlocking life’s greatest question: determining what God created you to do, and charting a practical path to fulfill it.',
                'description' => '<p>Determining what God has created you to do is essentially life\'s greatest question. You will not experience lasting peace until you are walking in God\'s ordained purpose for your life.</p><p>Our vocational coaching guides you through a structured assessment of your spiritual gifts, natural passions, life experiences, and vocational opportunities. We provide practical tools to pivot careers, launch callings, or bring divine intentionality to your daily work.</p>',
                'benefits' => [
                    'Identify and articulate your unique life purpose and spiritual assignments',
                    'Assess your natural strengths, spiritual gifts, and vocational passions',
                    'Actionable roadmap for career transitions or entrepreneurial ventures',
                    'Overcome the fear of failure and step out in confident faith',
                    'Harmonize your professional endeavors with family and kingdom priorities',
                ],
                'audience' => [
                    'Individuals feeling unfulfilled or stuck in meaningless jobs',
                    'Career changers and retirees asking "what is next for my life?"',
                    'College grads seeking their initial vocational direction',
                    'Aspiring ministry workers, authors, and mission-driven entrepreneurs',
                ],
                'image' => 'images/site/vocational.jpg',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Educational Coaching',
                'slug' => 'educational-coaching',
                'icon' => 'academic-cap',
                'tagline' => 'Empowering students and lifelong learners to excel with purpose',
                'excerpt' => 'Nurturing study habits, intellectual curiosity, moral discipline, and academic resilience from middle school to graduate studies.',
                'description' => '<p>Every individual is fearfully and wonderfully made with incredible intellectual potential. In a chaotic and distracted culture, students need both technical learning habits and emotional/spiritual anchoring.</p><p>Our educational coaching coaches students, adult learners, and homeschooling families to develop rigorous study practices, manage academic anxiety, and view learning as a joyful pursuit of God\'s truth.</p>',
                'benefits' => [
                    'Proven time-management, note-taking, and active study methodologies',
                    'Overcoming academic procrastination, test anxiety, and self-doubt',
                    'Goal setting and accountability for high school, college, and graduate degrees',
                    'Biblical worldview integration across humanities, sciences, and ethics',
                    'Mentoring for youth navigating peer pressure and campus culture',
                ],
                'audience' => [
                    'High school students preparing for college admission and independent living',
                    'Undergraduate and graduate students navigating demanding workloads',
                    'Adult learners returning to school or pursuing certifications',
                    'Parents seeking academic coaching support for their children',
                ],
                'image' => 'images/site/educational.jpg',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];
        foreach ($services as $s) {
            CoachingService::firstOrCreate(['slug' => $s['slug']], $s);
        }

        // 5. Consulting Services & Speaking Engagements (from original site)
        $consulting = [
            [
                'type' => 'speaking',
                'title' => 'Understanding Family Dynamics',
                'slug' => 'understanding-family-dynamics',
                'icon' => 'home',
                'excerpt' => 'God\'s original design for marriage, parents, children, and siblings, and how to restore structure when family feels broken.',
                'description' => '<p>Families were created by God and is therefore very close to His heart because it was His original design from the beginning. It is said that, unlike our friends, we don\'t get to choose our family. That\'s because God Himself chooses the family who we are birthed into for His purpose and glory to be manifested through us.</p><p>Key themes explored in this keynote/seminar:</p><ul><li>What is God\'s purpose for marriage?</li><li>What is our role as parents?</li><li>What is our role as children?</li><li>What is our role as siblings?</li></ul>',
                'points' => [
                    'Reviewing family relationships from God’s perspective',
                    'The roots of modern family disintegration and biblical remedies',
                    'Practical restoration steps for fractured households',
                ],
                'image' => 'images/site/speaking.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'type' => 'speaking',
                'title' => 'Finding Your Life’s Purpose',
                'slug' => 'finding-your-lifes-purpose',
                'icon' => 'sparkles',
                'excerpt' => 'Determining what God has created you to do—essentially life’s greatest question. You will not be at peace until you are in purpose.',
                'description' => '<p>We are a Christ-centered ministry committed to helping you experience the life of purpose and fulfillment that is your divine right as a child of God. Leading a fulfilling life is possible for you in the here and now because God has created each of us with a specific purpose in mind.</p><p>Determining what God has created you to do is essentially life’s greatest question. You will not be at peace until you are walking in God’s purpose for your life.</p>',
                'points' => [
                    'Are you doing what God has created you to do?',
                    'The secrets of life fulfillment revealed in Scripture',
                    'Practical steps to determine your divine assignment',
                ],
                'image' => 'images/site/hero.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'type' => 'speaking',
                'title' => 'Managing Work-Life Balance',
                'slug' => 'managing-work-life-balance',
                'icon' => 'scale',
                'excerpt' => 'Life is not a dress rehearsal. We explore what you must do to protect loving relationships, set work hours, and enjoy daily peace.',
                'description' => '<p>Achieving a good work-life balance is critical to our overall individual and family happiness. We must remember that life is not always a dress rehearsal and, therefore, we may only get one opportunity to get some things right.</p><p>Finding the right balance between time spent in our careers and family time is integral. We need to have set work hours and stick to them so that we can be available for our spouses and children under the nurture and admonition of God.</p>',
                'points' => [
                    'What must you do to enjoy healthy living and loving relationships?',
                    'Are your relationships being threatened by work-life challenges?',
                    'What concrete changes will you need to embrace today?',
                ],
                'image' => 'images/site/journal.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'type' => 'workshop',
                'title' => 'Biblical Team Building',
                'slug' => 'biblical-team-building',
                'icon' => 'user-group',
                'excerpt' => 'We help companies, youth groups, schools, and ministries unlock shared goals through biblical activities, games, and leadership principles.',
                'description' => '<p>We can accomplish more when we work together as a team with each person using their specific gifts to achieve a shared goal. There is an old saying: "many hands make work light". In order for a team to work effectively, each person on the team should be respectful and receptive to each other\'s perspectives.</p>',
                'points' => [
                    'Better assess and leverage each person’s specific gifts',
                    'Provide biblical team-building activities and interactive workshops',
                    'Promote a collegial, uplifting environment and improve communication',
                    'Enhance leadership skills and collaboration across team tiers',
                    'Review what the Bible explicitly teaches about leadership',
                ],
                'image' => 'images/site/managerial.jpg',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'type' => 'consulting',
                'title' => 'Organizational & Leadership Consulting',
                'slug' => 'organizational-leadership-consulting',
                'icon' => 'building-office',
                'excerpt' => 'Strategic organizational consulting for churches, non-profits, and Christian businesses seeking cultural renewal and structural clarity.',
                'description' => '<p>Cross Rivers Ministries partners with executive boards, pastoral teams, and business owners to evaluate organizational health, resolve internal leadership friction, and align day-to-day operations with eternal vision.</p>',
                'points' => [
                    'Leadership audit and organizational culture diagnostic',
                    'Executive coaching for senior leaders and pastoral staff',
                    'Policy and governance alignment rooted in integrity and care',
                    'Transition planning and leadership succession mentoring',
                ],
                'image' => 'images/site/speaking.jpg',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];
        foreach ($consulting as $cs) {
            ConsultingService::firstOrCreate(['slug' => $cs['slug']], $cs);
        }

        // 6. Testimonials (Exact real client testimonials preserved from the original website)
        $testimonials = [
            [
                'name' => 'K. R.',
                'location' => 'Maryland',
                'category' => 'Spiritual & Life Purpose',
                'headline' => 'Talking to Ms. Jane is like talking to a friend — multiple breakthroughs and clarity on life purpose',
                'content' => 'During my conversation with Ms. Jane, I knew I wanted several answers and guidance for different areas of life. The conversation was very organic and along the way, I had multiple breakthroughs about my family dynamics, my being and actions in relationships (many caused by fears stemmed from my childhood), and gained clarity about my life purpose. With each breakthrough came a lot of emotion... at many points I was shocked because I never expected to have certain realizations, and I also feel that God was giving me certain confirmations that I have been searching for. Talking to Ms. Jane is like talking to a friend. She is a great listener, you don’t need to have a particular agenda, and I felt completely comfortable being vulnerable with her. Whatever it is that you are dealing with, I am sure that Ms. Jane will be a tremendous help and give you clarity on the things that matter most to you.',
                'photo' => null,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'P. K.',
                'location' => 'Maryland',
                'category' => 'Emotional & Personal Support',
                'headline' => 'Jane sensed my deep sadness — she would stop whatever she was doing to pray with me',
                'content' => 'I believe that God places people in our lives for a designated purpose. Often that purpose is not fully understood until much time has passed and we have time to reflect on a person’s impact in our lives. Jane entered my life as a work colleague at a hospital in which we both worked as nurses. During the first few years, we worked together developing education initiatives for staff nurses. I always admired Jane for her high level of intellect, patience, kindness, and creativity. Jane seemed to have a special sense that allowed her to see beyond the here and now. Little did I know that while Jane was working with me, she was also perceiving in me what I really needed—not from a professional standpoint, but from a much deeper personal view. In the work environment, I never discussed my personal outside of work concerns, but Jane sensed my deep sadness. That moment in time was the beginning for me. As I began the difficult and very painful journey to move from turmoil and despair to hope, Jane was always there in faith with me. Her continual reminder was that no matter how difficult it was in the moment, God was there with me, walking with me, protecting me.',
                'photo' => null,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'S. P.',
                'location' => 'Virginia',
                'category' => 'Faith & Trust',
                'headline' => 'Jane taught me the value of trusting when I don’t see anything happening',
                'content' => 'I love and appreciate Jane’s practical use of everyday events in analogies referencing the Word of God. Jane has been instrumental in teaching me the value of trusting when I don’t see anything happening. I’ve watched Jane face what looks like the brink of collapsing, but yet she holds on to God’s unchanging hand. Her faith is living, steadfast, and contagious.',
                'photo' => null,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'I. C.',
                'location' => 'Virginia',
                'category' => 'Spiritual Mentorship',
                'headline' => 'My Naomi — a trusted confidante, prayer partner, and listening ear',
                'content' => 'I believe that my connection to Jane was divinely orchestrated and I am most grateful to God for that. You see, both Jane and I grew up in Liberia which is located on the West Coast of Africa. However, I only knew her in passing then. It was not until I met her at church that our relationship began to grow. I have never met someone who is so extremely devoted to the things of God. I refer to her as my Naomi because of her steadfast loyalty, counsel, and encouragement in my life. Jane has been a trusted confidante, a prayer partner, and a listening ear, and for that I am truly blessed. To God be the glory for the victory we experience as a result of faithful obedience.',
                'photo' => null,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 4,
            ],
        ];
        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(['name' => $t['name'], 'location' => $t['location']], $t);
        }

        // 7. Store Products & Categories (From real products on the original site)
        $catTees = ProductCategory::firstOrCreate(['name' => 'Thought Provoking Tees'], ['slug' => 'thought-provoking-tees']);
        $catMugs = ProductCategory::firstOrCreate(['name' => 'Care-Free Mugs'], ['slug' => 'care-free-mugs']);
        $catBooks = ProductCategory::firstOrCreate(['name' => 'Books & Guides'], ['slug' => 'books-and-guides']);

        $products = [
            [
                'product_category_id' => $catTees->id,
                'name' => 'Authentic Thought-Provoking Tee',
                'slug' => 'authentic-thought-provoking-tee',
                'excerpt' => 'Premium cotton tee carrying the message of walking in God-given authenticity and freedom.',
                'description' => '<p>Crafted from 100% ring-spun combed cotton for a tailored, comfortable fit. The "Authentic" tee serves as a wearable testimony that you are fearfully and wonderfully made by God.</p><ul><li>100% ring-spun cotton</li><li>Soft vintage screenprint</li><li>Unisex retail fit</li><li>Ethically produced</li></ul>',
                'price' => 23.00,
                'compare_at_price' => 28.00,
                'stock' => 45,
                'image' => 'images/site/journal.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'product_category_id' => $catTees->id,
                'name' => 'Faith Is Thought-Provoking Tee',
                'slug' => 'faith-is-thought-provoking-tee',
                'excerpt' => 'A bold reminder of Hebrews 11:1—the substance of things hoped for, the evidence of things not seen.',
                'description' => '<p>Walk boldly into any room. The "Faith Is" tee communicates your confidence in God\'s promises even when current circumstances look uncertain.</p>',
                'price' => 23.00,
                'compare_at_price' => null,
                'stock' => 60,
                'image' => 'images/site/sunrise-hands.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'product_category_id' => $catTees->id,
                'name' => 'A Warrior Thought-Provoking Tee',
                'slug' => 'a-warrior-thought-provoking-tee',
                'excerpt' => 'Rooted in 1 Corinthians 15:57—thanks be to God who gives us the victory through Jesus Christ.',
                'description' => '<p>You are equipped for the battle. This durable, premium weight tee is designed to remind you that your strength comes directly from the Lord.</p>',
                'price' => 23.00,
                'compare_at_price' => null,
                'stock' => 30,
                'image' => 'images/site/hero.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'product_category_id' => $catTees->id,
                'name' => 'God’s Got My Back Tee',
                'slug' => 'gods-got-my-back-tee',
                'excerpt' => 'Comfortable daily wear affirming Isaiah 43:2—when you pass through rivers, they will not overwhelm you.',
                'description' => '<p>A ministry favorite. Features our signature Cross Rivers comfort cut with a clean typographic layout on the chest.</p>',
                'price' => 23.00,
                'compare_at_price' => 28.00,
                'stock' => 50,
                'image' => 'images/site/rivers-wide.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'product_category_id' => $catMugs->id,
                'name' => 'Good Morning Wisdom Ceramic Mug',
                'slug' => 'good-morning-wisdom-ceramic-mug',
                'excerpt' => '15oz ceramic mug for your morning devotional, tea, or coffee. Microwave and dishwasher safe.',
                'description' => '<p>Start your day centered on God\'s Word. Heavyweight ceramic with glossy finish and durable sublimated lettering that will never fade.</p>',
                'price' => 18.00,
                'compare_at_price' => null,
                'stock' => 75,
                'image' => 'images/site/journal.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'product_category_id' => $catMugs->id,
                'name' => 'Grace for DisGrace Ceramic Mug',
                'slug' => 'grace-for-disgrace-ceramic-mug',
                'excerpt' => 'A daily reminder that God’s grace is sufficient and his power is made perfect in weakness.',
                'description' => '<p>Featuring 2 Corinthians 12:9 inspiration in elegant script. Perfect gift for friends or colleagues navigating difficult seasons.</p>',
                'price' => 18.00,
                'compare_at_price' => null,
                'stock' => 40,
                'image' => 'images/site/path.jpg',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'product_category_id' => $catBooks->id,
                'name' => 'Crossing Life’s Rivers: 30-Day Devotional Guide',
                'slug' => 'crossing-lifes-rivers-30-day-devotional-guide',
                'excerpt' => 'Written by Jane Audrey. Daily scripture, heartfelt reflections, and guided prayer to navigate transitions.',
                'description' => '<p>A handsome softcover devotional taking you through 30 pivotal passages of Scripture, paired with clinical insights and practical prayers to help you cross life\'s deepest rivers with peace.</p>',
                'price' => 19.99,
                'compare_at_price' => 24.99,
                'stock' => 120,
                'image' => 'images/site/spiritual.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];
        foreach ($products as $p) {
            Product::firstOrCreate(['slug' => $p['slug']], $p);
        }

        // 8. Blog Categories & Posts
        $catFaith = PostCategory::firstOrCreate(['name' => 'Faith & Spiritual Growth'], ['slug' => 'faith-spiritual-growth']);
        $catFamily = PostCategory::firstOrCreate(['name' => 'Family & Relationships'], ['slug' => 'family-relationships']);
        $catPurpose = PostCategory::firstOrCreate(['name' => 'Calling & Purpose'], ['slug' => 'calling-purpose']);

        $posts = [
            [
                'post_category_id' => $catFaith->id,
                'user_id' => $admin->id,
                'title' => 'When Rivers Overflow: How to Stand When You Feel Overwhelmed',
                'slug' => 'when-rivers-overflow-how-to-stand-when-overwhelmed',
                'excerpt' => 'Isaiah 43:2 does not promise you will avoid the river; it promises the water will not overwhelm you. Here is how to cross with confidence.',
                'body' => '<p>There are seasons in life when challenges do not arrive as a gentle trickle. They arrive like swollen, turbulent waters after a sudden mountain storm. A medical diagnosis, an unexpected job loss, an emotional betrayal, or a family crisis can feel like a river threatening to drag you under.</p><h2>The Promise in the Crossing</h2><p>In Isaiah 43:2, God speaks directly to His people: <em>“When you pass through the waters, I will be with you; and when you pass through the rivers, they will not overwhelm you.”</em> Notice carefully that Scripture does not say <em>if</em> you pass through rivers, but <em>when</em>. Crossing rivers is a natural, unavoidable reality of life on this side of heaven.</p><blockquote>"God never promised an absence of deep waters. He promised His holy, unwavering presence right beside you in the current."</blockquote><h2>Three Steps to Guard Your Soul</h2><p>When you feel the current rising against you, practicing these three biblical disciplines will steady your feet:</p><ol><li><strong>Fix Your Eyes on the Guide:</strong> Peter only sank when he took his eyes off Jesus and looked at the wind and the waves (Matthew 14:30). When fear speaks, answer back with Scripture.</li><li><strong>Guard What Enters Your Mind:</strong> You cannot overcome anxiety if you are continuously feeding your spirit on 24-hour despair. Filter what you read, what you watch, and what you listen to through Philippians 4:8.</li><li><strong>Never Cross in Isolation:</strong> God designed the Body of Christ so that no believer walks alone. Reach out to a coach, a trusted brother or sister, or a prayer partner.</li></ol>',
                'image' => 'images/site/rivers-wide.jpg',
                'meta_description' => 'Practical biblical coaching on navigating overwhelming life seasons based on Isaiah 43:2.',
                'is_featured' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'post_category_id' => $catFamily->id,
                'user_id' => $admin->id,
                'title' => 'Rebuilding Fractured Family Bonds: A Biblical Blueprint',
                'slug' => 'rebuilding-fractured-family-bonds-biblical-blueprint',
                'excerpt' => 'God did not choose our family by accident. Discover how to initiate humble, healing conversations even after years of estrangement.',
                'body' => '<p>Unlike our friendships, which we choose based on shared interests or temperaments, God chose the family into which we were born. That truth alone should give us pause. If God orchestrated our placement in a specific household, then His glory, our character, and our growth are intrinsically tied to how we navigate those family relationships.</p><h2>Understanding the Roots of Discord</h2><p>Unspoken expectations, unresolved childhood wounds, and competitive jealousy have plagued families since Cain and Abel. But in Christ, we have been given the ministry of reconciliation (2 Corinthians 5:18).</p><p>Rebuilding broken family trust does not happen overnight. It begins with one person deciding to lay down the need to win an argument in exchange for winning a brother or sister\'s heart.</p>',
                'image' => 'images/site/about-hero.jpg',
                'meta_description' => 'A Christ-centered approach to restoring damaged family dynamics and healing generational friction.',
                'is_featured' => false,
                'published_at' => now()->subDays(8),
            ],
            [
                'post_category_id' => $catPurpose->id,
                'user_id' => $admin->id,
                'title' => 'Determining What God Created You to Do: Life’s Greatest Question',
                'slug' => 'determining-what-god-created-you-to-do',
                'excerpt' => 'You will not experience enduring peace until you walk in divine assignment. Here is how to recognize the clues God placed in your story.',
                'body' => '<p>Why am I here? What was I made for? Is my life making a difference? These questions are not vanity; they are the holy rumblings of an eternal spirit yearning for alignment with its Creator.</p><p>Jeremiah 29:11 reminds us that God has definite, prosperous plans for each of His children. Your purpose is rarely found by imitating someone else’s highlight reel. It is uncovered at the intersection of your God-given spiritual gifts, your deepest burdens, and the needs of people around you.</p>',
                'image' => 'images/site/path.jpg',
                'meta_description' => 'Discover your God-given calling and life purpose through biblical discernment and spiritual coaching.',
                'is_featured' => true,
                'published_at' => now()->subDays(14),
            ],
        ];
        foreach ($posts as $post) {
            Post::firstOrCreate(['slug' => $post['slug']], $post);
        }

        // 9. Podcasts & Episodes
        $show = Podcast::firstOrCreate(
            ['slug' => 'crossing-over-with-jane-audrey'],
            [
                'title' => 'Crossing Over with Jane Audrey',
                'description' => 'Practical wisdom, inspirational scripture, and life coaching conversations helping you cross over life’s challenges and step into God’s best life.',
                'cover' => 'images/site/podcast.jpg',
                'sort_order' => 1,
            ]
        );

        $episodes = [
            [
                'podcast_id' => $show->id,
                'title' => 'Episode 01: Standing on the Riverbank — Overcoming the Fear to Step Out',
                'slug' => 'ep-01-standing-on-the-riverbank',
                'episode_number' => 1,
                'excerpt' => 'Jane shares the foundational message of Cross Rivers Ministries and why fear keeps us paralyzed on the bank.',
                'description' => '<p>In this inaugural episode, coach Jane Audrey shares the vision behind Cross Rivers Ministries. When Joshua and the priests stepped into the Jordan River, the waters did not part while they stood safely on dry ground—the river divided only when their feet actually touched the edge of the water (Joshua 3:15). Today, Jane breaks down how to overcome paralysis and take that first faithful step.</p>',
                'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
                'image' => 'images/site/podcast.jpg',
                'duration' => '24:18',
                'is_featured' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'podcast_id' => $show->id,
                'title' => 'Episode 02: Guarding the Gate of Your Mind (Proverbs 23:7)',
                'slug' => 'ep-02-guarding-the-gate-of-your-mind',
                'episode_number' => 2,
                'excerpt' => 'As a man thinketh in his heart, so is he. Jane discusses how to dismantle negative self-limiting mental loops.',
                'description' => '<p>Your life invariably moves in the direction of your strongest thoughts. In this episode, Jane explains practical methods drawn from her clinical nursing background and biblical coaching to interrupt spiraling worry and re-anchor your thought life in the peace of God.</p>',
                'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
                'image' => 'images/site/journal.jpg',
                'duration' => '31:45',
                'is_featured' => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'podcast_id' => $show->id,
                'title' => 'Episode 03: The Naomi & Ruth Bond — The Power of Loyal Prayer Partners',
                'slug' => 'ep-03-the-naomi-ruth-bond',
                'episode_number' => 3,
                'excerpt' => 'Exploring the rare gift of spiritual friendship, intercession, and standing with each other through grief.',
                'description' => '<p>Ruth clung to Naomi when everything had fallen apart. Jane explores why deep, covenantal Christian friendships are essential when traversing seasons of sadness and loss.</p>',
                'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3',
                'image' => 'images/site/spiritual.jpg',
                'duration' => '28:10',
                'is_featured' => false,
                'published_at' => now()->subDays(19),
            ],
        ];
        foreach ($episodes as $ep) {
            PodcastEpisode::firstOrCreate(['slug' => $ep['slug']], $ep);
        }

        // 10. Videos & Categories
        $vCat = VideoCategory::firstOrCreate(['name' => 'Ministry Messages'], ['slug' => 'ministry-messages']);
        $vCatCoach = VideoCategory::firstOrCreate(['name' => 'Coaching Moments'], ['slug' => 'coaching-moments']);

        $videos = [
            [
                'video_category_id' => $vCat->id,
                'title' => 'Welcome to Cross Rivers Ministries — Helping You Cross Over',
                'slug' => 'welcome-to-cross-rivers-ministries',
                'excerpt' => 'An introduction to our mission, coaching philosophy, and heartfelt promise to walk with you.',
                'description' => '<p>Join founder Jane Audrey as she shares the heart of Cross Rivers Ministries. Whether you are facing relational, financial, emotional, or vocational rivers, there is hope, purpose, and guidance available in Jesus Christ.</p>',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'images/site/video-cover.jpg',
                'duration' => '04:30',
                'is_featured' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'video_category_id' => $vCatCoach->id,
                'title' => 'How the Coaching Advice Line Works: Real Hope, Real Guidance',
                'slug' => 'how-the-coaching-advice-line-works',
                'excerpt' => 'A behind-the-scenes look at how our telephonic coaching sessions bring clarity and comfort in minutes.',
                'description' => '<p>Are you wondering what happens when you dial the Coaching Advice Line? Watch this short walkthrough on what to expect, how our certified coaches listen attentively, and how biblical wisdom is applied to your situation.</p>',
                'video_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                'thumbnail' => 'images/site/who-we-are.jpg',
                'duration' => '03:15',
                'is_featured' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'video_category_id' => $vCat->id,
                'title' => 'Crossing Rivers of Anxiety: A Message of Encouragement',
                'slug' => 'crossing-rivers-of-anxiety',
                'excerpt' => 'Jane shares biblical encouragement for anyone feeling paralyzed by panic, anxiety, or heavy burdens.',
                'description' => '<p>You are not alone in your struggle. In this heartfelt video reflection, Jane Audrey guides viewers through Philippians 4 and delivers a targeted prayer for peace and deliverance.</p>',
                'video_url' => 'https://www.youtube.com/watch?v=fJ9rUzIMcZQ',
                'thumbnail' => 'images/site/sunrise-hands.jpg',
                'duration' => '08:45',
                'is_featured' => false,
                'published_at' => now()->subDays(16),
            ],
        ];
        foreach ($videos as $v) {
            Video::firstOrCreate(['slug' => $v['slug']], $v);
        }

        // 11. Sample Subscriber
        Subscriber::firstOrCreate(
            ['email' => 'partner@crossriversministries.org'],
            ['name' => 'Faith Partner', 'source' => 'homepage_footer']
        );
    }
}
