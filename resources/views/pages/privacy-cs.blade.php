@extends('layouts.app')

@section('title', 'Ochrana osobních údajů')
@section('meta_description', 'Informace o zpracování osobních údajů a ochraně soukromí na IrskoStudy — jak a proč shromažďujeme data.')

@section('content')
<div class="container mx-auto px-4 py-12">
    <article class="prose lg:prose-lg mx-auto">
        <h1>Ochrana osobních údajů</h1>
        <p>Tato stránka stručně popisuje, jak tento web zpracovává osobní údaje. Je to jednoduché shrnutí určené pro běžné uživatele.</p>

        <h2>1) Kdo je správcem</h2>
        <p>Správcem osobních údajů je provozovatel webu. V případě dotazů kontaktujte: <a href="mailto:info@irsko.ie">privacy@irsko.ie</a> (upravte na reálný kontakt).</p>

        <h2>2) Jaké údaje shromažďujeme</h2>
        <ul>
            <li>Údaje z kontaktních formulářů (jméno, e‑mail, zpráva).</li>
            <li>Technická metadata (IP adresa, typ prohlížeče) zaznamenaná v logu pro bezpečnost a analytiku.</li>
            <li>Cookies a nástroje pro analytiku (pokud jsou aktivní).</li>
        </ul>

        <h2>3) K čemu údaje používáme</h2>
        <p>Údaje slouží k odpovědi na dotazy, technickému provozu webu, bezpečnosti a zlepšování služeb. Kontaktní zprávy mohou být ukládány v logu nebo odeslány na e‑mail správce.</p>

        <h2>4) Jak dlouho údaje uchováváme</h2>
        <p>Uchováváme údaje pouze po dobu nutnou pro účely, pro které byly shromážděny (např. do vyřízení dotazu). Dále mohou být záznamy uchovávány kratší dobu v protokolech pro potřeby bezpečnosti.</p>

        <h2>5) Práva uživatelů</h2>
        <p>Máte právo požádat o přístup k údajům, jejich opravu, vymazání nebo omezení zpracování. Pro uplatnění práv kontaktujte správce na e‑mailu uvedeném výše.</p>

        <h2>6) Bezpečnost</h2>
        <p>Web používá HTTPS. Přijímáme rozumná technická a organizační opatření k ochraně údajů, ale žádný přenos po internetu není úplně bez rizika.</p>

        <p>Pokud potřebujete podrobnější zásady (např. zpracování cookies nebo dodavatele služeb třetích stran), dejte vědět a doplním rozšířenou verzi.</p>
    </article>
</div>
@endsection
