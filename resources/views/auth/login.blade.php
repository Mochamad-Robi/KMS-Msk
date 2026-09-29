@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=IBM+Plex+Sans:wght@400;500;600&display=swap');

  :root{
    --maroon:#7f0d0d;
    --maroon-lit:#A81B2A;
    --plum:#4A1240;
    --indigo:#2B1A54;
    --gold:#E8B44C;
    --gold-soft:#F7DFAE;
    --paper:#FBF8F5;
    --ink:#241C1A;
    --ink-soft:#7A6D66;
    --line:#EAE1D8;
    --ease:cubic-bezier(.76,0,.24,1);
    --sheet:cubic-bezier(.32,.72,0,1);
  }

  .auth{ font-family:'IBM Plex Sans',sans-serif; color:var(--ink); }
  .auth .display{ font-family:'Sora',sans-serif; }
  .auth img{ max-width:100%; height:auto; }

  .wrap{ position:fixed; inset:0; overflow:hidden; background:var(--paper); }

  /* ================= PANEL ================= */
  .panel{
    position:absolute; top:0; left:0; height:100%; width:100%;
    z-index:20; overflow:hidden;
    background:linear-gradient(145deg, var(--maroon-lit) 0%, var(--maroon) 32%, var(--plum) 68%, var(--indigo) 100%);
    transition:width 1.05s var(--ease);
    will-change:width;
  }
  @media (min-width:1024px){
    .open .panel{ width:52%; }
  }

  .orb{ position:absolute; border-radius:50%; filter:blur(60px); will-change:transform; }
  .orb-a{ width:36vw;height:36vw;min-width:260px;min-height:260px;top:-10%;left:-8%;
    background:radial-gradient(circle,#E0455C 0%,rgba(224,69,92,0) 70%); opacity:.5;
    animation:fA 20s ease-in-out infinite; }
  .orb-b{ width:30vw;height:30vw;min-width:220px;min-height:220px;bottom:-14%;right:-6%;
    background:radial-gradient(circle,var(--gold) 0%,rgba(232,180,76,0) 70%); opacity:.26;
    animation:fB 26s ease-in-out infinite; }
  .orb-c{ width:26vw;height:26vw;min-width:200px;min-height:200px;top:38%;left:44%;
    background:radial-gradient(circle,#6C3BD1 0%,rgba(108,59,209,0) 70%); opacity:.36;
    animation:fC 23s ease-in-out infinite; }
  @keyframes fA{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(5vw,6vh) scale(1.15)}}
  @keyframes fB{0%,100%{transform:translate(0,0) scale(1.08)}50%{transform:translate(-6vw,-5vh) scale(1)}}
  @keyframes fC{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(-4vw,4vh) scale(1.18)}}

  .ring{ position:absolute;border:1.5px solid rgba(255,255,255,.09);border-radius:50%; }
  .ring-1{ width:560px;height:560px;top:-180px;right:-170px; }
  .ring-2{ width:380px;height:380px;bottom:-130px;left:-110px;border-color:rgba(232,180,76,.15); }

  .curve{
    position:absolute; top:0; right:-1px; height:100%; width:92px;
    color:var(--paper); z-index:5; opacity:0;
    transition:opacity .45s ease .55s;
  }
  .open .curve{ opacity:1; }
  @media (max-width:1023px){ .curve{ display:none; } }

  /* ---- STATE 1: WELCOME ---- */
  .welcome{
    position:absolute; inset:0; z-index:6;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    text-align:center; padding:32px 24px;
    transition:opacity .45s ease, transform .7s var(--ease);
  }
  /* Desktop: welcome memudar. Mobile: welcome naik sedikit & meredup di belakang sheet */
  @media (min-width:1024px){
    .open .welcome{ opacity:0; transform:scale(.94); pointer-events:none; }
  }
  @media (max-width:1023px){
    .welcome{ justify-content:flex-start; padding-top:12vh; }
    .open .welcome{
      transform:translateY(-9vh) scale(.92);
      opacity:.35; pointer-events:none;
    }
  }

  .logo-hero{
    height:78px !important; width:auto !important; max-width:290px !important;
    object-fit:contain !important; flex:0 0 auto !important;
    display:block; margin-bottom:22px;
  }
  .logo-form{
    height:64px !important; width:auto !important; max-width:260px !important;
    object-fit:contain !important; display:block; margin:0 auto 16px !important;
  }

  /* ---- STATE 2: SIDE (desktop only) ---- */
  .side{
    position:absolute; inset:0; z-index:6;
    display:flex; flex-direction:column; justify-content:space-between;
    padding:34px 108px 34px 52px;
    opacity:0; transform:translateX(-22px); pointer-events:none;
    transition:opacity .5s ease .5s, transform .7s var(--ease) .45s;
  }
  .open .side{ opacity:1; transform:translateX(0); pointer-events:auto; }
  @media (max-width:1023px){ .side{ display:none !important; } }

  .chip{
    display:inline-flex;align-items:center;gap:8px;
    padding:7px 15px;border-radius:999px;width:fit-content;
    border:1px solid rgba(247,223,174,.30);background:rgba(247,223,174,.10);
    font-size:10.5px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;
    color:var(--gold-soft);
  }
  .live{ width:6px;height:6px;border-radius:50%;background:var(--gold);animation:bl 2.4s ease-in-out infinite; }
  @keyframes bl{0%,100%{opacity:.4;transform:scale(.85)}50%{opacity:1;transform:scale(1.2)}}

  .feat{ display:flex;align-items:center;gap:12px;font-size:13.5px;color:rgba(255,255,255,.72); }
  .feat-ico{
    width:32px;height:32px;border-radius:9px;flex-shrink:0;
    background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.14);
    display:flex;align-items:center;justify-content:center;color:var(--gold-soft);
  }

  .btn-ghost{
    display:inline-flex;align-items:center;gap:10px;
    padding:15px 46px;border-radius:999px;cursor:pointer;
    border:1.5px solid rgba(255,255,255,.55); background:transparent;
    font-family:'Sora',sans-serif;font-size:14px;font-weight:600;
    letter-spacing:.12em;text-transform:uppercase;color:#fff;
    transition:background .25s, border-color .25s, transform .18s, box-shadow .25s;
  }
  .btn-ghost:hover{
    background:#fff; color:var(--maroon); border-color:#fff;
    transform:translateY(-2px); box-shadow:0 14px 30px -12px rgba(0,0,0,.55);
  }
  .btn-ghost:active{ transform:translateY(0) scale(.97); }
  .btn-ghost svg{ transition:transform .25s ease; }
  .btn-ghost:hover svg{ transform:translateX(4px); }

  /* ================= FORM ================= */
  /* Desktop: kolom kanan. Mobile: bottom sheet */
  .form-col{
    position:absolute; z-index:30;
    display:flex; align-items:center; justify-content:center;
    overflow-y:auto; -webkit-overflow-scrolling:touch;
  }

  @media (min-width:1024px){
    .form-col{
      top:0; right:0; height:100%; width:48%;
      padding:18px 20px; background:transparent;
      opacity:0; transform:translateX(28px); pointer-events:none;
      transition:opacity .55s ease .5s, transform .8s var(--ease) .45s;
    }
    .open .form-col{ opacity:1; transform:translateX(0); pointer-events:auto; }
  }

  @media (max-width:1023px){
    .form-col{
      left:0; right:0; bottom:0;
      max-height:92vh; max-height:92dvh;
      align-items:flex-start;
      padding:0 20px 24px;
      background:var(--paper);
      border-radius:26px 26px 0 0;
      box-shadow:0 -14px 46px -10px rgba(0,0,0,.42);
      transform:translateY(100%);
      transition:transform .68s var(--sheet);
      will-change:transform;
    }
    .open .form-col{ transform:translateY(0); }
  }

  /* handle sheet (mobile only) */
  .sheet-grip{ display:none; }
  @media (max-width:1023px){
    .sheet-grip{
      display:block; width:42px; height:4.5px; border-radius:999px;
      background:#DDD3C8; margin:11px auto 14px; flex-shrink:0;
    }
  }

  .form-box{ width:100%; max-width:352px; margin:0 auto; }

  /* backdrop gelap di belakang sheet (mobile) */
  .scrim{
    position:absolute; inset:0; z-index:25;
    background:rgba(12,2,4,.45);
    opacity:0; pointer-events:none;
    transition:opacity .5s ease;
  }
  @media (max-width:1023px){
    .open .scrim{ opacity:1; pointer-events:auto; }
  }
  @media (min-width:1024px){ .scrim{ display:none; } }

  .lbl{ display:block;font-size:12px;font-weight:600;color:var(--ink-soft);margin-bottom:6px; }
  .fw{ position:relative; }
  .field{
    display:block;width:100%;box-sizing:border-box;
    padding:13px 14px 13px 42px;font-size:15px;color:var(--ink);
    background:#fff;border:1.5px solid var(--line);border-radius:12px;
    transition:border-color .18s,box-shadow .18s;
  }
  .field::placeholder{ color:#BCB1A8; }
  .field:focus{ outline:none;border-color:var(--maroon);box-shadow:0 0 0 3.5px rgba(127,13,13,.10); }
  .field-plain{ padding-left:14px; }
  .field-pw{ padding-right:44px; }
  .field.err{ border-color:#D14B63;box-shadow:0 0 0 3.5px rgba(209,75,99,.10); }

  .f-ico{
    position:absolute;left:13px;top:50%;transform:translateY(-50%);
    width:17px;height:17px;color:#BCB1A8;pointer-events:none;transition:color .18s;
  }
  .fw:focus-within .f-ico{ color:var(--maroon); }

  .eye{
    position:absolute;right:6px;top:50%;transform:translateY(-50%);
    width:34px;height:34px;border:none;background:transparent;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    color:var(--ink-soft);cursor:pointer;transition:background .15s;
  }
  .eye:hover{ background:#F3EDE7; }

  .cap-row{ display:flex;gap:8px;margin-bottom:8px; }
  .cap-img{
    flex:1;height:52px;border-radius:12px;overflow:hidden;background:#fff;
    border:1.5px solid var(--line);display:flex;align-items:center;justify-content:center;
  }
  .cap-img img{ max-width:100%;max-height:100%;object-fit:contain;display:block; }
  .cap-btn{
    flex-shrink:0;width:52px;height:52px;border-radius:12px;
    border:1.5px solid var(--line);background:#fff;color:var(--ink-soft);
    display:flex;align-items:center;justify-content:center;cursor:pointer;
    transition:background .16s,color .16s;
  }
  .cap-btn:hover{ background:#F3EDE7;color:var(--maroon); }
  .cap-btn svg{ transition:transform .55s var(--ease); }
  .cap-btn.spin svg{ transform:rotate(-360deg); }

  .btn{
    position:relative;overflow:hidden;
    display:flex;align-items:center;justify-content:center;gap:8px;
    width:100%;box-sizing:border-box;padding:14px 18px;
    border:none;border-radius:12px;cursor:pointer;
    font-family:'Sora',sans-serif;font-size:15px;font-weight:600;color:#fff;
    background:linear-gradient(120deg,var(--maroon-lit) 0%,var(--maroon) 50%,var(--plum) 100%);
    box-shadow:0 8px 20px -8px rgba(127,13,13,.7);
    transition:transform .15s,box-shadow .2s,filter .2s;
  }
  .btn:hover{ transform:translateY(-2px);filter:brightness(1.08);
    box-shadow:0 14px 28px -10px rgba(127,13,13,.8); }
  .btn:active{ transform:translateY(0) scale(.99); }
  .btn[disabled]{ opacity:.7;cursor:not-allowed;transform:none; }
  .btn::after{
    content:'';position:absolute;top:0;left:-120%;width:50%;height:100%;
    transform:skewX(-22deg);
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.28),transparent);
  }
  .btn:hover::after{ animation:sheen .8s ease; }
  @keyframes sheen{ to{ left:150%; } }

  .chk{ width:16px;height:16px;border-radius:4px;cursor:pointer;accent-color:var(--maroon); }

  .alert{
    display:flex;gap:9px;align-items:flex-start;
    padding:11px 13px;border-radius:12px;margin-bottom:14px;
    background:#FCEEF0;border:1px solid #F2D2D8;color:#8F1F31;
    font-size:13px;line-height:1.5;
    animation:shake .42s cubic-bezier(.36,.07,.19,.97);
  }
  @keyframes shake{
    10%,90%{transform:translateX(-1px)}20%,80%{transform:translateX(2px)}
    30%,50%,70%{transform:translateX(-3px)}40%,60%{transform:translateX(3px)}
  }

  .wIn{ opacity:0;transform:translateY(16px);animation:wUp .75s var(--ease) forwards; }
  @keyframes wUp{ to{opacity:1;transform:translateY(0)} }
  .w1{animation-delay:.15s}.w2{animation-delay:.30s}
  .w3{animation-delay:.44s}.w4{animation-delay:.58s}

  /* tombol kembali: desktop pill kanan atas, mobile tombol X di sheet */
  .back{
    position:absolute; top:22px; right:26px; z-index:40;
    display:none; align-items:center; gap:7px;
    padding:8px 15px; border-radius:999px; cursor:pointer;
    border:1.5px solid var(--line); background:#fff;
    font-size:12.5px; font-weight:600; color:var(--ink-soft);
    transition:color .18s, border-color .18s;
  }
  .open .back{ display:flex; }
  .back:hover{ color:var(--maroon); border-color:var(--maroon); }
  @media (max-width:1023px){ .open .back{ display:none; } }

  .sheet-close{
    display:none;
    position:absolute; top:12px; right:14px;
    width:34px; height:34px; border-radius:50%;
    border:none; background:#F1EAE3; color:var(--ink-soft);
    align-items:center; justify-content:center; cursor:pointer;
  }
  @media (max-width:1023px){ .sheet-close{ display:flex; } }

  @media (prefers-reduced-motion:reduce){
    .orb,.live{animation:none!important}
    .wIn{animation-duration:.01ms!important;opacity:1!important;transform:none!important}
    .panel,.welcome,.side,.form-col,.scrim{ transition-duration:.01ms!important; }
    .btn:hover::after{animation:none}
  }
</style>

<div class="auth">
  <div class="wrap" id="wrap">

    {{-- ===== PANEL ===== --}}
    <div class="panel">
      <div class="orb orb-a"></div>
      <div class="orb orb-b"></div>
      <div class="orb orb-c"></div>
      <div class="ring ring-1"></div>
      <div class="ring ring-2"></div>

      <svg class="curve" viewBox="0 0 92 800" preserveAspectRatio="none" fill="currentColor">
        <path d="M92,0 L92,800 L42,800 C72,650 82,520 82,400 C82,280 72,150 42,0 Z"/>
      </svg>

      {{-- ---------- STATE 1: WELCOME ---------- --}}
      <div class="welcome" id="welcome">

        <img src="{{ asset('assets/logoapk2.png') }}" alt="Logo MSK Pintar" class="logo-hero wIn w1">

        <span class="chip wIn w1" style="margin-bottom:22px">
          <span class="live"></span> Knowledge Portal
        </span>

        <h1 class="display text-white text-[32px] sm:text-[44px] font-extrabold leading-[1.1] mb-4 wIn w2">
          Selamat Datang<br>Kembali
        </h1>

        <p class="text-[14.5px] sm:text-[15px] leading-relaxed max-w-md mb-8 wIn w3" style="color:rgba(255,255,255,.75)">
          Pusat pengetahuan PT Mitra Sendang Kemakmuran.
          Masuk untuk mengakses kebijakan, SOP, dan panduan kerja perusahaan.
        </p>

        <button type="button" class="btn-ghost wIn w4" id="btn-open">
          Masuk
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </button>
      </div>

      {{-- ---------- STATE 2: SIDE (desktop) ---------- --}}
      <div class="side">
        <div>
          <span class="chip"><span class="live"></span> Knowledge Portal</span>
        </div>

        <div>
          <h2 class="display text-white text-[32px] xl:text-[38px] font-extrabold leading-[1.12] mb-4">
            Satu Tempat<br>Segala Pengetahuan
          </h2>
          <div class="w-12 h-[3px] rounded-full mb-5" style="background:var(--gold)"></div>
          <p class="text-[14px] leading-relaxed max-w-sm" style="color:rgba(255,255,255,.7)">
            Akses kebijakan, SOP, panduan kerja, dan basis pengetahuan
            perusahaan dalam satu platform terintegrasi.
          </p>
        </div>

        <div class="space-y-3">
          <div class="feat">
            <span class="feat-ico">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </span>
            Dokumen terpusat &amp; terklasifikasi
          </div>
          <div class="feat">
            <span class="feat-ico">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </span>
            Akses berjenjang sesuai grade
          </div>
          <div class="feat">
            <span class="feat-ico">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3C7.7 6.2 6 8.4 6 11v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1"/>
              </svg>
            </span>
            Notifikasi real-time ke perangkat
          </div>
        </div>
      </div>
    </div>

    {{-- scrim mobile --}}
    <div class="scrim" id="scrim"></div>

    {{-- tombol kembali (desktop) --}}
    <button type="button" class="back" id="btn-back">
      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
      </svg>
      Kembali
    </button>

    {{-- ===== FORM ===== --}}
    <div class="form-col" id="sheet">

      <button type="button" class="sheet-close" id="btn-close-sheet" aria-label="Tutup">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <div class="form-box">

        <div class="sheet-grip"></div>

        <div class="mb-5 text-center">
          <img src="{{ asset('assets/logoapk2.png') }}" alt="Logo MSK Pintar" class="logo-form">
          <h2 class="display text-[22px] font-bold leading-tight mb-1.5">Masuk ke Akun Anda</h2>
          <p class="text-[13px]" style="color:var(--ink-soft)">
            Gunakan ID Karyawan yang terdaftar di HRD
          </p>
        </div>

        @if ($errors->any())
          <div class="alert">
            <svg class="w-4 h-4 flex-shrink-0 mt-[2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form">
          @csrf

          <div class="mb-3">
            <label for="employee_id" class="lbl">ID Karyawan</label>
            <div class="fw">
              <svg class="f-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <input type="text" name="employee_id" id="employee_id"
                     value="{{ old('employee_id') }}" placeholder="102***"
                     maxlength="12" autocomplete="username"
                     oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-_.]/g,'')"
                     class="field @error('employee_id') err @enderror" />
            </div>
            @error('employee_id')<p class="text-[12px] mt-1" style="color:#B3344A">{{ $message }}</p>@enderror
          </div>

          <div class="mb-3">
            <label for="password" class="lbl">Password</label>
            <div class="fw">
              <svg class="f-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              <input type="password" name="password" id="password" placeholder="Masukkan password"
                     autocomplete="current-password"
                     class="field field-pw @error('password') err @enderror" />
              <button type="button" onclick="togglePassword()" class="eye" aria-label="Tampilkan password">
                <svg id="eye-open" width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg id="eye-off" class="hidden" width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M3 3l18 18"/>
                </svg>
              </button>
            </div>
            @error('password')<p class="text-[12px] mt-1" style="color:#B3344A">{{ $message }}</p>@enderror
          </div>

          <div class="mb-3.5">
            <label for="captcha" class="lbl">Kode Verifikasi</label>
            <div class="cap-row">
              <div class="cap-img">
                <img id="captcha-img" src="{{ captcha_src('flat') }}" alt="Kode captcha">
              </div>
              <button type="button" class="cap-btn" id="cap-btn" onclick="refreshCaptcha()" aria-label="Ganti kode">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
              </button>
            </div>
            <input type="text" name="captcha" id="captcha" required autocomplete="off"
                   placeholder="Ketik kode di atas"
                   class="field field-plain @error('captcha') err @enderror" />
            @error('captcha')<p class="text-[12px] mt-1" style="color:#B3344A">{{ $message }}</p>@enderror
          </div>

          <div class="flex items-center justify-between mb-4">
            <label class="flex items-center gap-2 cursor-pointer select-none">
              <input type="checkbox" name="remember" id="remember" class="chk">
              <span class="text-[13px]" style="color:var(--ink-soft)">Ingat saya</span>
            </label>
            </div>

          <button type="submit" class="btn" id="submit-btn">
            <span id="btn-label">Masuk</span>
            <svg id="btn-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
            <svg id="btn-spin" class="hidden animate-spin" width="16" height="16" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </button>

        </form>

        <p class="text-center text-[10.5px] mt-5 leading-relaxed" style="color:#B0A49C">
          &copy; {{ date('Y') }} PT Mitra Sendang Kemakmuran &nbsp;&middot;&nbsp;
        </p>

      </div>
    </div>

  </div>
