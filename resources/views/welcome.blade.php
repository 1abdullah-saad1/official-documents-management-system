<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'نظام رشفة البريد الصادر والوارد') }}</title>

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optional) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
    body {
        background-color: #f8f9fa;
    }

    .brand-title {
        font-weight: 700;
    }

    .hero {
        background: linear-gradient(135deg, #e9f3ff, #fff);
        border-radius: 1rem;
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        border-radius: .75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #0d6efd1a;
        color: #0d6efd;
    }

    .mosul-badge {
        background-color: #0d6efd;
    }

    .logo {
        max-height: 56px;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <img class="logo" src="{{ asset('assets/images/logo.png') }}" alt="شعار جامعة الموصل"
                    onerror="this.onerror=null;this.src='{{ asset('images/mosul-placeholder.svg') }}'">
                <span class="brand-title">جامعة الموصل</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
                aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="topNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">المزايا</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">تواصل</a></li>
                </ul>
                @if (Route::has('login'))
                <div class="d-flex gap-2">
                    @auth
                    <a href="{{ url('/home') }}" class="btn btn-outline-primary">لوحة التحكم</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary">الدخول</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary">تسجيل</a>
                    @endif
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <header class="py-5 py-lg-5">
        <div class="container">
            <div class="row align-items-center gy-4 hero p-4 p-lg-5">
                <div class="col-12 col-lg-6">
                    <span class="badge mosul-badge text-white mb-2">جامعة الموصل</span>
                    <h1 class="display-6 mb-3">نظام رشفة البريد الصادر والوارد</h1>
                    <p class="lead text-secondary mb-4">
                        منصة جامعية لإدارة البريد الصادر والوارد بسهولة، تتبع المعاملات، والأرشفة الذكية، مع دعم كامل
                        للغة العربية واتجاه الكتابة من اليمين إلى اليسار.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @if (Route::has('login'))
                        @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary btn-lg">الانتقال للوحة التحكم</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">ابدأ الآن</a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">إنشاء حساب</a>
                        @endif
                        @endauth
                        @endif
                    </div>
                </div>
                <div class="col-12 col-lg-6 text-center">
                    <img class="img-fluid" src="{{ asset('/assets/images/landing.png') }}" alt="شعار النظام"
                        style="max-height:220px"
                        onerror="this.onerror=null;this.src='{{ asset('images/mosul-placeholder.svg') }}'">
                </div>
            </div>
        </div>
    </header>

    <!-- Features -->
    <section id="features" class="py-5 bg-white border-top border-bottom">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="feature-icon mb-3"><i class="bi bi-inboxes"></i></div>
                            <h5 class="card-title mb-2">إدارة البريد</h5>
                            <p class="card-text text-secondary">تسجّل وتتبع البريد الصادر والوارد مع حالة المعاملة
                                وخطواتها.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="feature-icon mb-3"><i class="bi bi-search"></i></div>
                            <h5 class="card-title mb-2">بحث وأرشفة</h5>
                            <p class="card-text text-secondary">أرشفة المستندات والبحث السريع حسب الجهة أو الرقم أو
                                التاريخ.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="feature-icon mb-3"><i class="bi bi-shield-check"></i></div>
                            <h5 class="card-title mb-2">صلاحيات وأمان</h5>
                            <p class="card-text text-secondary">أدوار وصلاحيات دقيقة للمستخدمين وفق هيكل الجامعة.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="feature-icon mb-3"><i class="bi bi-graph-up"></i></div>
                            <h5 class="card-title mb-2">تقارير ولوحات</h5>
                            <p class="card-text text-secondary">لوحات معلومات وتقارير لدعم اتخاذ القرار الإداري.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="py-4">
        <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-center gap-2">
            <div class="text-secondary">© {{ date('Y') }} جامعة الموصل — جميع الحقوق محفوظة</div>
            <div class="d-flex gap-3">
                <a class="link-secondary" href="#">سياسة الخصوصية</a>
                <a class="link-secondary" href="#">الشروط والأحكام</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
