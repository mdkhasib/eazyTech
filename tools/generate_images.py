"""
Generates the default artwork used by the demo data (all SVG, crisp at any size):
  public/images/team/*.svg        illustrated portraits (team, users, testimonials)
  public/images/clients/*.svg     client logo marks
  public/images/portfolio/*.svg   case-study cover illustrations

Anything uploaded from the admin panel replaces these. Re-run with:  python tools/generate_images.py
"""
import os, random, math

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'public', 'images')
FONT = "Segoe UI, Inter, Helvetica, Arial, sans-serif"

PAL = {  # theme -> (accent, deep)
    'teal': ('#0db5bc', '#123a6b'), 'orange': ('#f28a2a', '#7a2b3a'), 'coral': ('#ec5a5e', '#4b2060'),
    'yellow': ('#f2d624', '#b5521e'), 'violet': ('#8b7cff', '#1a3a7a'), 'mint': ('#34d399', '#0c4a63'),
}


def save(rel, svg):
    path = os.path.join(ROOT, rel)
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(svg)


# ───────────────────────── portraits ─────────────────────────
SKIN = {'a': '#f6d2b4', 'b': '#e9b88f', 'c': '#d79d6f', 'd': '#b9784d', 'e': '#8a5636'}
HAIR = {'k': '#17151a', 'b': '#3a281d', 'r': '#6b3b22', 'g': '#4a4a55'}