</div>

<script>
(function(){
  const wrap  = document.getElementById('wrap');
  const sheet = document.getElementById('sheet');
  const scrim = document.getElementById('scrim');
  if(!wrap) return;

  const isMobile = () => window.innerWidth < 1024;

  function openForm(){
    wrap.classList.add('open');
    if(isMobile()) document.body.style.overflow = 'hidden';
    setTimeout(function(){
      if(!isMobile()){
        const f = document.getElementById('employee_id');
        if(f) f.focus();
      }
    }, 900);
  }
  function closeForm(){
    wrap.classList.remove('open');
    document.body.style.overflow = '';
  }

  const bo = document.getElementById('btn-open');
  const bb = document.getElementById('btn-back');
  const bc = document.getElementById('btn-close-sheet');
  if(bo) bo.addEventListener('click', openForm);
  if(bb) bb.addEventListener('click', closeForm);
  if(bc) bc.addEventListener('click', closeForm);
  if(scrim) scrim.addEventListener('click', closeForm);

  @if ($errors->any() || old('employee_id'))
    wrap.classList.add('open');
    if(isMobile()) document.body.style.overflow = 'hidden';
  @endif

  // ===== Swipe ke bawah untuk menutup sheet (mobile) =====
  let startY = 0, currentY = 0, dragging = false;

  sheet.addEventListener('touchstart', function(e){
    if(!isMobile() || !wrap.classList.contains('open')) return;
    if(sheet.scrollTop > 0) return;          // biarkan scroll normal dulu
    startY = e.touches[0].clientY;
    dragging = true;
    sheet.style.transition = 'none';
  }, {passive:true});

  sheet.addEventListener('touchmove', function(e){
    if(!dragging) return;
    currentY = e.touches[0].clientY - startY;
    if(currentY > 0) sheet.style.transform = 'translateY(' + currentY + 'px)';
  }, {passive:true});

  sheet.addEventListener('touchend', function(){
    if(!dragging) return;
    dragging = false;
    sheet.style.transition = '';
    sheet.style.transform = '';
    if(currentY > 110) closeForm();
    currentY = 0;
  });

  // ===== Helpers =====
  window.togglePassword = function(){
    const i = document.getElementById('password');
    const on = document.getElementById('eye-open');
    const off = document.getElementById('eye-off');
    const show = i.type === 'password';
    i.type = show ? 'text' : 'password';
    on.classList.toggle('hidden', show);
    off.classList.toggle('hidden', !show);
  };

  window.refreshCaptcha = function(){
    const b = document.getElementById('cap-btn');
    b.classList.add('spin');
    setTimeout(function(){ b.classList.remove('spin'); }, 560);

    fetch("{{ route('captcha.refresh') }}")
      .then(function(r){ return r.json(); })
      .then(function(d){
        document.getElementById('captcha-img').src = d.captcha + '&v=' + Date.now();
        document.getElementById('captcha').value = '';
      })
      .catch(function(e){ console.error(e); });
  };

  const lf = document.getElementById('login-form');
  if(lf) lf.addEventListener('submit', function(){
    const b = document.getElementById('submit-btn');
    b.disabled = true;
    document.getElementById('btn-label').textContent = 'Memproses';
    document.getElementById('btn-arrow').classList.add('hidden');
    document.getElementById('btn-spin').classList.remove('hidden');
  });
})();
</script>
@endsection