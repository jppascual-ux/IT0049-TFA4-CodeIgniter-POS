<style>
    :root { color-scheme: light dark; --bg:#f5f5f7; --ink:#1d1d1f; --muted:#6e6e73; --blue:#0071e3; }
    @media (prefers-color-scheme: dark) { :root { --bg:#000; --ink:#f5f5f7; --muted:#a1a1a6; --blue:#0a84ff; } }
    * { box-sizing: border-box; }
    body { margin:0; min-height:100vh; display:grid; place-items:center; padding:24px 16px; background:var(--bg); color:var(--ink);
           font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Inter","Segoe UI",Roboto,Arial,sans-serif; letter-spacing:-.022em; text-align:center;
           -webkit-font-smoothing:antialiased; }
    .code { font-size:96px; line-height:1; font-weight:700; letter-spacing:-.05em;
            background:linear-gradient(90deg,#2563eb,#6366f1,#e8467c); -webkit-background-clip:text; background-clip:text; color:transparent;
            animation:rise 1s cubic-bezier(.28,.11,.32,1) both; }
    h1 { margin:12px 0 0; font-size:32px; letter-spacing:-.03em; animation:rise 1s cubic-bezier(.28,.11,.32,1) .1s both; }
    p { max-width:36ch; margin:10px auto 0; color:var(--muted); font-size:17px; line-height:1.47; animation:rise 1s cubic-bezier(.28,.11,.32,1) .2s both; }
    a { display:inline-flex; align-items:center; min-height:44px; margin-top:26px; padding:0 22px; border-radius:980px; background:var(--blue); color:#fff;
        text-decoration:none; font-size:17px; animation:rise 1s cubic-bezier(.28,.11,.32,1) .3s both; }
    @keyframes rise { from { opacity:0; transform:translateY(20px); filter:blur(8px); } }
    @media (prefers-reduced-motion: reduce) { * { animation:none !important; } }
    @media (min-width:735px) { .code { font-size:140px; } h1 { font-size:40px; } }
</style>