def portrait(name, bg, skin, hair, hair_style, shirt, glasses=False, beard=False, scarf=None, uid=0):
    a, b = PAL[bg]
    sk, hc = SKIN[skin], HAIR[hair]
    sk_dark = {'a': '#e3b18d', 'b': '#cf9870', 'c': '#bb8153', 'd': '#9c6038', 'e': '#6e4228'}[skin]
    o = []
    o.append(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="400" height="400">')
    o.append(f'<defs><linearGradient id="bg{uid}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="{a}"/><stop offset="1" stop-color="{b}"/></linearGradient>'
             f'<radialGradient id="gl{uid}" cx=".3" cy=".25" r=".7"><stop offset="0" stop-color="#fff" stop-opacity=".35"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></radialGradient>'
             f'<linearGradient id="sh{uid}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="{shirt}"/><stop offset="1" stop-color="#000" stop-opacity=".35"/></linearGradient></defs>')
    o.append(f'<rect width="400" height="400" fill="url(#bg{uid})"/><rect width="400" height="400" fill="url(#gl{uid})"/>')
    # decorative puzzle-ish shapes
    o.append(f'<circle cx="340" cy="70" r="70" fill="#fff" opacity=".08"/><circle cx="50" cy="330" r="90" fill="#000" opacity=".10"/>'
             f'<rect x="288" y="290" width="70" height="70" rx="22" fill="#fff" opacity=".10" transform="rotate(18 323 325)"/>'
             f'<circle cx="64" cy="82" r="14" fill="#fff" opacity=".25"/><circle cx="92" cy="58" r="6" fill="#fff" opacity=".2"/>')
    # back hair
    if hair_style == 'long':
        o.append(f'<path d="M128 165 C112 270 132 312 158 322 L242 322 C268 312 288 270 272 165 C270 92 130 92 128 165Z" fill="{hc}"/>')
    if hair_style == 'bun':
        o.append(f'<circle cx="200" cy="72" r="32" fill="{hc}"/>')
    if scarf:
        o.append(f'<path d="M112 190 C100 90 150 54 200 54 C250 54 300 90 288 190 C296 250 318 292 340 340 L60 340 C82 292 104 250 112 190Z" fill="{scarf}"/>')
    # shoulders
    o.append(f'<path d="M30 400 C40 318 112 282 200 278 C288 282 360 318 370 400Z" fill="url(#sh{uid})"/>')
    if not scarf:
        o.append(f'<path d="M168 278 L200 328 L232 278 Z" fill="#f4f6fb"/><path d="M168 278 L200 328 L180 282Z" fill="#000" opacity=".12"/>')
        o.append(f'<path d="M150 284 L200 340 L176 280Z M250 284 L200 340 L224 280Z" fill="#000" opacity=".18"/>')
        # neck
        o.append(f'<path d="M176 218 L176 272 C176 292 224 292 224 272 L224 218Z" fill="{sk_dark}"/>')
    # (scarf neck handled after the face)
    # ears + head
    if not scarf:
        o.append(f'<ellipse cx="141" cy="178" rx="11" ry="17" fill="{sk}"/><ellipse cx="259" cy="178" rx="11" ry="17" fill="{sk}"/>')
    rx, ry = (50, 62) if scarf else (60, 72)
    o.append(f'<ellipse cx="200" cy="{175 if scarf else 168}" rx="{rx}" ry="{ry}" fill="{sk}"/>')
    # beard (under face features)
    if beard:
        o.append(f'<path d="M142 178 C138 244 168 268 200 268 C232 268 262 244 258 178 C248 214 228 226 200 226 C172 226 152 214 142 178Z" fill="{hc}"/>')
    if scarf:  # chin wrap + drape + fold lines
        o.append(f'<path d="M144 206 C150 252 176 266 200 266 C224 266 250 252 256 206 C274 252 296 300 312 345 L88 345 C104 300 126 252 144 206Z" fill="{scarf}"/>'
                 f'<path d="M144 206 C150 252 176 266 200 266 C224 266 250 252 256 206" fill="none" stroke="#000" stroke-opacity=".18" stroke-width="4"/>'
                 f'<path d="M186 270 C176 300 168 322 160 345 M214 270 C224 300 232 322 240 345 M200 272 C200 304 200 326 200 345" fill="none" stroke="#000" stroke-opacity=".14" stroke-width="3"/>')
    # front hair
    if hair_style == 'short':
        o.append(f'<path d="M138 156 C132 94 170 74 204 76 C246 78 270 104 262 156 C252 130 226 118 200 120 C172 122 148 130 138 156Z" fill="{hc}"/>')
    elif hair_style == 'side':
        o.append(f'<path d="M136 162 C128 92 172 70 208 74 C250 78 272 106 264 160 C258 132 232 108 190 112 C160 114 146 134 136 162Z" fill="{hc}"/>'
                 f'<path d="M136 162 C150 130 178 116 214 112 C196 126 168 134 148 170Z" fill="#fff" opacity=".08"/>')
    elif hair_style == 'long':
        o.append(f'<path d="M136 170 C124 96 170 72 206 76 C248 80 276 108 264 172 C260 134 236 112 198 114 C166 116 146 136 136 170Z" fill="{hc}"/>')
    elif hair_style == 'bun':
        o.append(f'<path d="M140 160 C136 100 172 84 204 86 C244 88 266 108 260 160 C250 132 228 122 200 124 C172 126 150 134 140 160Z" fill="{hc}"/>')
    elif hair_style == 'curly':
        for cx, cy, r in [(150, 118, 26), (178, 98, 28), (214, 94, 28), (246, 112, 28), (262, 142, 22), (138, 146, 22), (200, 108, 30)]:
            o.append(f'<circle cx="{cx}" cy="{cy}" r="{r}" fill="{hc}"/>')
    elif hair_style == 'scarf':
        o.append(f'<path d="M146 146 C158 108 242 108 254 146 C240 124 160 124 146 146Z" fill="{scarf}"/>'
                 f'<path d="M146 146 C158 108 242 108 254 146" fill="none" stroke="#000" stroke-opacity=".2" stroke-width="4"/>'
                 f'<path d="M120 120 C130 80 170 62 200 62 M280 120 C270 80 230 62 200 62" fill="none" stroke="#000" stroke-opacity=".14" stroke-width="3"/>')
    # eyes / brows / nose / mouth
    ey = 170 if not scarf else 176
    o.append(f'<ellipse cx="174" cy="{ey}" rx="7" ry="9" fill="#1b1b26"/><ellipse cx="226" cy="{ey}" rx="7" ry="9" fill="#1b1b26"/>'
             f'<circle cx="177" cy="{ey-3}" r="2.6" fill="#fff"/><circle cx="229" cy="{ey-3}" r="2.6" fill="#fff"/>')
    o.append(f'<path d="M160 {ey-20} Q174 {ey-27} 188 {ey-20}" fill="none" stroke="{hc}" stroke-width="5" stroke-linecap="round"/>'
             f'<path d="M212 {ey-20} Q226 {ey-27} 240 {ey-20}" fill="none" stroke="{hc}" stroke-width="5" stroke-linecap="round"/>')
    o.append(f'<path d="M200 {ey+6} C195 {ey+24} 192 {ey+30} 202 {ey+31}" fill="none" stroke="{sk_dark}" stroke-width="4" stroke-linecap="round"/>')
    my = ey + 50
    o.append(f'<path d="M178 {my} Q200 {my+22} 222 {my}" fill="#fff" stroke="#7a3030" stroke-width="4" stroke-linejoin="round" stroke-linecap="round"/>')
    o.append(f'<ellipse cx="156" cy="{ey+28}" rx="12" ry="7" fill="#ff7b7b" opacity=".22"/><ellipse cx="244" cy="{ey+28}" rx="12" ry="7" fill="#ff7b7b" opacity=".22"/>')
    if glasses:
        o.append(f'<rect x="153" y="{ey-14}" width="42" height="30" rx="12" fill="#fff" fill-opacity=".12" stroke="#1d2233" stroke-width="4"/>'
                 f'<rect x="205" y="{ey-14}" width="42" height="30" rx="12" fill="#fff" fill-opacity=".12" stroke="#1d2233" stroke-width="4"/>'
                 f'<path d="M195 {ey} L205 {ey}" stroke="#1d2233" stroke-width="4"/>')
    o.append('</svg>')
    return ''.join(o)


PEOPLE = {
    # slug: (bg, skin, hair, style, shirt, glasses, beard, scarf)
    'arif-rahman':     ('teal',   'b', 'k', 'short', '#1f3a5f', True,  False, None),
    'nusrat-jahan':    ('coral',  'b', 'k', 'long',  '#7b2f5e', False, False, None),
    'tanvir-hasan':    ('violet', 'c', 'k', 'side',  '#2b2f45', True,  True,  None),
    'maliha-sultana':  ('orange', 'a', 'k', 'scarf', '#2a2140', False, False, '#7b2d6b'),
    'sadia-karim':     ('mint',   'b', 'b', 'bun',   '#0f5a52', False, False, None),
    'sumon-das':       ('teal',   'd', 'k', 'short', '#8b3a2f', False, True,  None),
    'imran-hossain':   ('yellow', 'c', 'b', 'curly', '#264a6b', False, False, None),
    'farhana-akter':   ('coral',  'a', 'k', 'long',  '#134d58', True,  False, None),
    'rafi-ahmed':      ('orange', 'b', 'k', 'side',  '#4a2a6b', False, False, None),
    'jui-rahman':      ('violet', 'c', 'k', 'scarf', '#1d3a5a', False, False, '#0e6b73'),
    # testimonials / clients
    'kamal-uddin':     ('mint',   'd', 'g', 'short', '#2e3b4e', True,  True,  None),
    'dr-anika-rahman':    ('coral',  'a', 'k', 'scarf', '#33264f', True,  False, '#c2455a'),
    'olivia-grant':    ('yellow', 'a', 'r', 'long',  '#5a2a3a', False, False, None),
    'priya-menon':     ('teal',   'c', 'k', 'bun',   '#7a3b12', False, False, None),
}


# ───────────────────────── client logos ─────────────────────────
def logo(kind, theme, uid):
    a, b = PAL[theme]
    g = {
        'basket': '<path d="M52 92h96l-10 52a8 8 0 0 1-8 6H70a8 8 0 0 1-8-6z" fill="#fff"/><path d="M78 92c0-20 10-34 22-34s22 14 22 34" fill="none" stroke="#fff" stroke-width="9" stroke-linecap="round"/>',
        'cross': '<rect x="84" y="50" width="32" height="100" rx="10" fill="#fff"/><rect x="50" y="84" width="100" height="32" rx="10" fill="#fff"/>',
        'dumbbell': '<rect x="52" y="86" width="96" height="28" rx="6" fill="#fff"/><rect x="40" y="66" width="24" height="68" rx="9" fill="#fff"/><rect x="136" y="66" width="24" height="68" rx="9" fill="#fff"/>',
        'building': '<path d="M60 150V70l40-22 40 22v80z" fill="#fff"/><g fill="%s"><rect x="76" y="86" width="16" height="16" rx="3"/><rect x="108" y="86" width="16" height="16" rx="3"/><rect x="76" y="114" width="16" height="16" rx="3"/><rect x="108" y="114" width="16" height="16" rx="3"/></g>' % a,
        'leaf': '<path d="M52 148C48 90 90 52 150 50c2 60-34 100-98 98z" fill="#fff"/><path d="M60 142C86 108 110 88 140 62" stroke="%s" stroke-width="6" fill="none" stroke-linecap="round"/>' % a,
        'cap': '<path d="M100 56 30 90l70 34 70-34z" fill="#fff"/><path d="M60 110v26c0 10 80 10 80 0v-26l-40 20z" fill="#fff" opacity=".85"/><rect x="164" y="92" width="6" height="46" rx="3" fill="#fff"/>',
        'hanger': '<path d="M100 62a14 14 0 1 1 14 14c-8 2-14 6-14 14v8L38 134a10 10 0 0 0 6 18h112a10 10 0 0 0 6-18L100 98" fill="none" stroke="#fff" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/>',
        'mountain': '<path d="M26 150l52-84 30 44 18-24 48 64z" fill="#fff"/><circle cx="144" cy="64" r="12" fill="#fff" opacity=".8"/>',
    }[kind]
    return (f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="200" height="200"><defs>'
            f'<linearGradient id="l{uid}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="{a}"/><stop offset="1" stop-color="{b}"/></linearGradient></defs>'
            f'<rect width="200" height="200" rx="46" fill="url(#l{uid})"/><circle cx="165" cy="35" r="46" fill="#fff" opacity=".1"/>{g}</svg>')


CLIENT_LOGOS = {
    'freshcart-retail': ('basket', 'teal'), 'mediplus-pharmacy': ('cross', 'mint'), 'northwind-fitness': ('dumbbell', 'coral'),
    'urbannest-properties': ('building', 'orange'), 'spice-route-foods': ('leaf', 'yellow'), 'brightpath-academy': ('cap', 'violet'),
    'stylehub-fashion': ('hanger', 'coral'), 'alpine-logistics': ('mountain', 'teal'),
}


# ───────────────────────── portfolio covers ─────────────────────────
class S:
    def __init__(self, theme):
        self.a, self.b = PAL[theme]
        self.theme = theme
        self.o = []
        self.n = 0

    def add(self, s):
        self.o.append(s)

    def uid(self):
        self.n += 1
        return f'{self.theme}{self.n}'


def rr(x, y, w, h, r, fill, op=1, stroke=None, sw=2, extra=''):
    st = f' stroke="{stroke}" stroke-width="{sw}"' if stroke else ''
    return f'<rect x="{x:.1f}" y="{y:.1f}" width="{w:.1f}" height="{h:.1f}" rx="{r}" fill="{fill}" fill-opacity="{op}"{st} {extra}/>'


def tx(x, y, s, size=20, fill='#e8ecf7', weight=600, anchor='start', op=1):
    return f'<text x="{x:.1f}" y="{y:.1f}" font-family="{FONT}" font-size="{size}" font-weight="{weight}" fill="{fill}" fill-opacity="{op}" text-anchor="{anchor}">{s}</text>'


def bg(s, W=1600, H=1100):
    a, b = s.a, s.b
    s.add(f'<defs><linearGradient id="bg{s.theme}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="{b}"/><stop offset=".55" stop-color="{b}"/><stop offset="1" stop-color="{a}" stop-opacity=".55"/></linearGradient>'
          f'<filter id="blur" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="90"/></filter>'
          f'<filter id="shadow" x="-20%" y="-20%" width="140%" height="150%"><feDropShadow dx="0" dy="40" stdDeviation="34" flood-color="#000" flood-opacity=".55"/></filter>'
          f'<pattern id="grid" width="64" height="64" patternUnits="userSpaceOnUse"><path d="M64 0H0V64" fill="none" stroke="#fff" stroke-opacity=".06" stroke-width="2"/></pattern>'
          f'<radialGradient id="fade" cx=".5" cy=".5" r=".7"><stop offset=".3" stop-color="#fff"/><stop offset="1" stop-color="#000"/></radialGradient>'
          f'<mask id="m"><rect width="{W}" height="{H}" fill="url(#fade)"/></mask></defs>')
    s.add(f'<rect width="{W}" height="{H}" fill="url(#bg{s.theme})"/>')
    s.add(f'<circle cx="260" cy="180" r="320" fill="{a}" opacity=".55" filter="url(#blur)"/><circle cx="1400" cy="960" r="360" fill="{a}" opacity=".35" filter="url(#blur)"/>'
          f'<circle cx="1280" cy="140" r="220" fill="#ec5a5e" opacity=".18" filter="url(#blur)"/>')
    s.add(f'<rect width="{W}" height="{H}" fill="url(#grid)" mask="url(#m)"/>')
    rnd = random.Random(7)
    for _ in range(26):
        x, y, r = rnd.randint(40, W - 40), rnd.randint(40, H - 40), rnd.choice([3, 4, 5, 7])
        s.add(f'<circle cx="{x}" cy="{y}" r="{r}" fill="#fff" opacity="{rnd.choice([.15, .25, .4])}"/>')


def browser(s, x, y, w, h, url='app.eazytech.io'):
    s.add(f'<g filter="url(#shadow)">{rr(x, y, w, h, 28, "#0a0f1f", 1, "#ffffff", 2).replace("stroke=", "stroke-opacity=\".16\" stroke=")}</g>')
    s.add(rr(x, y, w, 52, 28, '#141b33', 1) + rr(x, y + 26, w, 26, 0, '#141b33', 1))
    for i, c in enumerate(['#ec5a5e', '#f2d624', '#34d399']):
        s.add(f'<circle cx="{x+32+i*26}" cy="{y+26}" r="8" fill="{c}"/>')
    s.add(rr(x + 150, y + 12, w * .42, 28, 14, '#fff', .07) + tx(x + 172, y + 32, url, 15, '#8c97b8', 500))
    return x, y + 52, w, h - 52


def sidebar(s, x, y, w, h, items, accent):
    s.add(rr(x, y, w, h, 0, '#0c1226', 1))
    s.add(rr(x + 22, y + 26, 40, 40, 12, accent, 1) + rr(x + 74, y + 36, 80, 12, 6, '#fff', .55) + rr(x + 74, y + 54, 50, 8, 4, '#fff', .22))
    for i, it in enumerate(items):
        yy = y + 106 + i * 54
        on = i == 0
        if on:
            s.add(rr(x + 14, yy - 6, w - 28, 44, 12, accent, .18) + rr(x + 14, yy + 2, 5, 28, 3, accent, 1))
        s.add(rr(x + 34, yy + 6, 22, 22, 7, accent if on else '#fff', 1 if on else .25))
        s.add(tx(x + 70, yy + 24, it, 17, '#fff', 600 if on else 500, op=1 if on else .6))


def kpi(s, x, y, w, h, label, value, delta, accent):
    s.add(rr(x, y, w, h, 20, '#fff', .05, '#fff', 1.5).replace('stroke=', 'stroke-opacity=".1" stroke='))
    s.add(rr(x + 20, y + 20, 38, 38, 12, accent, .22) + rr(x + 31, y + 31, 16, 16, 5, accent, 1))
    s.add(tx(x + 72, y + 36, label, 15, '#9aa6c6', 500) + tx(x + 20, y + 96, value, 36, '#fff', 700) + tx(x + w - 20, y + 96, delta, 16, '#34d399', 700, 'end'))


def linechart(s, x, y, w, h, accent, seed, title):
    rnd = random.Random(seed)
    s.add(rr(x, y, w, h, 20, '#fff', .05, '#fff', 1.5).replace('stroke=', 'stroke-opacity=".1" stroke='))
    s.add(tx(x + 24, y + 38, title, 18, '#fff', 700) + rr(x + w - 150, y + 18, 126, 30, 15, '#fff', .08) + tx(x + w - 87, y + 39, 'Last 30 days', 13, '#aab3d0', 600, 'middle'))
    for i in range(4):
        yy = y + 80 + i * (h - 120) / 3
        s.add(f'<line x1="{x+24}" y1="{yy:.1f}" x2="{x+w-24}" y2="{yy:.1f}" stroke="#fff" stroke-opacity=".07" stroke-width="2"/>')
    pts, n = [], 12
    v = .35
    for i in range(n):
        v = min(.95, max(.2, v + rnd.uniform(-.08, .16)))
        pts.append((x + 34 + i * (w - 68) / (n - 1), y + h - 34 - v * (h - 120)))
    d = ' '.join(f'{px:.1f},{py:.1f}' for px, py in pts)
    gid = s.uid()
    s.add(f'<defs><linearGradient id="a{gid}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="{accent}" stop-opacity=".5"/><stop offset="1" stop-color="{accent}" stop-opacity="0"/></linearGradient></defs>')
    s.add(f'<polygon points="{pts[0][0]:.1f},{y+h-34} {d} {pts[-1][0]:.1f},{y+h-34}" fill="url(#a{gid})"/>')
    s.add(f'<polyline points="{d}" fill="none" stroke="{accent}" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>')
    px, py = pts[-3]
    s.add(f'<circle cx="{px:.1f}" cy="{py:.1f}" r="11" fill="#0a0f1f" stroke="{accent}" stroke-width="5"/>')


def barchart(s, x, y, w, h, accent, seed, title):
    rnd = random.Random(seed)
    s.add(rr(x, y, w, h, 20, '#fff', .05, '#fff', 1.5).replace('stroke=', 'stroke-opacity=".1" stroke='))
    s.add(tx(x + 24, y + 38, title, 18, '#fff', 700))
    n = 9
    bw = (w - 60) / n
    for i in range(n):
        hh = rnd.uniform(.25, 1) * (h - 110)
        s.add(rr(x + 30 + i * bw + 4, y + h - 30 - hh, bw - 12, hh, 8, accent, .35 + .65 * (i / n)))


def donut(s, cx, cy, r, pct, accent, label):
    c = 2 * math.pi * r
    s.add(f'<circle cx="{cx}" cy="{cy}" r="{r}" fill="none" stroke="#fff" stroke-opacity=".1" stroke-width="22"/>')
    s.add(f'<circle cx="{cx}" cy="{cy}" r="{r}" fill="none" stroke="{accent}" stroke-width="22" stroke-linecap="round" stroke-dasharray="{c*pct:.1f} {c:.1f}" transform="rotate(-90 {cx} {cy})"/>')
    s.add(tx(cx, cy + 12, f'{int(pct*100)}%', 34, '#fff', 700, 'middle') + tx(cx, cy + 38, label, 14, '#9aa6c6', 500, 'middle'))


def table(s, x, y, w, rows, accent):
    s.add(rr(x, y, w, 62 + len(rows) * 56, 20, '#fff', .05, '#fff', 1.5).replace('stroke=', 'stroke-opacity=".1" stroke='))
    cols = [x + 28, x + w * .42, x + w * .66, x + w - 28]
    for i, hd in enumerate(['ITEM', 'REF', 'STATUS', 'AMOUNT']):
        s.add(tx(cols[i], y + 38, hd, 12, '#7c88aa', 700, 'end' if i == 3 else 'start'))
    for r, (a_, b_, st, c_, col) in enumerate(rows):
        yy = y + 62 + r * 56
        s.add(f'<line x1="{x+20}" y1="{yy}" x2="{x+w-20}" y2="{yy}" stroke="#fff" stroke-opacity=".07" stroke-width="2"/>')
        s.add(rr(cols[0], yy + 12, 32, 32, 10, accent, .3) + tx(cols[0] + 46, yy + 35, a_, 17, '#fff', 600) + tx(cols[1], yy + 35, b_, 16, '#9aa6c6', 500))
        s.add(rr(cols[2], yy + 14, 92, 28, 14, col, .18) + tx(cols[2] + 46, yy + 34, st, 13, col, 700, 'middle') + tx(cols[3], yy + 35, c_, 17, '#fff', 700, 'end'))


def phone(s, x, y, w, h, rot=0, ui=None, accent='#0db5bc', title=''):
    cx, cy = x + w / 2, y + h / 2
    s.add(f'<g transform="rotate({rot} {cx} {cy})"><g filter="url(#shadow)">{rr(x, y, w, h, 54, "#070b17", 1, "#2b3560", 7)}</g>')
    s.add(rr(x + 14, y + 14, w - 28, h - 28, 42, '#0c1226', 1))
    s.add(rr(x + w / 2 - 54, y + 26, 108, 28, 14, '#04060e', 1))
    s.add(tx(x + 40, y + 48, '9:41', 15, '#fff', 700))
    if ui:
        ui(s, x + 14, y + 70, w - 28, h - 98, accent)
    s.add('</g>')


def ui_app(s, x, y, w, h, accent):
    s.add(tx(x + 24, y + 30, 'Good morning', 16, '#9aa6c6', 500) + tx(x + 24, y + 62, 'Your week', 28, '#fff', 700))
    s.add(rr(x + w - 62, y + 12, 40, 40, 20, accent, 1))
    donut(s, x + w / 2, y + 190, 78, .78, accent, 'goal')
    for i, (lab, val) in enumerate([('Steps', '8.2k'), ('Kcal', '540'), ('Time', '48m')]):
        xx = x + 20 + i * ((w - 40) / 3)
        s.add(rr(xx, y + 300, (w - 40) / 3 - 10, 84, 18, '#fff', .06) + tx(xx + 14, y + 332, lab, 13, '#9aa6c6', 500) + tx(xx + 14, y + 366, val, 24, '#fff', 700))
    for i in range(3):
        yy = y + 408 + i * 78
        s.add(rr(x + 20, yy, w - 40, 66, 18, '#fff', .06) + rr(x + 34, yy + 12, 42, 42, 13, [accent, '#f28a2a', '#ec5a5e'][i], .9))
        s.add(rr(x + 92, yy + 16, 120, 12, 6, '#fff', .75) + rr(x + 92, yy + 38, 80, 9, 4.5, '#fff', .3) + rr(x + w - 90, yy + 22, 54, 22, 11, accent, .25))


def ui_list(s, x, y, w, h, accent):
    s.add(tx(x + 24, y + 38, 'Classes', 28, '#fff', 700))
    for i in range(6):
        yy = y + 70 + i * 86
        s.add(rr(x + 18, yy, w - 36, 74, 18, '#fff', .06) + rr(x + 32, yy + 12, 50, 50, 15, ['#ec5a5e', accent, '#f2d624'][i % 3], .85))
        s.add(rr(x + 98, yy + 18, 150, 12, 6, '#fff', .75) + rr(x + 98, yy + 40, 96, 9, 4.5, '#fff', .3) + rr(x + w - 92, yy + 24, 56, 26, 13, '#fff', .12))


def ui_shop(s, x, y, w, h, accent):
    s.add(rr(x + 20, y + 14, w - 40, 44, 22, '#fff', .08) + tx(x + 48, y + 43, 'Search products', 15, '#8c97b8', 500))
    s.add(rr(x + 20, y + 74, w - 40, 150, 24, accent, .9) + rr(x + 44, y + 100, 150, 16, 8, '#fff', .9) + rr(x + 44, y + 128, 110, 12, 6, '#fff', .55) + rr(x + 44, y + 166, 96, 34, 17, '#0a0f1f', .8))
    for i in range(4):
        xx = x + 20 + (i % 2) * ((w - 52) / 2 + 12)
        yy = y + 246 + (i // 2) * 210
        s.add(rr(xx, yy, (w - 52) / 2, 190, 22, '#fff', .06) + rr(xx + 12, yy + 12, (w - 52) / 2 - 24, 100, 16, ['#f28a2a', '#ec5a5e', accent, '#f2d624'][i], .8))
        s.add(rr(xx + 16, yy + 128, 70, 11, 5, '#fff', .75) + rr(xx + 16, yy + 150, 48, 9, 4.5, '#fff', .35))


def ad_card(s, x, y, w, h, rot, a, b, caption, likes):
    cx, cy = x + w / 2, y + h / 2
    gid = s.uid()
    s.add(f'<defs><linearGradient id="ad{gid}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="{a}"/><stop offset="1" stop-color="{b}"/></linearGradient></defs>')
    s.add(f'<g transform="rotate({rot} {cx} {cy})"><g filter="url(#shadow)">{rr(x, y, w, h, 46, "#070b17", 1, "#2b3560", 7)}</g>')
    s.add(rr(x + 12, y + 12, w - 24, h - 24, 36, f'url(#ad{gid})', 1))
    s.add(f'<clipPath id="cp{gid}"><rect x="{x+12}" y="{y+12}" width="{w-24}" height="{h-24}" rx="36"/></clipPath><g clip-path="url(#cp{gid})"><circle cx="{x+w*.75}" cy="{y+h*.25}" r="{w*.35}" fill="#fff" opacity=".14"/><circle cx="{x+w*.2}" cy="{y+h*.8}" r="{w*.4}" fill="#000" opacity=".14"/></g>')
    s.add(rr(x + w / 2 - 50, y + 22, 100, 24, 12, '#04060e', 1))
    s.add(f'<circle cx="{x+44}" cy="{y+88}" r="17" fill="#fff"/>' + rr(x + 70, y + 76, 110, 12, 6, '#fff', .9) + tx(x + 70, y + 106, 'Sponsored', 13, '#fff', 600, op=.85))
    s.add(f'<circle cx="{x+w/2}" cy="{y+h*.45}" r="52" fill="#fff" fill-opacity=".95"/><polygon points="{x+w/2-14},{y+h*.45-22} {x+w/2-14},{y+h*.45+22} {x+w/2+24},{y+h*.45}" fill="#0a0f1f"/>')
    for i, t in enumerate(['♥', '✎', '➤']):
        s.add(f'<circle cx="{x+w-46}" cy="{y+h*.62+i*58}" r="21" fill="#000" fill-opacity=".35"/>' + tx(x + w - 46, y + h * .62 + i * 58 + 7, t, 20, '#fff', 700, 'middle'))
    s.add(tx(x + 30, y + h - 128, caption, 24, '#fff', 700) + tx(x + 30, y + h - 98, f'♥ {likes}  ·  Free delivery', 15, '#fff', 500, op=.85))
    s.add(rr(x + 28, y + h - 74, w - 56, 46, 14, '#1877f2', 1) + tx(x + w / 2, y + h - 43, 'Shop now', 18, '#fff', 700, 'middle'))
    s.add('</g>')


def chip(s, x, y, big, small, accent='#34d399'):
    s.add(f'<g filter="url(#shadow)">{rr(x, y, 300, 110, 28, "#0a0f1f", .94, "#ffffff", 2).replace("stroke=", "stroke-opacity=\".18\" stroke=")}</g>')
    s.add(rr(x + 22, y + 26, 58, 58, 18, accent, .22) + f'<polyline points="{x+36},{y+64} {x+50},{y+48} {x+60},{y+58} {x+72},{y+40}" fill="none" stroke="{accent}" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>')
    s.add(tx(x + 98, y + 60, big, 38, '#fff', 800) + tx(x + 98, y + 90, small, 16, '#9aa6c6', 500))


def dashboard_cover(theme, kind):
    s = S(theme)
    W, H = 1600, 1100
    bg(s)
    cfg = {
        'pos':      (['Sales', 'Products', 'Stock', 'Suppliers', 'Reports', 'Customers'], ["Today's sales", 'Orders', 'Items sold'], ['$12,480', '342', '1,906'], 'Revenue — checkout speed',
                     [('Rice 5kg', 'INV-1042', 'Paid', '$12.40', '#34d399'), ('Olive Oil', 'INV-1043', 'Paid', '$9.90', '#34d399'), ('Milk 1L ×3', 'INV-1044', 'Due', '$4.80', '#f2d624')]),
        'pharma':   (['Medicines', 'Batches', 'Expiry', 'Purchase', 'Billing', 'Reports'], ['Expiring soon', 'Low stock', "Today's sales"], ['12', '8', '$1,284'], 'Sales vs expired stock',
                     [('Paracetamol', 'PC-2291', 'OK', '420', '#34d399'), ('Amoxicillin', 'AM-1180', '12 days', '36', '#ec5a5e'), ('Omeprazole', 'OM-0457', '2 mo', '88', '#f2d624')]),
        'portal':   (['Listings', 'Agents', 'Leads', 'Map view', 'Reports', 'Settings'], ['Active listings', 'New leads', 'Viewings'], ['1,284', '+120%', '86'], 'Qualified leads',
                     [('Marina Tower', 'LST-204', 'Live', '$420k', '#34d399'), ('Palm Villa', 'LST-198', 'Review', '$1.2M', '#f2d624'), ('City Loft', 'LST-187', 'Live', '$310k', '#34d399')]),
        'school':   (['Students', 'Fees', 'Exams', 'Attendance', 'Payroll', 'Reports'], ['Students', 'Fees collected', 'Attendance'], ['1,420', '$86k', '96%'], 'Fee collection',
                     [('Grade 8-A', 'CLS-08', 'Paid', '$12.4k', '#34d399'), ('Grade 9-B', 'CLS-19', 'Partial', '$8.1k', '#f2d624'), ('Grade 6-C', 'CLS-06', 'Paid', '$10.9k', '#34d399')]),
        'fleet':    (['Live map', 'Vehicles', 'Drivers', 'Shipments', 'Alerts', 'Reports'], ['On route', 'Delivered', 'Uptime'], ['148', '2,904', '99.9%'], 'Deliveries per day',
                     [('Truck AL-12', 'SHP-9921', 'On route', '312km', '#0db5bc'), ('Van AL-07', 'SHP-9918', 'Delivered', '84km', '#34d399'), ('Truck AL-03', 'SHP-9915', 'Delayed', '410km', '#ec5a5e')]),
    }[kind]
    items, labels, vals, chart_title, rows = cfg
    bx, by, bw, bh = 170, 150, 1260, 800
    ix, iy, iw, ih = browser(s, bx, by, bw, bh, {'pos': 'pos.freshcart.app', 'pharma': 'pharmacy.mediplus.app', 'portal': 'portal.urbannest.app', 'school': 'erp.brightpath.app', 'fleet': 'fleet.alpine.app'}[kind])
    sw = 230
    sidebar(s, ix, iy, sw, ih, items, s.a)
    mx, my, mw = ix + sw + 28, iy + 28, iw - sw - 56
    kw = (mw - 40) / 3
    for i in range(3):
        kpi(s, mx + i * (kw + 20), my, kw, 124, labels[i], vals[i], ['+18%', '+9%', '+24%'][i], [s.a, '#f28a2a', '#ec5a5e'][i])
    linechart(s, mx, my + 144, mw * .62, 270, s.a, sum(map(ord, kind)) % 100, chart_title)
    s.add(rr(mx + mw * .62 + 20, my + 144, mw * .38 - 20, 270, 20, '#fff', .05, '#fff', 1.5).replace('stroke=', 'stroke-opacity=".1" stroke='))
    donut(s, mx + mw * .62 + 20 + (mw * .38 - 20) / 2, my + 144 + 125, 66, .72, s.a, 'target')
    table(s, mx, my + 434, mw, rows, s.a)
    if kind in ('portal', 'pos', 'pharma'):
        phone(s, 1230, 420, 300, 600, 7, ui_shop, s.a)
    return s


def mobile_cover(theme):
    s = S(theme)
    bg(s)
    phone(s, 330, 170, 420, 860, -9, ui_list, s.a)
    phone(s, 760, 110, 470, 900, 6, ui_app, s.a)
    return s


def ecommerce_cover(theme):
    s = S(theme)
    bg(s)
    ix, iy, iw, ih = browser(s, 170, 160, 1260, 780, 'stylehub.shop')
    s.add(rr(ix, iy, iw, 70, 0, '#0c1226', 1) + rr(ix + 34, iy + 20, 130, 30, 8, s.a, 1))
    for i, t in enumerate(['New', 'Women', 'Men', 'Sale']):
        s.add(tx(ix + 280 + i * 120, iy + 44, t, 18, '#fff', 600, op=.8))
    s.add(rr(ix + iw - 170, iy + 16, 130, 38, 19, '#fff', .1) + tx(ix + iw - 105, iy + 41, 'Cart (3)', 16, '#fff', 700, 'middle'))
    s.add(rr(ix + 34, iy + 96, iw - 68, 190, 26, s.a, .9) + rr(ix + 74, iy + 140, 330, 26, 13, '#fff', .95) + rr(ix + 74, iy + 184, 240, 16, 8, '#fff', .6) + rr(ix + 74, iy + 222, 150, 42, 21, '#0a0f1f', .85))
    s.add(f'<circle cx="{ix+iw-190}" cy="{iy+190}" r="130" fill="#fff" opacity=".18"/><circle cx="{ix+iw-120}" cy="{iy+150}" r="70" fill="#fff" opacity=".22"/>')
    tw = (iw - 68 - 3 * 22) / 4
    cols = [s.a, '#f28a2a', '#ec5a5e', '#f2d624']
    for i in range(4):
        xx = ix + 34 + i * (tw + 22)
        yy = iy + 312
        s.add(rr(xx, yy, tw, 340, 22, '#fff', .05, '#fff', 1.5).replace('stroke=', 'stroke-opacity=".1" stroke='))
        s.add(rr(xx + 12, yy + 12, tw - 24, 220, 16, cols[i], .75))
        s.add(f'<path d="M{xx+tw/2-46} {yy+60} l24 -22 h44 l24 22 l-16 24 l-14 -10 v102 h-52 v-102 l-14 10z" fill="#fff" fill-opacity=".85"/>')
        s.add(rr(xx + 22, yy + 252, 120, 13, 6, '#fff', .8) + rr(xx + 22, yy + 278, 70, 10, 5, '#fff', .35) + rr(xx + tw - 90, yy + 270, 68, 34, 17, cols[i], .35) + tx(xx + tw - 56, yy + 293, '$49', 15, '#fff', 700, 'middle'))
    phone(s, 110, 560, 270, 500, -8, ui_shop, s.a)
    return s


def ads_cover(theme, variant):
    s = S(theme)
    bg(s)
    a, b = s.a, s.b
    caps = {'meta': ('Taste the tradition', '12.4k'), 'ai': ('Meet the new you', '28.1k')}[variant]
    ad_card(s, 180, 210, 360, 680, -9, '#f28a2a', '#ec5a5e', caps[0], caps[1])
    ad_card(s, 1060, 200, 360, 680, 9, '#f2d624', '#ec5a5e', 'Limited offer', '9.7k')
    ad_card(s, 570, 120, 460, 880, 0, a, '#1b2a6b', caps[0] if variant == 'ai' else 'Order tonight', caps[1])
    return s


def write_cover(slug, s, W=1600, H=1100):
    save(f'portfolio/{slug}.svg', f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" width="{W}" height="{H}" preserveAspectRatio="xMidYMid slice">' + ''.join(s.o) + '</svg>')


COVERS = {
    'retail-pos-inventory':      lambda: dashboard_cover('teal', 'pos'),
    'pharmacy-management-suite': lambda: dashboard_cover('mint', 'pharma'),
    'fitness-mobile-app':        lambda: mobile_cover('coral'),
    'real-estate-portal':        lambda: dashboard_cover('orange', 'portal'),
    'food-brand-meta-ads':       lambda: ads_cover('yellow', 'meta'),
    'school-erp':                lambda: dashboard_cover('violet', 'school'),
    'fashion-ecommerce':         lambda: ecommerce_cover('coral'),
    'ai-video-campaign':         lambda: ads_cover('teal', 'ai'),
    'logistics-dashboard':       lambda: dashboard_cover('mint', 'fleet'),
}

if __name__ == '__main__':
    for i, (slug, spec) in enumerate(PEOPLE.items()):
        bgc, skin, hair, style, shirt, gl, bd, sc = spec
        save(f'team/{slug}.svg', portrait(slug, bgc, skin, hair, 'scarf' if sc else style, shirt, gl, bd, sc, i))
    for i, (slug, (kind, theme)) in enumerate(CLIENT_LOGOS.items()):
        save(f'clients/{slug}.svg', logo(kind, theme, i))
    for slug, fn in COVERS.items():
        write_cover(slug, fn())
    print('generated', len(PEOPLE), 'portraits,', len(CLIENT_LOGOS), 'logos,', len(COVERS), 'covers')
