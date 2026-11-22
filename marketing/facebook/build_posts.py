"""
Builds the 5 Facebook post designs (1080x1080 PNG) for EazyTech Solution.

    python marketing/facebook/build_posts.py

Needs Google Chrome or Edge installed (used headless to render HTML -> PNG).
Edit the TEXT / CONTACT values below, run again, and the PNGs are regenerated.
"""
import os, subprocess, sys, pathlib

HERE = pathlib.Path(__file__).resolve().parent
SRC = HERE / "source"
OUT = HERE
ROOT = HERE.parent.parent
LOGO = (ROOT / "public/images/logo-dark.png").as_uri()

CONTACT_WEB = "eazytechsolution.com"
CONTACT_PHONE = "+880 1700-000000"          # placeholder - replace with the real number
CONTACT_MAIL = "hello@eazytechsolution.com"

CSS = """
*{box-sizing:border-box;margin:0;padding:0}
html,body{width:1080px;height:1080px;overflow:hidden;background:#070b18}
body{font-family:'Inter','Segoe UI',sans-serif;color:#eef2ff;position:relative;--o:#f28a2a;--y:#f2d624;--c:#ec5a5e;--t:#0db5bc}
.disp{font-family:'Space Grotesk','Segoe UI',sans-serif}
.bg{position:absolute;inset:0;background:
  radial-gradient(700px 520px at 92% 8%, color-mix(in srgb,var(--a1) 34%,transparent), transparent 70%),
  radial-gradient(760px 600px at 4% 98%, color-mix(in srgb,var(--a2) 30%,transparent), transparent 70%),
  #070b18}
.grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px);background-size:54px 54px;mask-image:radial-gradient(ellipse at 50% 45%,#000 30%,transparent 78%)}
.top{position:absolute;left:64px;right:64px;top:54px;display:flex;justify-content:space-between;align-items:center}
.top img{height:84px}
.pill{padding:10px 22px;border-radius:99px;border:1.5px solid color-mix(in srgb,var(--a1) 70%,transparent);color:var(--a1);background:color-mix(in srgb,var(--a1) 12%,transparent);font:700 17px 'Inter';letter-spacing:.16em;text-transform:uppercase}
h1{font:700 80px/1.03 'Space Grotesk','Segoe UI',sans-serif;letter-spacing:-.035em}
h1 em{font-style:normal;background:linear-gradient(95deg,var(--a1),var(--a2));-webkit-background-clip:text;background-clip:text;color:transparent}
.copy{position:absolute;left:64px;right:64px;top:158px}.sub{margin-top:24px;width:780px;font-size:27px;line-height:1.45;color:#aab4d0}
.vis{position:absolute;left:0;right:0;top:468px;height:430px}
.foot{position:absolute;left:0;right:0;bottom:0;height:116px;padding:0 64px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(180deg,transparent,rgba(5,8,18,.92) 40%);}
.cta{padding:20px 34px;border-radius:99px;background:linear-gradient(95deg,var(--a1),var(--a2));color:#0a0f1e;font:700 24px 'Space Grotesk';box-shadow:0 18px 40px color-mix(in srgb,var(--a1) 40%,transparent)}
.contact{text-align:right;font-size:20px;color:#c4cce6;line-height:1.5}.contact b{color:#fff;font-weight:700}
/* shared mock pieces */
.card{background:linear-gradient(160deg,rgba(255,255,255,.10),rgba(255,255,255,.035));border:1.5px solid rgba(255,255,255,.16);border-radius:28px;backdrop-filter:blur(10px);box-shadow:0 30px 70px rgba(0,0,0,.45)}
.abs{position:absolute}
.chip{display:inline-flex;align-items:center;gap:10px;padding:12px 20px;border-radius:99px;font:600 20px 'Inter';background:rgba(10,15,32,.88);border:1.5px solid rgba(255,255,255,.2);box-shadow:0 14px 34px rgba(0,0,0,.4)}
.dot{width:12px;height:12px;border-radius:50%}
.bar{border-radius:8px;background:rgba(255,255,255,.14)}
svg{display:block}
"""

