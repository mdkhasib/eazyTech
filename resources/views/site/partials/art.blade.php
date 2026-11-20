@php
    $cat = $item->category;
    $cover = $item->cover_url;
@endphp
<div class="art t-{{ $item->theme }}" @if($cover) style="background-image:url('{{ $cover }}')" @endif>
@if(! $cover)
    @if($cat === 'mobile')
        <div class="phone"><span class="pill"></span><div class="hero-b"></div><i></i><i></i><i></i><i></i></div>
        <div class="phone p2"><span class="pill"></span><div class="hero-b"></div><i></i><i></i><i></i></div>
    @elseif(in_array($cat, ['marketing', 'ai'], true))
        <div class="adcard c2"><span class="spons">SPONSORED</span></div>
        <div class="adcard c3"><span class="spons">SPONSORED</span></div>
        <div class="adcard"><span class="spons">SPONSORED</span><span class="play"><x-icon name="play" :size="22" /></span><span class="ctr"><span>♥ 12.4k</span><span>Shop now ›</span></span></div>
    @else
        <div class="ui">
            <div class="bar"><i></i><i></i><i></i></div>
            <div class="body">
                <div class="side"><b></b><b></b><b></b><b></b></div>
                <div class="main"><div class="kpis"><i></i><i></i><i></i></div>
                    <div class="chart"><u style="height:38%"></u><u style="height:62%"></u><u style="height:48%"></u><u style="height:80%"></u><u style="height:58%"></u><u style="height:92%"></u><u style="height:70%"></u></div>
                </div>
            </div>
        </div>
    @endif
@endif
</div>
