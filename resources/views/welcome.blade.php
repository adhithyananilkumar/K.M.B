<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>Aathmiya | Empowering Compassionate Care</title>
            <link rel="preconnect" href="https://fonts.bunny.net">
            <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
            <style>
                :root { --aw-primary:#0f4f4b; --aw-accent:#0EA5A1; --aw-dark:#0b3b38; --aw-bg:#f6fffd; }
                body { font-family:'Figtree', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, sans-serif; background:linear-gradient(160deg,#ffffff 0%, var(--aw-bg) 100%); color:#132221; }
                .navbar { backdrop-filter:blur(8px); }
                .hero { position:relative; padding:6rem 0 4rem; overflow:hidden; }
                .hero:before, .hero:after { content:""; position:absolute; border-radius:50%; pointer-events:none; mix-blend-mode:multiply; }
                .hero:before { width:520px; height:520px; background:radial-gradient(circle at 30% 30%,rgba(14,165,161,.35),transparent 70%); top:-140px; left:-160px; }
                .hero:after { width:680px; height:680px; background:radial-gradient(circle at 70% 60%,rgba(15,79,75,.25),transparent 70%); bottom:-260px; right:-220px; }
                .hero-heading { font-weight:700; letter-spacing:-.02em; }
                .gradient-text { background:linear-gradient(90deg,var(--aw-primary),var(--aw-accent)); -webkit-background-clip:text; color:transparent; }
                .lead { font-size:1.15rem; }
                .aw-btn-primary { background:var(--aw-primary); border-color:var(--aw-primary); }
                .aw-btn-primary:hover { background:var(--aw-dark); border-color:var(--aw-dark); }
                .feature-icon { width:48px; height:48px; background:linear-gradient(135deg,var(--aw-primary),var(--aw-accent)); color:#fff; display:flex; align-items:center; justify-content:center; border-radius:14px; font-size:1.4rem; box-shadow:0 6px 18px -6px rgba(15,79,75,.4); }
                .glass { backdrop-filter:blur(16px); background:rgba(255,255,255,.75); border:1px solid rgba(15,79,75,.10); border-radius:1.25rem; box-shadow:0 12px 36px -10px rgba(0,0,0,.15); }
                .stat { font-weight:600; font-size:1.1rem; }
                .footer { background:#ffffff; border-top:1px solid rgba(15,79,75,.1); }
                .shape-divider { position:absolute; left:0; bottom:-1px; width:100%; line-height:0; }
                .shape-divider svg { display:block; width:100%; height:90px; }
                a.aw-link { color:var(--aw-primary); text-decoration:none; }
                a.aw-link:hover { color:var(--aw-dark); text-decoration:underline; }
                @media (max-width: 575.98px){ .hero { padding:5rem 0 3rem; } }
            </style>
        </head>
        <body>
            <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                        <img src="{{ asset('assets/aathmiya.png') }}" alt="Aathmiya" style="height:32px;width:auto">
                        
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                            
                            @auth
                                <li class="nav-item ms-lg-2"><a href="{{ route('dashboard') }}" class="btn aw-btn-primary text-white rounded-pill px-3">Dashboard</a></li>
                            @else
                                <li class="nav-item ms-lg-2"><a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-3">Log in</a></li>
                                @if(Route::has('register'))
                                    <li class="nav-item mt-2 mt-lg-0"><a href="{{ route('register') }}" class="btn aw-btn-primary text-white rounded-pill px-3">Create account</a></li>
                                @endif
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>

            <header class="hero position-relative">
                <div class="container position-relative">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6">
                            <h1 class="display-5 hero-heading mb-3">
                                Compassionate <span class="gradient-text">Care Management</span> Simplified
                            </h1>
                            <p class="lead text-secondary mb-4">Aathmiya unifies medical records, medication tracking, lab workflows, inmate wellbeing logs and guardian communication—so your team focuses on people, not paperwork.</p>
                            <div class="d-flex flex-wrap gap-3 mb-4">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn aw-btn-primary text-white px-4 py-2 rounded-pill"><i class="bi bi-speedometer2 me-1"></i> Go to Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn aw-btn-primary text-white px-4 py-2 rounded-pill"><i class="bi bi-box-arrow-in-right me-1"></i> Get Started</a>
                                    @if(Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-dark px-4 py-2 rounded-pill"><i class="bi bi-person-plus me-1"></i> Create Account</a>
                                    @endif
                                @endauth
                            </div>
                            <div class="row g-3 small">
                                <div class="col-6 col-sm-4">
                                    <div class="glass p-3 h-100">
                                        <div class="stat"><i class="bi bi-shield-check text-success me-1"></i>Secure</div>
                                        <div class="text-secondary">Role-based & auditable</div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="glass p-3 h-100">
                                        <div class="stat"><i class="bi bi-activity text-primary me-1"></i>Real‑time</div>
                                        <div class="text-secondary">Live medication & labs</div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="glass p-3 h-100">
                                        <div class="stat"><i class="bi bi-people text-info me-1"></i>Collaborative</div>
                                        <div class="text-secondary">Doctors • Nurses • Guardians</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="glass p-4 p-md-5">
                                <div class="row g-4" id="features">
                                    <div class="col-12 d-flex gap-3">
                                        <div class="feature-icon"><i class="bi bi-heart-pulse"></i></div>
                                        <div>
                                            <h5 class="mb-1">Unified Health Profiles</h5>
                                            <p class="mb-0 text-secondary small">Centralized inmate medical, mental health, therapy and care plans—instantly accessible to authorized staff.</p>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex gap-3">
                                        <div class="feature-icon"><i class="bi bi-capsule"></i></div>
                                        <div>
                                            <h5 class="mb-1">Medication & Administration Logs</h5>
                                            <p class="mb-0 text-secondary small">Track inventory, dosage windows, adherence and live intake events with audit trails.</p>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex gap-3">
                                        <div class="feature-icon"><i class="bi bi-lab"></i></div>
                                        <div>
                                            <h5 class="mb-1">Lab Ordering & Results Flow</h5>
                                            <p class="mb-0 text-secondary small">Order tests, monitor status, capture results, notify clinicians, handle rejections seamlessly.</p>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex gap-3">
                                        <div class="feature-icon"><i class="bi bi-chat-dots"></i></div>
                                        <div>
                                            <h5 class="mb-1">Guardian Engagement</h5>
                                            <p class="mb-0 text-secondary small">Secure updates and messaging enhance trust and wellbeing visibility.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shape-divider">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160"><path fill="#FFFFFF" fill-opacity="1" d="M0,160L80,144C160,128,320,96,480,74.7C640,53,800,43,960,64C1120,85,1280,139,1360,165.3L1440,192L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path></svg>
                </div>
            </header>

            <section id="why" class="py-5">
                <div class="container">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-5">
                            <h2 class="fw-bold mb-3">Why choose <span class="gradient-text">Aathmiya</span>?</h2>
                            <p class="text-secondary mb-3">Built specifically for compassionate institutional care settings where continuity, safety and human dignity matter.</p>
                            <ul class="list-unstyled small mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>End‑to‑end record cohesion</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Fine‑grained role permissions</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Actionable real‑time dashboards</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Event driven notifications</li>
                            </ul>
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn aw-btn-primary text-white rounded-pill px-4">Open Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn aw-btn-primary text-white rounded-pill px-4">Start Now</a>
                            @endauth
                        </div>
                        <div class="col-lg-7">
                            <div class="glass p-4 p-md-5">
                                <div class="row g-4">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="feature-icon mx-auto mb-2" style="width:56px;height:56px;"><i class="bi bi-speedometer2"></i></div>
                                            <div class="fw-semibold">Fast Insights</div>
                                            <div class="small text-secondary">Status‑driven dashboards</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="feature-icon mx-auto mb-2" style="width:56px;height:56px;background:linear-gradient(135deg,#0EA5A1,#0f4f4b)"><i class="bi bi-database-lock"></i></div>
                                            <div class="fw-semibold">Secure Storage</div>
                                            <div class="small text-secondary">Encrypted + audited</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="feature-icon mx-auto mb-2" style="width:56px;height:56px;background:linear-gradient(135deg,#0f4f4b,#0EA5A1)"><i class="bi bi-bell"></i></div>
                                            <div class="fw-semibold">Smart Alerts</div>
                                            <div class="small text-secondary">Medication & labs</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="feature-icon mx-auto mb-2" style="width:56px;height:56px;"><i class="bi bi-arrows-fullscreen"></i></div>
                                            <div class="fw-semibold">Scalable</div>
                                            <div class="small text-secondary">Multi‑institution ready</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact" class="py-5 bg-white">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <h2 class="fw-bold mb-3">Stay connected</h2>
                            <p class="text-secondary mb-4">Need access or have a question? Reach out to your administrator or email <a href="mailto:support@aathmiya.local" class="aw-link">support@aathmiya.local</a>.</p>
                            @guest
                                <a href="{{ route('login') }}" class="btn aw-btn-primary text-white rounded-pill px-4"><i class="bi bi-box-arrow-in-right me-1"></i> Log in</a>
                                @if(Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-dark rounded-pill px-4 ms-2"><i class="bi bi-person-plus me-1"></i> Create account</a>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </section>

            <footer class="footer py-4 text-center small text-muted">
                <div class="container">
                    <div>&copy; {{ date('Y') }} AJCE24BCA</div>
                </div>
            </footer>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