HEAD = """<!doctype html><html><head><meta charset="utf-8">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
<style>%CSS%</style></head><body style="--a1:%A1%;--a2:%A2%">
<div class="bg"></div><div class="grid"></div>
<div class="top"><img src="%LOGO%"><div class="pill">%TAG%</div></div>
"""
FOOT = """<div class="foot"><div class="cta">%CTA% &rarr;</div>
<div class="contact"><b>%WEB%</b><br>%PHONE% &middot; %MAIL%</div></div></body></html>"""


def page(a1, a2, tag, h1, sub, vis, cta):
    return (HEAD.replace("%CSS%", CSS).replace("%A1%", a1).replace("%A2%", a2)
            .replace("%LOGO%", LOGO).replace("%TAG%", tag)
            + f'<div class="copy"><h1>{h1}</h1><div class="sub">{sub}</div></div><div class="vis">{vis}</div>'
            + FOOT.replace("%CTA%", cta).replace("%WEB%", CONTACT_WEB)
                  .replace("%PHONE%", CONTACT_PHONE).replace("%MAIL%", CONTACT_MAIL))


# ───────────────────────── 1 · Websites & web apps ─────────────────────────
vis1 = """
<div class="card abs" style="left:64px;top:10px;width:720px;height:410px;overflow:hidden">
  <div style="height:54px;display:flex;align-items:center;gap:10px;padding:0 22px;border-bottom:1.5px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05)">
    <span class="dot" style="background:#ec5a5e"></span><span class="dot" style="background:#f2d624"></span><span class="dot" style="background:#0db5bc"></span>
    <div style="margin-left:18px;flex:1;height:30px;border-radius:99px;background:rgba(255,255,255,.09);font:500 16px Inter;color:#9fb0d6;display:flex;align-items:center;padding:0 16px">https://yourbusiness.com</div></div>
  <div style="padding:26px 30px;display:grid;grid-template-columns:1.1fr 1fr;gap:26px">
    <div>
      <div class="bar" style="width:58%;height:14px;background:var(--a1);opacity:.9"></div>
      <div class="bar" style="width:100%;height:30px;margin-top:20px;background:rgba(255,255,255,.85)"></div>
      <div class="bar" style="width:82%;height:30px;margin-top:12px;background:rgba(255,255,255,.85)"></div>
      <div class="bar" style="width:92%;height:12px;margin-top:22px"></div><div class="bar" style="width:70%;height:12px;margin-top:10px"></div>
      <div style="margin-top:26px;display:flex;gap:12px"><div style="width:140px;height:44px;border-radius:99px;background:linear-gradient(95deg,var(--a1),var(--a2))"></div><div style="width:110px;height:44px;border-radius:99px;border:2px solid rgba(255,255,255,.3)"></div></div>
    </div>
    <div style="border-radius:20px;background:linear-gradient(150deg,color-mix(in srgb,var(--a1) 50%,transparent),color-mix(in srgb,var(--a2) 35%,transparent));padding:20px;display:flex;align-items:flex-end;gap:12px;height:228px">
      <div style="flex:1;height:34%;border-radius:8px 8px 0 0;background:rgba(255,255,255,.55)"></div><div style="flex:1;height:52%;border-radius:8px 8px 0 0;background:rgba(255,255,255,.6)"></div>
      <div style="flex:1;height:44%;border-radius:8px 8px 0 0;background:rgba(255,255,255,.65)"></div><div style="flex:1;height:72%;border-radius:8px 8px 0 0;background:rgba(255,255,255,.8)"></div>
      <div style="flex:1;height:100%;border-radius:8px 8px 0 0;background:#fff"></div></div>
  </div>
  <div style="padding:0 30px;display:flex;gap:18px"><div class="bar" style="flex:1;height:64px"></div><div class="bar" style="flex:1;height:64px"></div><div class="bar" style="flex:1;height:64px"></div></div>
</div>
<div class="card abs" style="right:64px;top:0;width:210px;padding:24px;text-align:center">
  <svg width="130" height="130" viewBox="0 0 130 130" style="margin:0 auto"><circle cx="65" cy="65" r="54" fill="none" stroke="rgba(255,255,255,.14)" stroke-width="12"/>
  <circle cx="65" cy="65" r="54" fill="none" stroke="#0db5bc" stroke-width="12" stroke-linecap="round" stroke-dasharray="333" stroke-dashoffset="12" transform="rotate(-90 65 65)"/>
  <text x="65" y="76" text-anchor="middle" font-family="Space Grotesk" font-weight="700" font-size="38" fill="#fff">98</text></svg>
  <div style="font:600 19px Inter;margin-top:12px;color:#c4cce6">Speed score</div></div>
<div class="card abs" style="right:92px;top:196px;width:250px;padding:18px 24px"><div style="font:500 17px Inter;color:#9fb0d6">New leads / month</div>
  <div class="disp" style="font:700 52px 'Space Grotesk';letter-spacing:-.03em;margin-top:6px">+248%</div>
  <svg width="190" height="40" viewBox="0 0 190 40" style="margin-top:8px"><polyline points="2,34 40,28 74,30 110,16 148,18 188,3" fill="none" stroke="#f28a2a" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
<div class="chip abs" style="right:64px;top:384px"><span class="dot" style="background:#0db5bc"></span>Mobile-first &amp; SEO ready</div>
"""
p1 = page("#0db5bc", "#5fe0e6", "Web Development",
          "Your website should <em>sell</em> while you sleep.",
          "Fast, modern websites &amp; web apps built to turn visitors into customers &mdash; not just look pretty.",
          vis1, "Get a free quote")

