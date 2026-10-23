# The Brand Concierge — Premium Homepage & Client Roster

A premium, sophisticated, visually distinctive website created for **The Brand Concierge**, a full-service branding, creative, and digital marketing agency.

---

## ✦ Brand Identity & Design System

- **Background Palette:**
  - **Primary Background:** `#FFFFFF` (Crisp White) with subtle off-white `#F8F8F8` and `#F3F3F3` surfaces for refined editorial rhythm.
  - **Selective Black Accents ("kanhi kanhi black"):** High-impact contrast sections including the **Brand Statement Section** (`#000000`), the **Substantial Footer** (`#000000`), the **Hero Floating Authority Badge** (`#000000`), and dark buttons.
  - **Logo Peach:** `#D8B19D` (Selective editorial highlights, borders, active hover states, and primary CTAs).
- **Typography:**
  - **Montserrat** (Google Fonts): Bold oversized headings (`clamp()`), controlled uppercase tracking, refined editorial body copy.
- **Aesthetic Direction:**
  - High-end independent creative studio feel (Pentagram, Basic/Dept, and Studio Freight inspired).
  - Editorial negative space, tactile noise grain texture overlay, confident typography.

---

## ✦ Pages & Navigation

### 1. Main Navigation
- **Home:** [index.html#hero](index.html#hero)
- **About:** [about.html](about.html)
- **Services:** [index.html#services](index.html#services)
- **Our Work:** [work.html](work.html)
- **Clients:** [clients.html](clients.html) *(Separate dedicated page)*
- **Contact:** [contact.html](contact.html)
- **Primary CTA:** *"Let's Talk"*

---

## ✦ Homepage Sections

1. **Header & Navigation:**
   - Minimalist header with brand emblem and wordmark on the left.
   - Desktop navigation links with animated underline hover state.
   - Peach-accented *"Let's Talk"* button.
   - Frosted backdrop blur on scroll (`.tbc-header.scrolled`).
   - Delicate animated peach light shimmer hairline along the header border (`headerLineShimmer`).
   - Fullscreen mobile drawer with dedicated top bar, brand logo, and **prominent explicit Close button ("CLOSE ✕")** for phone view.

2. **Hero Section (White Canvas with Animated Ambient Aurora):**
   - **Subtle Animated Aurora Background:** Shifting, organic ambient light orbs utilizing brand Logo Peach (`#D8B19D`) and warm Champagne silk tones gently undulating behind the hero and header area.
   - Interactive pointer-movement parallax subtly shifting aurora orbs and editorial media.
   - Headline: *"WE BUILD BRANDS THAT MEAN BUSINESS."* with GSAP masked line reveals.
   - Supporting narrative: *“Strategic thinking. Distinctive design. Meaningful experiences. We help ambitious brands turn their vision into lasting impact.”*
   - Asymmetrical composition with negative space, live studio status badge, floating authority card.
   - CTAs: *Explore Our Work* (dark button) & *Start a Conversation* (peach button) with full-width responsive stacking on mobile phones.

3. **Client Logos Auto-Slider (Infinite Marquee Ticker):**
   - Infinite continuous auto-sliding ticker featuring high-end brand marks:
     - Aura Atelier (Paris / Dubai)
     - Kroma Architecture (Zurich / London)
     - Vela Botanicals (London / New York)
     - Neo Horizons Capital (Singapore / Tokyo)
     - Élément Noir (Milan / Paris)
     - Sonique Acoustics (Berlin / San Francisco)
     - Maison Vance (Geneva)
     - Archetype Hospitality (Kyoto / New York)
     - Verdant Living (Stockholm)
   - Pauses on hover; smooth linear animation.
   - Shortcut link to the full [clients.html](clients.html) page.

4. **Brand Introduction (White / Split Editorial):**
   - Heading: *"GOOD BRANDS GET NOTICED. GREAT BRANDS GET REMEMBERED."*
   - Split layout with agency narrative, three core pillars (*Strategic Rigor*, *Art-Directed Depth*, *Digital Mastery*), layered dual-image visual composition, and rotating peach studio seal. Responsive image stacking on mobile.

5. **Services Section (Refined Numbered List):**
   - Heading: *"WHAT WE BRING TO THE TABLE."*
   - Hairline-divided numbered list of all 10 core agency capabilities:
     1. Brand Strategy & Identity
     2. Logo Design & Brand Guidelines
     3. Packaging & Print Design
     4. Website Design & Development
     5. Social Media Management
     6. Photography & Creative Production
     7. SEO & Performance Marketing
     8. Influencer Marketing
     9. Public Relations & Online PR
     10. Event Management & Brand Experiences
   - **Desktop Floating Image Preview:** Hovering over any service smoothly crossfades and tracks an editorial preview image to the cursor using GSAP `quickTo` physics.
   - Touch-optimized clean responsive layout on mobile without awkward wrapping.

6. **03 // Selected Archive — Overlapping Multi-Color Stacking Cards on Scroll:**
   - Tabs removed from desktop and phone as requested, allowing uninterrupted scroll experience.
   - *"Commission A Project"* button hidden on phone (`d-none d-md-block`) to preserve clean mobile screen estate.
   - **Mobile Phone Card Layout:** Project image placed prominently on **TOP** of each stacking card, with project index, title, description, and button stacked underneath.
   - Physical stacking cards that dock and smoothly overlap with vibrant multi-color luxury palettes:
     - **Card 01 (Signature Peach `#D8B19D`):** *Aura Atelier* (Haute Parfumerie & Brand Architecture)
     - **Card 02 (Deep Obsidian Noir `#0F0F12`):** *Kroma Architecture* (Spatial Architecture & Monograph)
     - **Card 03 (Warm Terracotta Linen `#9E5A44`):** *Vela Botanicals* (Sustainable Luxury Packaging)
     - **Card 04 (Sage Celadon Mist `#4E6B5D`):** *Neo Horizons Capital* (Private Equity Rebrand)
     - **Card 05 (Imperial Navy Silk `#182333`):** *Élément Noir* (High Fashion Campaign)
     - **Card 06 (Titanium Ivory `#F1EDE6`):** *Sonique Acoustics* (Acoustic Launch & Spatial Pavilion)
   - Sticky scroll depth scaling with GSAP ScrollTrigger on desktop, and smooth responsive sticky card overlap on mobile.
   - Interactive Case Study Preview Modal on click.

7. **Brand Statement Section (High Contrast Black Section):**
   - Full-width black section with kinetic typographic background ticker, oversized typography, and `Let's Talk About Your Brand` CTA.

8. **Contact Section (Light Editorial Layout):**
   - Studio contacts: `+91-9810140716`, `+91-9899678811`, `contact@thebrandconcierge.in`, and `info@thebrandconcierge.in`.
   - Inquiry form with Name, Work Email, Company Name, interactive multi-select Service pills, and Project Details.
   - Real-time client-side validation with transparent submission feedback.

9. **Substantial Black Footer:**
   - Brand tagline, quick links, capabilities index, studio telephone and email contacts, copyright (`© 2017 The Brand Concierge`), verified social links, and smooth *Back to Top* button.

---

## ✦ Dedicated Clients Page (`clients.html`)

- **Hero Header:** Bold headline *"PARTNERS IN PREEMINENCE"* with agency collaboration metrics.
- **Auto-Sliding Client Marquee:** Continuous infinite brand monogram ticker.
- **Filterable Client Directory:** Filterable by sector (*All Sectors*, *Luxury & Fashion*, *Spatial & Architecture*, *Ventures & Tech*, *Hospitality & Lifestyle*).
- **Signature Case Collaboration Spotlights:** Long-term partner stories (*Aura Atelier*, *Kroma Architecture*, *Neo Horizons Capital*).
- **Partnership Invitation CTA:** Direct commission inquiry hook.

---

## ✦ File Structure

```
theb/
├── index.html                           # Semantic HTML5 homepage (White theme base)
├── about.html                           # Dedicated Agency About page
├── work.html                            # Dedicated Portfolio & Selected Work page
├── clients.html                         # Dedicated Clients & Brand Partnerships page
├── contact.html                         # Dedicated Studio Contact & Inquiries page
├── brand-strategy-identity.html         # Service: Brand Strategy & Identity
├── logo-design-brand-guidelines.html    # Service: Logo Design & Brand Guidelines
├── packaging-print-design.html          # Service: Packaging & Print Design
├── website-design-development.html      # Service: Website Design & Development
├── social-media-management.html         # Service: Social Media Management
├── photography-creative-production.html # Service: Photography & Creative Production
├── seo-performance-marketing.html       # Service: SEO & Performance Marketing
├── influencer-marketing.html            # Service: Influencer Marketing
├── public-relations-online-pr.html      # Service: Public Relations & Online PR
├── event-management-experiences.html    # Service: Event Management & Experiences
├── css/
│   └── style.css                        # Custom CSS design system, typography & animations
├── js/
│   └── main.js                          # Vanilla JS, GSAP timelines, stacking cards, cursor, modal, form
├── fonts/                               # Brand typography font files
├── images/                              # Brand visuals, client logos, service & work assets
├── videos/                              # Agency reel showcase videos
└── README.md
```

---

## ✦ How to Run & Preview

Open [index.html](index.html) or [clients.html](clients.html) directly in any browser:
```powershell
npx serve .
# or
python -m http.server 8000
```