# ───────────────────────── 2 · E-commerce ─────────────────────────
def prod(c1, c2, name, price, icon):
    return f"""<div class="card" style="width:198px;padding:14px;border-radius:26px">
  <div style="height:150px;border-radius:18px;background:linear-gradient(150deg,{c1},{c2});display:grid;place-items:center">{icon}</div>
  <div style="font:600 21px Inter;margin-top:14px">{name}</div>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px"><b class="disp" style="font:700 26px 'Space Grotesk'">{price}</b>
  <span style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#f28a2a,#ec5a5e);display:grid;place-items:center;font:700 28px Inter;color:#0a0f1e">+</span></div></div>"""
bag = '<svg width="86" height="86" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>'
shoe = '<svg width="96" height="86" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16h20v2H2z"/><path d="M3 16c0-5 3-7 5-7l3 3c3 0 5 1 7 3"/></svg>'
watch = '<svg width="86" height="86" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><path d="M9 7l.6-4h4.8L15 7M9 17l.6 4h4.8l.6-4M12 10v2l1.5 1"/></svg>'
vis2 = f"""
<div class="abs" style="left:64px;top:0;display:flex;gap:16px">
  {prod('#f28a2a','#ec5a5e','Leather Tote','$48',bag)}
  {prod('#0db5bc','#2b6cb0','Run Sneaker','$72',shoe)}
  {prod('#ec5a5e','#7b3fa0','Smart Watch','$95',watch)}
</div>
<div class="card abs" style="right:64px;top:0;width:290px;height:350px;padding:22px;border-radius:30px">
  <div style="display:flex;justify-content:space-between;align-items:center"><b class="disp" style="font:700 24px 'Space Grotesk'">Your cart</b><span style="background:#ec5a5e;border-radius:99px;padding:3px 14px;font:700 17px Inter">3</span></div>
  <div style="margin-top:16px;display:grid;gap:12px">
    <div style="display:flex;gap:12px;align-items:center"><div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(150deg,#f28a2a,#ec5a5e)"></div><div class="bar" style="flex:1;height:12px"></div><b style="font:600 18px Inter">$48</b></div>
    <div style="display:flex;gap:12px;align-items:center"><div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(150deg,#0db5bc,#2b6cb0)"></div><div class="bar" style="flex:1;height:12px"></div><b style="font:600 18px Inter">$72</b></div>
    <div style="display:flex;gap:12px;align-items:center"><div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(150deg,#ec5a5e,#7b3fa0)"></div><div class="bar" style="flex:1;height:12px"></div><b style="font:600 18px Inter">$95</b></div></div>
  <div style="margin:18px 0 14px;border-top:1.5px dashed rgba(255,255,255,.2)"></div>
  <div style="display:flex;justify-content:space-between;font:600 20px Inter"><span style="color:#9fb0d6">Total</span><b class="disp" style="font:700 30px 'Space Grotesk'">$215</b></div>
  <div style="margin-top:16px;height:50px;border-radius:99px;background:linear-gradient(95deg,#f28a2a,#ec5a5e);display:grid;place-items:center;font:700 20px 'Space Grotesk';color:#0a0f1e">Checkout</div></div>
<div class="chip abs" style="left:64px;top:300px"><span class="dot" style="background:#0db5bc"></span>New order #1042 &middot; $86 paid</div>
<div class="abs" style="left:64px;top:362px;display:flex;gap:12px;font:600 18px Inter;color:#c4cce6">
  <span class="chip" style="padding:8px 16px;font-size:18px">bKash</span><span class="chip" style="padding:8px 16px;font-size:18px">Nagad</span><span class="chip" style="padding:8px 16px;font-size:18px">Cards</span><span class="chip" style="padding:8px 16px;font-size:18px">Cash on delivery</span></div>
"""
p2 = page("#f28a2a", "#f2d624", "E-commerce",
          "Open your <em>online store</em> and start selling 24/7.",
          "Beautiful stores with local payments, courier tracking and an admin panel you can run from your phone.",
          vis2, "Launch my store")

# ───────────────────────── 3 · Meta Ads ─────────────────────────
vis3 = """
<div class="card abs" style="left:64px;top:0;width:400px;height:430px;padding:0;overflow:hidden;border-radius:30px">
  <div style="padding:18px 20px;display:flex;gap:12px;align-items:center"><div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#f28a2a,#ec5a5e)"></div>
    <div><b style="font:700 19px Inter">Your Brand</b><div style="font:400 15px Inter;color:#9fb0d6">Sponsored &middot; <span style="color:#9fb0d6">&#127760;</span></div></div></div>
  <div style="padding:0 20px 14px;font:500 18px/1.4 Inter;color:#dfe6fb">Summer sale is LIVE &mdash; up to 40% off. Tap to shop before it ends!</div>
  <div style="height:150px;background:linear-gradient(135deg,#ec5a5e,#f2d624);position:relative;display:grid;place-items:center">
    <div class="disp" style="font:700 64px 'Space Grotesk';color:#0a0f1e;letter-spacing:-.03em">40% OFF</div>
    <div style="position:absolute;right:-30px;top:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.28)"></div></div>
  <div style="padding:14px 20px;display:flex;justify-content:space-between;align-items:center;background:rgba(255,255,255,.06)"><div><div style="font:500 14px Inter;color:#9fb0d6">YOURBRAND.COM</div><b style="font:700 19px Inter">Shop the collection</b></div>
    <div style="padding:10px 18px;border-radius:10px;background:rgba(255,255,255,.18);font:700 17px Inter">Shop now</div></div>
  <div style="padding:12px 20px;display:flex;gap:26px;font:600 17px Inter;color:#9fb0d6"><span>&#128077; Like</span><span>&#128172; Comment</span><span>&#8618; Share</span></div>
</div>
<div class="abs" style="left:496px;top:0;width:520px;display:grid;grid-template-columns:1fr 1fr;gap:18px">
  <div class="card" style="padding:22px"><div style="font:500 17px Inter;color:#9fb0d6">Cost per lead</div><div class="disp" style="font:700 54px 'Space Grotesk';letter-spacing:-.03em;color:#0db5bc;margin-top:4px">&minus;62%</div></div>
  <div class="card" style="padding:22px"><div style="font:500 17px Inter;color:#9fb0d6">Return on ad spend</div><div class="disp" style="font:700 54px 'Space Grotesk';letter-spacing:-.03em;color:#f2d624;margin-top:4px">4.8&times;</div></div>
  <div class="card" style="grid-column:1/3;padding:22px 26px">
    <div style="display:flex;justify-content:space-between"><span style="font:500 17px Inter;color:#9fb0d6">Leads per week</span><b class="disp" style="font:700 28px 'Space Grotesk'">1,240</b></div>
    <div style="display:flex;align-items:flex-end;gap:14px;height:130px;margin-top:14px">
      <div style="flex:1;height:26%;border-radius:8px 8px 0 0;background:rgba(255,255,255,.2)"></div><div style="flex:1;height:38%;border-radius:8px 8px 0 0;background:rgba(255,255,255,.28)"></div>
      <div style="flex:1;height:34%;border-radius:8px 8px 0 0;background:rgba(255,255,255,.34)"></div><div style="flex:1;height:56%;border-radius:8px 8px 0 0;background:rgba(236,90,94,.7)"></div>
      <div style="flex:1;height:74%;border-radius:8px 8px 0 0;background:rgba(236,90,94,.85)"></div><div style="flex:1;height:100%;border-radius:8px 8px 0 0;background:linear-gradient(180deg,#f2d624,#ec5a5e)"></div></div></div>
  <div class="chip" style="grid-column:1/3;justify-content:center"><span class="dot" style="background:#ec5a5e"></span>Facebook &middot; Instagram &middot; Messenger</div>
</div>
"""
p3 = page("#f2d624", "#ec5a5e", "Meta Ads",
          "Stop boosting posts. Start <em>scaling</em> with ads.",
          "Data-driven Facebook &amp; Instagram campaigns that cut your cost per lead and bring real buyers.",
          vis3, "Book a free ad audit")

# ───────────────────────── 4 · Business software ─────────────────────────
def mod(color, name, stat, svg):
    return f"""<div class="card" style="padding:20px 22px;border-radius:24px;display:flex;gap:16px;align-items:center">
  <div style="width:58px;height:58px;border-radius:18px;display:grid;place-items:center;background:color-mix(in srgb,{color} 18%,transparent);flex:none">
  <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{color}" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">{svg}</svg></div>
  <div><b class="disp" style="font:700 24px 'Space Grotesk'">{name}</b><div style="font:500 17px Inter;color:#9fb0d6;margin-top:2px">{stat}</div></div></div>"""
vis4 = f"""
<div class="abs" style="left:64px;top:0;width:620px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
  {mod('#0db5bc','ERP','Sales &middot; Stock &middot; Purchase','<path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/>')}
  {mod('#f2d624','Accounting','Invoices &middot; VAT &middot; Reports','<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 12h2M12 12h2M16 12h0M8 16h2M12 16h2"/>')}
  {mod('#ec5a5e','HR &amp; Payroll','Attendance &middot; Salary &middot; Leave','<circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-4 3-6 7-6s7 2 7 6M17 5a3 3 0 0 1 0 6M22 20c0-3-2-5-4-5.5"/>')}
  {mod('#f28a2a','Shop POS','Barcode &middot; Receipts &middot; Offline','<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>')}
  {mod('#0db5bc','Pharmacy','Batches &middot; Expiry alerts','<path d="M10.5 20.5l-7-7a4.9 4.9 0 0 1 7-7l7 7a4.9 4.9 0 0 1-7 7zM8 8l8 8"/>')}
  {mod('#ec5a5e','Inventory','Multi-branch stock control','<path d="M3 7l9-4 9 4-9 4zM3 7v10l9 4 9-4V7M12 11v10"/>')}
</div>
<div class="card abs" style="right:64px;top:0;width:300px;height:412px;padding:26px;border-radius:30px">
  <div style="font:500 18px Inter;color:#9fb0d6">Today&rsquo;s revenue</div>
  <div class="disp" style="font:700 56px 'Space Grotesk';letter-spacing:-.03em;margin-top:4px">$12.4k</div>
  <div style="display:inline-block;margin-top:6px;padding:5px 14px;border-radius:99px;background:rgba(13,181,188,.2);color:#0db5bc;font:700 17px Inter">&#9650; 18.2%</div>
  <svg width="248" height="130" viewBox="0 0 248 130" style="margin-top:20px"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#0db5bc" stop-opacity=".55"/><stop offset="1" stop-color="#0db5bc" stop-opacity="0"/></linearGradient></defs>
    <path d="M0 104 L40 88 L82 96 L124 56 L166 66 L206 28 L248 12 L248 130 L0 130Z" fill="url(#g)"/><polyline points="0,104 40,88 82,96 124,56 166,66 206,28 248,12" fill="none" stroke="#0db5bc" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></svg>
  <div style="display:flex;justify-content:space-between;margin-top:22px;font:600 17px Inter"><span style="color:#9fb0d6">Orders</span><b>186</b></div>
  <div style="display:flex;justify-content:space-between;margin-top:10px;font:600 17px Inter"><span style="color:#9fb0d6">Low stock</span><b style="color:#f2d624">7 items</b></div></div>
"""
p4 = page("#0db5bc", "#f2d624", "Business Software",
          "Run your whole business from <em>one dashboard.</em>",
          "ERP, accounting, HR &amp; payroll, shop POS and pharmacy software &mdash; custom built around how you work.",
          vis4, "See a free demo")

# ───────────────────────── 5 · AI video & automation ─────────────────────────
wave = "".join(f'<span style="width:7px;border-radius:4px;background:linear-gradient(180deg,#f2d624,#ec5a5e);height:{h}px"></span>' for h in
               [14,26,40,22,48,30,18,44,34,52,26,16,38,46,24,32,50,20,36,28,14,42,30])
vis5 = f"""
<div class="abs" style="left:96px;top:-8px;width:262px;height:448px;border-radius:44px;border:3px solid rgba(255,255,255,.28);background:#0a0f1e;box-shadow:0 40px 90px rgba(0,0,0,.6);overflow:hidden">
  <div style="position:absolute;inset:0;background:linear-gradient(165deg,#ec5a5e 0%,#7b3fa0 55%,#0db5bc 100%)"></div>
  <div style="position:absolute;left:50%;top:14px;transform:translateX(-50%);width:80px;height:20px;border-radius:99px;background:#0a0f1e"></div>
  <div style="position:absolute;left:50%;top:150px;transform:translateX(-50%);width:84px;height:84px;border-radius:50%;background:rgba(255,255,255,.92);display:grid;place-items:center"><svg width="34" height="34" viewBox="0 0 24 24" fill="#0a0f1e"><path d="M8 5v14l11-7z"/></svg></div>
  <div style="position:absolute;left:18px;right:18px;top:290px;padding:12px 14px;border-radius:14px;background:rgba(10,15,32,.72);font:700 22px/1.2 'Space Grotesk';text-align:center">New collection<br><span style="color:#f2d624">drops today</span></div>
  <div style="position:absolute;left:20px;right:20px;bottom:30px;height:6px;border-radius:4px;background:rgba(255,255,255,.3)"><div style="width:62%;height:100%;border-radius:4px;background:#fff"></div></div></div>
<div class="card abs" style="left:398px;top:6px;width:586px;padding:24px 26px;border-radius:28px">
  <div style="display:flex;justify-content:space-between;align-items:center"><b class="disp" style="font:700 24px 'Space Grotesk'">AI video ad</b><span style="font:700 16px Inter;color:#0db5bc;padding:4px 14px;border-radius:99px;background:rgba(13,181,188,.16)">Rendered in 48h</span></div>
  <div style="display:flex;align-items:center;gap:6px;height:64px;margin-top:16px">{wave}</div>
  <div style="display:flex;gap:10px;margin-top:14px"><div style="flex:3;height:30px;border-radius:8px;background:linear-gradient(90deg,#ec5a5e,#f28a2a)"></div><div style="flex:2;height:30px;border-radius:8px;background:linear-gradient(90deg,#0db5bc,#2b6cb0)"></div><div style="flex:3;height:30px;border-radius:8px;background:linear-gradient(90deg,#f2d624,#f28a2a)"></div></div></div>
<div class="card abs" style="left:398px;top:212px;width:586px;padding:22px 26px;border-radius:28px">
  <div style="display:flex;gap:12px;align-items:flex-start"><div style="width:38px;height:38px;border-radius:50%;background:#ddd;flex:none;background:linear-gradient(135deg,#f28a2a,#ec5a5e)"></div>
    <div style="background:rgba(255,255,255,.12);padding:12px 18px;border-radius:6px 20px 20px 20px;font:500 19px/1.35 Inter">Hi! Is the blue kurta in stock in size M?</div></div>
  <div style="display:flex;gap:12px;align-items:flex-start;justify-content:flex-end;margin-top:12px">
    <div style="background:linear-gradient(95deg,#0db5bc,#2b9bb8);color:#06121c;padding:12px 18px;border-radius:20px 6px 20px 20px;font:600 19px/1.35 Inter;max-width:420px">Yes! 4 left. Want me to reserve one and send the payment link? &#9889;</div>
    <div style="width:38px;height:38px;border-radius:12px;background:#0a0f1e;border:1.5px solid #0db5bc;flex:none;display:grid;place-items:center;font:700 14px Inter;color:#0db5bc">AI</div></div></div>
<div class="chip abs" style="left:398px;top:408px"><span class="dot" style="background:#f2d624"></span>Replies in seconds &middot; 24/7</div>
<div class="chip abs" style="left:700px;top:408px"><span class="dot" style="background:#ec5a5e"></span>WhatsApp &middot; Messenger</div>
"""
p5 = page("#ec5a5e", "#f28a2a", "AI &amp; Automation",
          "<em>AI video ads</em> that sell.<br>Bots that reply 24/7.",
          "Scroll-stopping AI-generated videos plus chatbots that reply on Messenger &amp; WhatsApp around the clock.",
          vis5, "Talk to our team")

POSTS = [
    ("post-1-website-web-apps", p1),
    ("post-2-ecommerce", p2),
    ("post-3-meta-ads", p3),
    ("post-4-business-software", p4),
    ("post-5-ai-video-automation", p5),
]


def find_browser():
    for p in [r"C:\Program Files\Google\Chrome\Application\chrome.exe",
              r"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe",
              r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe",
              r"C:\Program Files\Microsoft\Edge\Application\msedge.exe"]:
        if os.path.exists(p):
            return p
    sys.exit("Chrome or Edge not found")


def main():
    SRC.mkdir(exist_ok=True)
    browser = find_browser()
    for name, html in POSTS:
        f = SRC / f"{name}.html"
        f.write_text(html, encoding="utf-8")
        png = OUT / f"{name}.png"
        subprocess.run([browser, "--headless=new", "--disable-gpu", "--hide-scrollbars",
                        "--force-device-scale-factor=1", "--window-size=1080,1080",
                        "--virtual-time-budget=10000", f"--screenshot={png}", f.as_uri()],
                       check=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        print("built", png.name)


if __name__ == "__main__":
    main()
